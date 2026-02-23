<?php

namespace App\Src\POS\Commands;

use App\Src\POS\Actions\CloseCashRegister;
use App\Src\POS\Enums\CashRegisterStatus;
use App\Src\POS\Models\CashRegister;
use DB;
use Exception;
use Illuminate\Console\Command;

class CloseDailyCashRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:close-register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cierra automáticamente al final del día la caja que esté abierta.';

    /**
     * Execute the console command.
     */
    public function handle(CloseCashRegister $closeCashRegister): int
    {
        $openRegister = CashRegister::where('status', CashRegisterStatus::Open)->first();

        if (! $openRegister) {
            $this->info('No hay ninguna caja abierta para cerrar automáticamente.');
            return self::SUCCESS;
        }

        try {
            DB::beginTransaction();

            $closeCashRegister->execute(
                cashRegister: $openRegister, 
                closingActualAmount: null, 
                automatically: true
            );

            DB::commit();

            $this->info("La caja ID {$openRegister->id} se ha cerrado automáticamente.");
        } catch (Exception $e) {
            DB::rollBack();
            $this->error("Ocurrió un error al cerrar la caja: " . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
