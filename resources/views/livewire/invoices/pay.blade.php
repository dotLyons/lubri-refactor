<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-black text-zinc-800 dark:text-zinc-200 uppercase tracking-wider flex items-center gap-3">
                <div class="bg-indigo-600 p-2 rounded-xl">
                    <flux:icon.banknotes class="size-6 text-white" />
                </div>
                Cobro de Factura #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <div class="flex gap-2">
                <flux:button href="{{ route('work-orders.index') }}" variant="subtle" icon="arrow-left">Volver al Taller</flux:button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 relative items-start">
                
                {{-- Left Column: Invoice Details & Payment History --}}
                <div class="xl:col-span-7 flex flex-col gap-6">
                    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-2xl border border-zinc-200 dark:border-zinc-800">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4 border-b border-zinc-200 dark:border-zinc-800 pb-2">Información de la Factura</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div class="bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-zinc-100 dark:border-zinc-800">
                                    <span class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Cliente</span>
                                    <span class="text-zinc-900 dark:text-white font-medium">{{ $invoice->customer->first_name }} {{ $invoice->customer->last_name }}</span>
                                    <span class="block text-xs text-zinc-500 mt-1">DNI: {{ $invoice->customer->dni }}</span>
                                </div>
                                <div class="bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-zinc-100 dark:border-zinc-800">
                                    <span class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Vehículo Asociado</span>
                                    <span class="text-zinc-900 dark:text-white font-medium">{{ $invoice->workOrder->vehicle?->brand ?? 'S/R' }} {{ $invoice->workOrder->vehicle?->model ?? '' }}</span>
                                    <span class="block text-xs text-zinc-500 mt-1">Patente: {{ $invoice->workOrder->vehicle?->license_plate ?? 'S/R' }}</span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3 border-b border-zinc-200 dark:border-zinc-800 pb-2">Resumen Financiero</h4>
                                <div class="flex flex-col gap-2">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-zinc-500">Total a Pagar (Base):</span>
                                        <span class="font-bold text-zinc-900 dark:text-white">${{ number_format($invoice->total_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-emerald-600">Total Abonado:</span>
                                        <span class="font-bold text-emerald-600">${{ number_format($invoice->paid_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-lg mt-2 pt-2 border-t border-zinc-200 dark:border-zinc-800">
                                        <span class="font-bold text-zinc-900 dark:text-white">Saldo Pendiente:</span>
                                        <span class="font-black text-rose-600">${{ number_format($invoice->balance_due, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($invoice->payments->count() > 0)
                                <div>
                                    <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3 border-b border-zinc-200 dark:border-zinc-800 pb-2">Historial de Pagos</h3>
                                    <ul class="space-y-3">
                                        @foreach($invoice->payments as $payment)
                                            <li class="bg-zinc-50 dark:bg-zinc-800/50 p-3 rounded-xl border border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                                                <div>
                                                    <span class="block text-sm font-bold text-zinc-900 dark:text-white">{{ $payment->payment_method->label() }}</span>
                                                    <span class="block text-xs text-zinc-500">{{ $payment->created_at->format('d/m/Y H:i') }} @if($payment->cardPlan) | {{ $payment->cardPlan->name }} @endif</span>
                                                </div>
                                                <span class="text-emerald-600 font-bold text-sm">+${{ number_format($payment->amount, 2) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column: Payment Form --}}
                <div class="xl:col-span-5 space-y-6">
                    @if($invoice->status->value === 'paid')
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-6 shadow-sm border border-emerald-200 dark:border-emerald-800 sticky top-8">
                            <div class="flex items-center gap-4 mb-5 border-b border-emerald-200 dark:border-emerald-800 pb-5">
                                <div class="size-12 rounded-full bg-emerald-100 dark:bg-emerald-800 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                                    <flux:icon.check-circle class="size-7" />
                                </div>
                                <div>
                                    <h4 class="text-emerald-900 dark:text-emerald-100 text-lg font-bold leading-none mb-1.5">Factura Saldada</h4>
                                    <p class="text-xs text-emerald-600/80 dark:text-emerald-400 font-medium">Esta factura no registra deuda. Todos sus pagos fueron procesados correctamente.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-indigo-600 dark:bg-indigo-700 rounded-2xl p-6 shadow-xl border border-indigo-500 text-white flex flex-col justify-between overflow-hidden relative sticky top-8">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            
                            <div class="relative z-10 w-full">
                                <h4 class="text-xl font-black uppercase tracking-wider mb-3 drop-shadow-sm flex justify-between items-center border-b border-indigo-400/30 pb-4">
                                    Añadir Pago
                                    <flux:icon.currency-dollar class="size-7 text-white/50" />
                                </h4>

                                <div class="space-y-6 w-full">
                                    @foreach($payments as $index => $payment)
                                        <div class="bg-indigo-800/40 border border-indigo-500/30 rounded-xl p-4 relative font-mono">
                                            @if(count($payments) > 1)
                                                <button wire:click="removePaymentRow({{ $index }})" type="button" class="absolute -top-3 -right-3 bg-rose-500 hover:bg-rose-600 text-white rounded-full p-1 shadow-lg transition z-20">
                                                    <flux:icon.x-mark class="size-4" />
                                                </button>
                                            @endif
                                            
                                            <div class="flex justify-between items-center mb-4">
                                                <h5 class="text-xs font-bold uppercase tracking-widest text-indigo-300">Pago #{{ $index + 1 }}</h5>
                                            </div>

                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-200 mb-1.5">Medio de Pago</label>
                                                    <select wire:model.live="payments.{{ $index }}.method" class="w-full bg-indigo-900/50 border border-indigo-400/40 text-white text-sm rounded-lg focus:ring-4 focus:ring-indigo-300/30 focus:border-indigo-300 py-2.5 shadow-inner">
                                                        @foreach(\App\Src\POS\Enums\PaymentMethod::cases() as $method)
                                                            <option value="{{ $method->value }}">{{ $method->label() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                @if(in_array($payment['method'], ['credit_card', 'debit_card']))
                                                    <div class="mt-4">
                                                        <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-200 mb-1.5">Tarjeta</label>
                                                        <select wire:model.live="payments.{{ $index }}.cardId" class="w-full bg-indigo-900/50 border border-indigo-400/40 text-white text-sm rounded-lg focus:ring-4 focus:ring-indigo-300/30 focus:border-indigo-300 py-2.5 shadow-inner">
                                                            <option value="">Seleccionar Tarjeta...</option>
                                                            @php
                                                                $cards = $payment['method'] === 'credit_card' ? $creditCards : $debitCards;
                                                            @endphp
                                                            @foreach($cards as $card)
                                                                <option value="{{ $card->id }}">{{ $card->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                @if(!empty($payment['cardId']))
                                                    <div class="mt-4">
                                                        <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-200 mb-1.5">Plan de Cuotas</label>
                                                        <select wire:model.live="payments.{{ $index }}.planId" class="w-full bg-indigo-900/50 border border-indigo-400/40 text-white text-sm rounded-lg focus:ring-4 focus:ring-indigo-300/30 focus:border-indigo-300 py-2.5 shadow-inner">
                                                            <option value="">Seleccionar Plan...</option>
                                                            @if(!empty($allPlansCollection[$payment['cardId']]))
                                                                @foreach($allPlansCollection[$payment['cardId']] as $plan)
                                                                    <option value="{{ $plan->id }}">
                                                                        {{ $plan->name }} 
                                                                        @if($plan->surcharge_percentage > 0)
                                                                            (+{{ (float) $plan->surcharge_percentage }}%)
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="mt-4 border-t border-indigo-400/30 pt-4">
                                                    <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-100 mb-1.5">
                                                        Monto a Cancelar <span class="font-normal normal-case opacity-70">(Editable)</span>
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-indigo-200 font-bold sm:text-lg">$</span>
                                                        </div>
                                                        <input type="number" step="0.01" wire:model.live.debounce.400ms="payments.{{ $index }}.baseAmountToPay" class="w-full pl-7 bg-indigo-900/50 border border-indigo-400/40 text-white font-bold text-lg rounded-lg focus:ring-4 focus:ring-indigo-300/30 focus:border-indigo-300 py-2.5 shadow-inner block">
                                                    </div>
                                                </div>

                                                @if($payment['surchargeAmount'] > 0)
                                                    <div class="mt-4 bg-indigo-500/30 border border-indigo-400/50 p-3 rounded-lg flex justify-between items-center animate-in fade-in slide-in-from-top-2 duration-300">
                                                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-100">+ Recargo</span>
                                                        <span class="text-indigo-100 font-medium">+${{ number_format($payment['surchargeAmount'], 2) }}</span>
                                                    </div>
                                                @endif

                                                <div class="mt-4 bg-indigo-900/60 border border-indigo-400/50 p-3 rounded-xl flex justify-between items-center shadow-inner">
                                                    <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-100">Total a Cobrar</span>
                                                    <span class="text-xl text-white font-black">${{ number_format($payment['totalAmountToPay'], 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <button wire:click="addPaymentRow" type="button" class="w-full bg-indigo-800/30 hover:bg-indigo-800/50 border border-dashed border-indigo-400/50 rounded-xl py-3 text-indigo-200 font-bold text-sm tracking-wider uppercase transition-colors flex items-center justify-center gap-2">
                                        <flux:icon.plus-circle class="size-5" />
                                        Pago Diferido / Agregar Método
                                    </button>
                                </div>
                            </div>
                            
                            <div class="relative z-10 w-full mt-6 pt-6 border-t border-indigo-500/50">
                                <button wire:click="processPayments" wire:confirm="¿Registrar los pagos indicados en la cuenta?" class="w-full bg-white text-indigo-900 rounded-xl font-black text-xl py-4 transition-all shadow-xl hover:-translate-y-1 hover:shadow-indigo-900/50 active:translate-y-0 active:scale-95 duration-200 uppercase tracking-widest flex flex-col items-center justify-center gap-1">
                                    <span>REGISTRAR PAGOS</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
