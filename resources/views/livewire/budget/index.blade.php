<div>
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Presupuestos</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Gestión de presupuestos para clientes.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <flux:modal.trigger name="create-budget">
                    <flux:button variant="primary" icon="plus">
                        Nuevo Presupuesto
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
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-zinc-900 dark:text-white sm:pl-6">Estado</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">Cliente y Vehículo</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">Fecha de Creación</th>
                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-zinc-900 dark:text-white">Total ($)</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                    @forelse($budgets as $budget)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                @if($budget->status->value === 'open')
                                    <flux:badge size="sm" color="amber">Abierto</flux:badge>
                                @else
                                    <flux:badge size="sm" color="emerald">Cerrado</flux:badge>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $budget->customer->last_name }}, {{ $budget->customer->first_name }}</span>
                                <div class="text-xs text-zinc-500 mt-0.5">
                                    {{ $budget->vehicle->brand }} {{ $budget->vehicle->model }} (<span class="uppercase">{{ $budget->vehicle->license_plate }}</span>)
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $budget->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm">
                                <span class="font-bold text-zinc-900 dark:text-white">
                                    ${{ number_format($budget->total_amount, 2) }}
                                </span>
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <flux:dropdown align="end">
                                    <flux:button variant="ghost" size="sm" icon="ellipsis-vertical" class="!px-2" />
                                    <flux:menu>
                                        <flux:menu.item icon="document-text" href="{{ route('budgets.edit', $budget->id) }}" wire:navigate>Gestionar Presupuesto</flux:menu.item>
                                        @if($budget->status->value === 'open')
                                            <flux:menu.separator />
                                            <flux:menu.item icon="trash" variant="danger" wire:click="deleteBudget({{ $budget->id }})" wire:confirm="¿Seguro que deseas eliminar este presupuesto?">Eliminar</flux:menu.item>
                                        @endif
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                No se encontraron presupuestos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($budgets->hasPages())
                <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-800 sm:px-6">
                    {{ $budgets->links() }}
                </div>
            @endif
        </div>
    </div>
    
    @livewire('budget.create')
</div>
