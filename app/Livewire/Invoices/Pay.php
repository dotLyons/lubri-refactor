<?php

namespace App\Livewire\Invoices;

use App\Src\Invoices\Models\Invoice;
use App\Src\POS\Actions\RegisterMovement;
use App\Src\POS\Enums\MovementType;
use App\Src\POS\Enums\PaymentMethod;
use App\Src\POS\Models\Card;
use App\Src\POS\Models\CardPlan;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class Pay extends Component
{
    public ?Invoice $invoice = null;

    // Usaremos un array de pagos para admitir cobros divididos
    public array $payments = [];

    public function mount($id)
    {
        $this->invoice = Invoice::with(['customer', 'workOrder.vehicle', 'payments'])->findOrFail($id);

        if ($this->invoice->balance_due > 0) {
            $this->addPaymentRow();
        }
    }

    public function addPaymentRow()
    {
        $remaining = $this->invoice->balance_due - collect($this->payments)->sum('baseAmountToPay');
        if ($remaining < 0) {
            $remaining = 0;
        }

        $this->payments[] = [
            'id' => uniqid(),
            'method' => 'cash',
            'baseAmountToPay' => (float) $remaining,
            'cardId' => '',
            'planId' => '',
            'surchargeAmount' => 0,
            'totalAmountToPay' => (float) $remaining,
        ];

        $this->calculateTotals();
    }

    public function removePaymentRow($index)
    {
        if (count($this->payments) > 1) {
            unset($this->payments[$index]);
            $this->payments = array_values($this->payments);
            $this->calculateTotals();
        }
    }

    public function updatedPayments()
    {
        $this->calculateTotals();
    }

    protected function calculateTotals()
    {
        $totalBase = 0;
        foreach ($this->payments as $k => &$payment) {
            if ($payment['baseAmountToPay'] === '') {
                $payment['baseAmountToPay'] = 0;
            }
            $payment['baseAmountToPay'] = (float) $payment['baseAmountToPay'];
            if ($payment['baseAmountToPay'] < 0) {
                $payment['baseAmountToPay'] = 0;
            }

            // Calculate surcharge
            $payment['surchargeAmount'] = 0;
            if (in_array($payment['method'], ['credit_card', 'debit_card']) && $payment['planId']) {
                $plan = CardPlan::find($payment['planId']);
                if ($plan && $plan->surcharge_percentage > 0) {
                    $payment['surchargeAmount'] = ($payment['baseAmountToPay'] * (float) $plan->surcharge_percentage) / 100;
                }
            }

            $payment['totalAmountToPay'] = $payment['baseAmountToPay'] + $payment['surchargeAmount'];

            // Auto reset card and plan if method changed
            if (! in_array($payment['method'], ['credit_card', 'debit_card'])) {
                $payment['cardId'] = '';
                $payment['planId'] = '';
            }

            $totalBase += $payment['baseAmountToPay'];
        }
    }

    public function processPayments()
    {
        // Validaciones
        if ($this->invoice->status->value === 'paid') {
            Flux::toast('La factura ya se encuentra pagada.', variant: 'warning');

            return;
        }

        $totalBaseToPay = collect($this->payments)->sum('baseAmountToPay');

        if ($totalBaseToPay <= 0) {
            Flux::toast('El monto total a pagar debe ser mayor a 0.', variant: 'danger');

            return;
        }

        foreach ($this->payments as $k => $payment) {
            if (in_array($payment['method'], ['credit_card', 'debit_card']) && empty($payment['planId'])) {
                Flux::toast('Seleccione un plan de tarjeta válido para el método de pago.', variant: 'danger');

                return;
            }
        }

        try {
            DB::transaction(function () use ($totalBaseToPay) {
                if ($totalBaseToPay > $this->invoice->balance_due + 0.1) {
                    throw new Exception('El monto ingresado ($'.number_format($totalBaseToPay, 2).') es mayor al saldo pendiente de la factura ($'.number_format($this->invoice->balance_due, 2).')');
                }

                $transactionGroupId = (string) Str::uuid();

                foreach ($this->payments as $payment) {
                    $baseToCancel = (float) $payment['baseAmountToPay'];
                    $surchargeToApply = (float) $payment['surchargeAmount'];
                    $totalPaidByCustomer = (float) $payment['totalAmountToPay'];

                    if ($surchargeToApply > 0) {
                        $this->invoice->total_amount += $surchargeToApply;
                        $this->invoice->balance_due += $surchargeToApply;
                        $this->invoice->save();
                    }

                    $descriptionInfo = count($this->payments) > 1 ? 'Pago Múltiple/Diferido Factura #' : 'Pago de Factura #';
                    $description = $descriptionInfo.$this->invoice->id.' - Cliente: '.$this->invoice->customer->first_name;

                    $registerMovement = new RegisterMovement;
                    $movement = $registerMovement->execute(
                        user: Auth::user(),
                        type: MovementType::Income,
                        amount: $totalPaidByCustomer,
                        paymentMethod: PaymentMethod::from($payment['method']),
                        cardPlanId: ! empty($payment['planId']) ? $payment['planId'] : null,
                        description: $description,
                        isManual: false,
                        passcode: null,
                        transactionGroupId: count($this->payments) > 1 ? $transactionGroupId : null
                    );

                    $this->invoice->payments()->create([
                        'user_id' => Auth::id(),
                        'cash_register_movement_id' => $movement->id,
                        'amount' => $totalPaidByCustomer,
                        'payment_method' => $payment['method'],
                        'card_plan_id' => ! empty($payment['planId']) ? $payment['planId'] : null,
                        'description' => count($this->payments) > 1 ? 'Cobro fraccionado registrado' : 'Cobro registrado',
                    ]);

                    $this->invoice->paid_amount += $totalPaidByCustomer;
                    $this->invoice->balance_due -= $totalPaidByCustomer;
                    $this->invoice->save();
                }

                if ($this->invoice->balance_due <= 0.01) {
                    $this->invoice->balance_due = 0;
                    $this->invoice->status = 'paid';
                } else {
                    $this->invoice->status = 'partial';
                }

                $this->invoice->save();
            });

            Flux::toast('Pago registrado exitosamente.', variant: 'success');

            $this->invoice->refresh();
            $this->payments = [];

            if ($this->invoice->balance_due > 0) {
                $this->addPaymentRow();
            }

            if ($this->invoice->status->value === 'paid') {
                return redirect()->route('work-orders.index');
            }

        } catch (Exception $e) {
            Flux::toast($e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        $creditCards = Card::where('type', 'credit')->where('is_active', true)->get();
        $debitCards = Card::where('type', 'debit')->where('is_active', true)->get();

        return view('livewire.invoices.pay', [
            'creditCards' => $creditCards,
            'debitCards' => $debitCards,
            'allPlansCollection' => CardPlan::where('is_active', true)->get()->groupBy('card_id'),
        ]);
    }
}
