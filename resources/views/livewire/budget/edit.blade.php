<div>
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <div class="sm:flex-auto">
                <div class="flex items-center gap-3">
                    <flux:button variant="ghost" icon="arrow-left" href="{{ route('budgets.index') }}" wire:navigate />
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Presupuesto #{{ $budget->id }}</h1>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            Cliente: {{ $budget->customer->last_name }}, {{ $budget->customer->first_name }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 sm:flex-none flex gap-2">
                @if($budget->status->value === 'open')
                    <button type="button" wire:click="openCloseModal" class="inline-flex items-center gap-2 rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Cerrar Presupuesto
                    </button>
                @else
                    <flux:badge size="lg" color="emerald">Cerrado</flux:badge>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Info Column --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Customer & Vehicle Info --}}
                <div class="bg-white dark:bg-zinc-900 shadow-sm ring-1 ring-zinc-300 dark:ring-zinc-800 rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider mb-4">Vehículo</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">Marca/Modelo:</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $budget->vehicle->brand }} {{ $budget->vehicle->model }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">Año:</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $budget->vehicle->year }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">Patente:</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white uppercase">{{ $budget->vehicle->license_plate }}</span>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="bg-white dark:bg-zinc-900 shadow-sm ring-1 ring-zinc-300 dark:ring-zinc-800 rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider mb-4">Observaciones</h3>
                    <flux:textarea wire:model="notes" placeholder="Agregue notas..." rows="4" />
                    <div class="mt-3">
                        <flux:button wire:click="saveNotes" size="sm" variant="filled">Guardar Notas</flux:button>
                    </div>
                </div>
            </div>

            {{-- Items Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Budget Items --}}
                <div class="bg-white dark:bg-zinc-900 shadow-sm ring-1 ring-zinc-300 dark:ring-zinc-800 rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider">Items del Presupuesto</h3>
                        <span class="text-lg font-bold text-zinc-900 dark:text-white">Total: ${{ number_format($totalAmount, 2) }}</span>
                    </div>
                    
                    @if(count($items) > 0)
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                            <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Producto</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-zinc-500 uppercase">Cantidad</th>
                                    <th class="px-3 py-3 text-right text-xs font-medium text-zinc-500 uppercase">Precio Unit.</th>
                                    <th class="px-3 py-3 text-right text-xs font-medium text-zinc-500 uppercase">Subtotal</th>
                                    @if($budget->status->value === 'open')
                                        <th class="relative px-3 py-3">
                                            <span class="sr-only">Acciones</span>
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                @foreach($items as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="font-medium text-zinc-900 dark:text-white">{{ $item['name'] }}</div>
                                            <div class="text-xs text-zinc-500">{{ $item['category'] }}</div>
                                        </td>
                                        <td class="px-3 py-4 text-center">
                                            @if($budget->status->value === 'open')
                                                <div class="flex items-center justify-center gap-2">
                                                    <flux:button size="xs" variant="ghost" wire:click="updateItemQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})" icon="minus" class="!px-1" />
                                                    <span class="w-12 text-center font-medium">{{ $item['quantity'] }}</span>
                                                    <flux:button size="xs" variant="ghost" wire:click="updateItemQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})" icon="plus" class="!px-1" />
                                                </div>
                                            @else
                                                <span class="font-medium">{{ $item['quantity'] }}</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 text-right text-sm text-zinc-900 dark:text-white">
                                            ${{ number_format($item['unit_price'], 2) }}
                                        </td>
                                        <td class="px-3 py-4 text-right text-sm font-medium text-zinc-900 dark:text-white">
                                            ${{ number_format($item['subtotal'], 2) }}
                                        </td>
                                        @if($budget->status->value === 'open')
                                            <td class="px-3 py-4 text-right">
                                                <flux:button size="xs" variant="ghost" color="red" wire:click="removeItem({{ $item['id'] }})" icon="trash" class="!px-1" />
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-zinc-50 dark:bg-zinc-800/50">
                                <tr>
                                    <td colspan="{{ $budget->status->value === 'open' ? 4 : 3 }}" class="px-6 py-4 text-right text-sm font-bold text-zinc-900 dark:text-white">
                                        Total Presupuesto
                                    </td>
                                    <td class="px-3 py-4 text-right text-sm font-bold text-zinc-900 dark:text-white">
                                        ${{ number_format($totalAmount, 2) }}
                                    </td>
                                    @if($budget->status->value === 'open')
                                        <td></td>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div class="px-6 py-8 text-center text-sm text-zinc-500">
                            No hay productos agregados al presupuesto
                        </div>
                    @endif
                </div>

                {{-- Add Product --}}
                @if($budget->status->value === 'open')
                    <div class="bg-white dark:bg-zinc-900 shadow-sm ring-1 ring-zinc-300 dark:ring-zinc-800 rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider mb-4">Agregar Productos</h3>
                        
                        <flux:input wire:model.live.debounce.300ms="productSearch" icon="magnifying-glass" placeholder="Buscar por nombre, código o barras..." class="mb-4" />

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 uppercase">Producto</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-zinc-500 uppercase">Stock</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-zinc-500 uppercase">Precio</th>
                                        <th class="px-4 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                    @forelse($modalProducts as $product)
                                        <tr>
                                            <td class="px-4 py-2 text-sm">
                                                <div class="font-medium text-zinc-900 dark:text-white">{{ $product->product_name }}</div>
                                                <div class="text-xs text-zinc-500">{{ $product->category?->category_name ?? 'Sin categoría' }}</div>
                                            </td>
                                            <td class="px-4 py-2 text-right text-sm">
                                                <span class="{{ ($product->stock?->quantity ?? 0) > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                                    {{ $product->stock?->quantity ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-right text-sm text-zinc-900 dark:text-white">
                                                ${{ number_format($product->sale_price, 2) }}
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                @if(($product->stock?->quantity ?? 0) > 0)
                                                    <flux:button size="xs" wire:click="addItem({{ $product->id }})" icon="plus" variant="primary">Agregar</flux:button>
                                                @else
                                                    <flux:badge size="xs" color="red">Sin stock</flux:badge>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-4 text-center text-sm text-zinc-500">
                                                No se encontraron productos
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($modalProducts->hasPages())
                            <div class="mt-4">
                                {{ $modalProducts->links() }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Close Budget Modal --}}
    <flux:modal wire:model="showCloseModal" class="md:w-3/4 max-w-2xl">
        <form wire:submit="closeBudgetAndCreateWorkOrder" class="space-y-6">
            <div class="flex items-center p-6 border-b border-zinc-200 dark:border-zinc-800">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Cerrar Presupuesto y Crear Orden de Trabajo</h3>
            </div>

            <div class="px-6 py-4 space-y-6">
                <div class="p-4 bg-amber-50 dark:bg-amber-900/10 text-amber-700 dark:text-amber-400 text-sm rounded-lg border border-amber-200 dark:border-amber-900/30">
                    <strong>Nota:</strong> Al cerrar el presupuesto se creará una orden de trabajo y se descontará el stock de los productos utilizados.
                </div>

                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider border-b border-zinc-200 dark:border-zinc-800 pb-2">1. Seleccionar Sector</h4>
                    <flux:select wire:model="destination" label="Destino del Turno" required>
                        <option value="">Seleccione a dónde va...</option>
                        @foreach($destinations as $dest)
                            <option value="{{ $dest->value }}">{{ $dest->label() }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider border-b border-zinc-200 dark:border-zinc-800 pb-2">2. Agendamiento</h4>
                    <flux:input type="datetime-local" wire:model="scheduled_at" label="Fecha y Hora Programada" required />
                </div>

                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider border-b border-zinc-200 dark:border-zinc-800 pb-2">3. Resumen del Presupuesto</h4>
                    <div class="bg-zinc-50 dark:bg-zinc-800/30 rounded-lg p-4">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-zinc-500 uppercase">Producto</th>
                                    <th class="text-center text-xs font-medium text-zinc-500 uppercase">Cantidad</th>
                                    <th class="text-right text-xs font-medium text-zinc-500 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @foreach($items as $item)
                                    <tr>
                                        <td class="py-2 text-sm">{{ $item['name'] }}</td>
                                        <td class="py-2 text-sm text-center">{{ $item['quantity'] }}</td>
                                        <td class="py-2 text-sm text-right">${{ number_format($item['subtotal'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="py-2 text-sm font-bold text-right">Total:</td>
                                    <td class="py-2 text-sm font-bold text-right">${{ number_format($totalAmount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="p-6 pt-2 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-3 rounded-b-xl">
                <flux:button type="button" variant="ghost" wire:click="$set('showCloseModal', false)">Cancelar</flux:button>
                <flux:button type="submit" variant="primary" icon="check-circle">Cerrar Presupuesto y Crear OT</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
