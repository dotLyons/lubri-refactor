<div>
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Clientes</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Directorio de clientes y sus vehículos registrados.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <flux:modal.trigger name="create-customer">
                    <flux:button variant="primary" icon="plus">
                        Nuevo Cliente
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-4 mb-4">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar por DNI, Nombre o Apellido..." class="w-full sm:w-96" />
        </div>

        <div class="mt-4 bg-white dark:bg-zinc-900 shadow-sm ring-1 ring-zinc-300 dark:ring-zinc-800 sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-zinc-900 dark:text-white sm:pl-6">Cliente</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">DNI</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">Celular</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-zinc-900 dark:text-white">Vehículos</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                    @forelse($customers as $customer)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                <div class="font-medium text-zinc-900 dark:text-white">{{ $customer->last_name }}, {{ $customer->first_name }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $customer->dni }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $customer->primary_phone }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                <div class="flex items-center gap-1">
                                    <flux:badge size="sm" color="zinc">{{ $customer->vehicles->count() }}</flux:badge>
                                </div>
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <flux:dropdown align="end">
                                    <flux:button variant="ghost" size="sm" icon="ellipsis-vertical" class="!px-2" />
                                    <flux:menu>
                                        <flux:menu.item icon="pencil-square" wire:click="$dispatch('edit-customer', { id: {{ $customer->id }} })">Editar</flux:menu.item>
                                        <flux:menu.item icon="trash" variant="danger" wire:click="deleteCustomer({{ $customer->id }})" wire:confirm="¿Seguro que deseas eliminar el cliente y sus vehículos?">Eliminar</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                No se encontraron clientes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($customers->hasPages())
                <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-800 sm:px-6">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
    
    @livewire('customers.create')
    @livewire('customers.edit')
</div>
