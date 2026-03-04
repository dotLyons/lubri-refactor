<div>
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Órdenes de Trabajo</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Turnos y trabajos asignados al taller y lavadero.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <flux:modal.trigger name="create-work-order">
                    <flux:button variant="primary" icon="plus">
                        Nuevo Turno
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-4 mb-4">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar por DNI, Cliente o Patente..." class="w-full sm:w-96" />
        </div>

        <div class="mt-4 bg-white dark:bg-zinc-900 shadow-sm ring-1 ring-zinc-300 dark:ring-zinc-800 sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-zinc-900 dark:text-white sm:pl-6">Destino / Estado</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">Cliente y Vehículo</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">Fecha Programada</th>
                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-zinc-900 dark:text-white">Total Actual ($)</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                    @forelse($workOrders as $order)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                <div class="font-medium text-zinc-900 dark:text-white mb-1">
                                    {{ $order->destination->label() }}
                                </div>
                                @if($order->status->value === 'open')
                                    <flux:badge size="sm" color="amber">Abierta</flux:badge>
                                @else
                                    <flux:badge size="sm" color="emerald">Cerrada</flux:badge>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $order->customer->last_name }}, {{ $order->customer->first_name }}</span>
                                <div class="text-xs text-zinc-500 mt-0.5">
                                    {{ $order->vehicle->brand }} {{ $order->vehicle->model }} (<span class="uppercase">{{ $order->vehicle->license_plate }}</span>)
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $order->scheduled_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold text-zinc-900 dark:text-white">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <flux:dropdown align="end">
                                    <flux:button variant="ghost" size="sm" icon="ellipsis-vertical" class="!px-2" />
                                    <flux:menu>
                                        <flux:menu.item icon="document-text" href="{{ route('work-orders.edit', $order->id) }}" wire:navigate>Gestionar Turno</flux:menu.item>
                                        @if($order->status->value === 'open')
                                            <flux:menu.separator />
                                            <flux:menu.item icon="trash" variant="danger" wire:click="deleteWorkOrder({{ $order->id }})" wire:confirm="¿Seguro que deseas cancelar y eliminar esta orden de trabajo?">Eliminar</flux:menu.item>
                                        @endif
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                No se encontraron turnos u órdenes de trabajo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($workOrders->hasPages())
                <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-800 sm:px-6">
                    {{ $workOrders->links() }}
                </div>
            @endif
        </div>
    </div>
    
    @livewire('work-orders.create')
</div>
