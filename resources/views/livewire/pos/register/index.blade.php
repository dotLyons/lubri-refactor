<div>
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Caja (POS)</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Control de turnos, ingresos, egresos y control de efectivo.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex items-center gap-3">
                @if($activeRegister)
                    <flux:modal.trigger name="manual-movement">
                        <flux:button variant="subtle" icon="arrows-right-left">
                            <span class="hidden sm:inline">Movimiento Manual</span>
                        </flux:button>
                    </flux:modal.trigger>
                    
                    <flux:button variant="danger" icon="lock-closed" wire:click="$dispatch('register-close-request')">
                        <span class="hidden sm:inline">Cerrar Caja</span>
                    </flux:button>
                @endif
            </div>
        </div>

        <div class="mt-8">
            @if(! $activeRegister)
                <!-- Register Closed State -->
                <div class="text-center rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-dashed border-zinc-200 dark:border-zinc-700 p-16">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-900 mb-4">
                        <x-flux::icon.lock-closed class="h-10 w-10 text-zinc-400 dark:text-zinc-500" />
                    </div>
                    <h3 class="mt-2 text-lg font-bold text-zinc-900 dark:text-white">La caja está cerrada</h3>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto">Para comenzar a operar y registrar movimientos necesitas realizar la apertura de caja de tu turno.</p>
                    <div class="mt-8">
                        <flux:modal.trigger name="open-register">
                            <flux:button variant="primary" icon="lock-open">
                                Realizar Apertura
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                </div>
            @else
                <!-- Active Register Dashboard -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Status Card -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Estado de Caja</h3>
                                <span class="bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Abierta
                                </span>
                            </div>
                            <div class="mt-4">
                                <p class="text-xs text-zinc-500 mb-1">Cajero: <span class="font-medium text-zinc-900 dark:text-white">{{ $activeRegister->user->name }}</span></p>
                                <p class="text-xs text-zinc-500 mb-1">Apertura: <span class="font-medium text-zinc-900 dark:text-white">{{ $activeRegister->opened_at->format('d/m/Y H:i') }}</span></p>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                            <span class="text-sm font-medium text-zinc-500">Monto Inicial</span>
                            <span class="text-lg font-bold text-zinc-900 dark:text-white">${{ number_format($activeRegister->opening_amount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Cash In Drawer -->
                    <div class="bg-indigo-600 dark:bg-indigo-500 rounded-xl shadow-sm border border-indigo-500 p-6 flex flex-col justify-center text-white relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <h3 class="text-sm font-medium text-indigo-100 uppercase tracking-wider relative z-10 mb-2">Total Registrado Hoy</h3>
                        <div class="relative z-10 flex items-baseline gap-2">
                            <span class="text-4xl font-black">${{ number_format($cashInDrawer, 2) }}</span>
                        </div>
                        <p class="text-xs text-indigo-200 mt-2 relative z-10 mb-2">Monto Inicial + Total Ingreos - Total Egresos</p>
                    </div>

                    <!-- Day Totals -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 p-6 flex flex-col justify-center gap-4">
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                    <flux:icon.arrow-trending-up class="size-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Ingresos (Total)</p>
                                    <p class="text-xl font-bold text-zinc-900 dark:text-white">${{ number_format($totalIncomes, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center text-red-600 dark:text-red-400">
                                    <flux:icon.arrow-trending-down class="size-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Egresos (Total)</p>
                                    <p class="text-xl font-bold text-zinc-900 dark:text-white">${{ number_format($totalExpenses, 2) }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Movements List -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Últimos Movimientos del Turno</h3>
                        </div>
                        
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <select wire:model.live="typeFilter" class="block w-full sm:w-40 py-2 pl-3 pr-10 text-sm border-zinc-300 rounded-lg leading-5 bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-100 shadow-sm">
                                <option value="">Todos los tipos</option>
                                <option value="income">Ingreso (+)</option>
                                <option value="expense">Egreso (-)</option>
                            </select>

                            <select wire:model.live="paymentMethodFilter" class="block w-full sm:w-48 py-2 pl-3 pr-10 text-sm border-zinc-300 rounded-lg leading-5 bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-100 shadow-sm">
                                <option value="">Todos los métodos</option>
                                @foreach(\App\Src\POS\Enums\PaymentMethod::cases() as $method)
                                    <option value="{{ $method->value }}">{{ $method->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                            <thead class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">Hora</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">Motivo/Descripción</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">Método Pago</th>
                                    <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                                @forelse($movements as $mov)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $mov->created_at->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                @if($mov->type->value === 'income')
                                                    <flux:icon.arrow-up-right class="size-4 text-emerald-500 shrink-0" />
                                                @else
                                                    <flux:icon.arrow-down-right class="size-4 text-red-500 shrink-0" />
                                                @endif
                                                <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                                    {{ $mov->description ?: 'Venta' }}
                                                    @if($mov->is_manual)
                                                        <span class="ml-2 inline-flex items-center rounded-md bg-zinc-100 px-1.5 py-0.5 text-[10px] font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">MANUAL</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-zinc-900 dark:text-zinc-200">
                                                {{ $mov->payment_method->label() }}
                                            </div>
                                            @if($mov->cardPlan)
                                                <div class="text-[10px] text-zinc-500">
                                                    {{ $mov->cardPlan->card->name }} ({{ $mov->cardPlan->name }})
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $mov->type->value === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $mov->type->value === 'income' ? '+' : '-' }}${{ number_format($mov->amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                            Sin movimientos en este turno.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($movements && $movements->hasPages())
                        <div class="border-t border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 sm:px-6">
                            {{ $movements->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @livewire('pos.register.open')
    @livewire('pos.register.close')
    @livewire('pos.register.movement')
</div>
