<div>
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Cuenta Corriente</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Pagos registrados a cuenta corriente.
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg px-4 py-2">
                    <span class="text-sm text-amber-700 dark:text-amber-300">Total Pendiente:</span>
                    <span class="ml-2 text-lg font-bold text-amber-800 dark:text-amber-200">${{ number_format($totalPending, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="mt-4 mb-6">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar por cliente, DNI o patente..." class="w-full sm:w-96" />
        </div>

        <div class="mt-4 bg-white dark:bg-zinc-900 shadow-sm ring-1 ring-zinc-300 dark:ring-zinc-800 sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-zinc-900 dark:text-white sm:pl-6">Fecha</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">Cliente</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">Vehículo</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">Orden de Trabajo</th>
                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-zinc-900 dark:text-white">Monto</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">Usuario</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6 text-zinc-500 dark:text-zinc-400">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <span class="font-medium text-zinc-900 dark:text-white">
                                    {{ $payment->invoice->customer->last_name }}, {{ $payment->invoice->customer->first_name }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                @if($payment->invoice->workOrder?->vehicle)
                                    {{ $payment->invoice->workOrder->vehicle->brand }} {{ $payment->invoice->workOrder->vehicle->model }}
                                    <span class="text-xs uppercase">({{ $payment->invoice->workOrder->vehicle->license_plate }})</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                @if($payment->invoice->workOrder)
                                    <a href="{{ route('work-orders.edit', $payment->invoice->workOrder->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        OT #{{ $payment->invoice->workOrder->id }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold text-zinc-900 dark:text-white">
                                ${{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $payment->user->name }}
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('pos.current-account.pdf', $payment->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Imprimir comprobante">
                                    <flux:icon name="printer" class="w-5 h-5" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                No hay pagos registrados en cuenta corriente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($payments->hasPages())
                <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-800 sm:px-6">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
