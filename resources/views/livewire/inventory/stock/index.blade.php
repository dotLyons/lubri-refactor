<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Inventario (Stock)</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Consulta el stock actual y movimientos de tus
                productos.</p>
        </div>
        <div>
            <flux:modal.trigger name="stock-report-modal">
                <button
                    class="rounded-lg bg-white dark:bg-zinc-800 px-3 py-2 text-sm font-semibold text-zinc-900 dark:text-zinc-300 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors flex items-center">
                    <svg class="h-5 w-5 mr-2 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Descargar Stock PDF
                </button>
            </flux:modal.trigger>
        </div>
    </div>

    {{-- Controls --}}
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="w-full sm:w-1/4">
            <select wire:model.live="searchField"
                class="block w-full py-2 pl-3 pr-10 text-sm border-zinc-300 rounded-lg leading-5 bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-100 dark:focus:ring-indigo-500 shadow-sm">
                <option value="product_name">Nombre</option>
                <option value="product_code">Código</option>
            </select>
        </div>

        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-zinc-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="block w-full py-2 pl-10 pr-3 text-sm border border-zinc-300 rounded-lg leading-5 bg-white text-zinc-900 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-100 dark:placeholder-zinc-400 dark:focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out shadow-sm"
                placeholder="Buscar productos...">
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-4 mb-6">
        @forelse ($products as $product)
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white">{{ $product->product_name }}</h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $product->product_code }}</p>
                    </div>
                    @if(($product->stock->quantity ?? 0) > 10)
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">Disponible</span>
                    @elseif(($product->stock->quantity ?? 0) > 0)
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-400">Bajo
                            Stock</span>
                    @else
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400">Sin
                            Stock</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400 block">Stock Actual</span>
                        <span
                            class="text-xl font-bold {{ ($product->stock->quantity ?? 0) <= 0 ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-white' }}">
                            {{ $product->stock->quantity ?? 0 }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400 block">Barra</span>
                        <span
                            class="text-sm font-medium text-zinc-900 dark:text-zinc-200">{{ $product->bar_code ?? '-' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    <flux:modal.trigger name="movements-modal">
                        <button wire:click="viewMovements({{ $product->id }})"
                            class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Historial
                        </button>
                    </flux:modal.trigger>

                    <flux:modal.trigger name="stock-adjustment">
                        <button
                            wire:click="$dispatchTo('inventory.stock.adjustment', 'open-adjustment', { productId: {{ $product->id }} })"
                            class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-orange-700 bg-orange-50 hover:bg-orange-100 dark:bg-orange-500/10 dark:text-orange-400 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.703.127 1.243.729 1.215 2.483 1.215 2.483m-17 0h17" />
                            </svg>
                            Corregir
                        </button>
                    </flux:modal.trigger>
                </div>
            </div>
        @empty
            <div class="text-center py-8 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700">
                <p class="text-sm text-zinc-500">No se encontraron productos</p>
            </div>
        @endforelse

        @if($products->hasPages())
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- Table (Desktop) --}}
    <div
        class="hidden md:block overflow-hidden bg-white border border-zinc-200 rounded-xl shadow-sm dark:bg-zinc-900 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3.5 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            Producto
                        </th>
                        <th scope="col"
                            class="px-6 py-3.5 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            Código / Barra
                        </th>
                        <th scope="col"
                            class="px-6 py-3.5 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            Stock Actual
                        </th>
                        <th scope="col"
                            class="px-6 py-3.5 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            Estado
                        </th>
                        <th scope="col" class="relative px-6 py-3.5">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                    @forelse ($products as $product)
                        <tr
                            class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors duration-150 ease-in-out {{ ($product->stock->quantity ?? 0) == 0 ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                    {{ $product->product_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-zinc-900 dark:text-zinc-200">{{ $product->product_code }}</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400" title="Código de barras">
                                    {{ $product->bar_code ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="text-sm font-bold {{ ($product->stock->quantity ?? 0) <= 0 ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-white' }}">
                                    {{ $product->stock->quantity ?? 0 }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(($product->stock->quantity ?? 0) > 10)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        Disponible
                                    </span>
                                @elseif(($product->stock->quantity ?? 0) > 0)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-400">
                                        Bajo Stock
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400">
                                        Sin Stock
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <flux:modal.trigger name="movements-modal">
                                    <button wire:click="viewMovements({{ $product->id }})"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500/10 dark:text-indigo-400 dark:hover:bg-indigo-500/20 transition-colors"
                                        title="Ver Trazabilidad">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Trazabilidad
                                    </button>
                                </flux:modal.trigger>

                                <flux:modal.trigger name="stock-adjustment">
                                    <button
                                        wire:click="$dispatchTo('inventory.stock.adjustment', 'open-adjustment', { productId: {{ $product->id }} })"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-orange-700 bg-orange-100 hover:bg-orange-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 dark:bg-orange-500/10 dark:text-orange-400 dark:hover:bg-orange-500/20 transition-colors"
                                        title="Corregir Stock">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.703.127 1.243.729 1.215 2.483 1.215 2.483m-17 0h17" />
                                        </svg>
                                        Corregir
                                    </button>
                                </flux:modal.trigger>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-zinc-300 dark:text-zinc-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">No se encontraron
                                    productos</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="border-t border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 sm:px-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- Movements Modal --}}
    <flux:modal name="movements-modal" class="md:w-[1100px] !p-0 overflow-hidden bg-white dark:bg-zinc-900 rounded-xl">
        <livewire:inventory.stock.movements />
    </flux:modal>

    {{-- Adjustment Modal --}}
    <flux:modal name="stock-adjustment" class="md:w-[600px] bg-white dark:bg-zinc-900 rounded-xl p-6">
        <livewire:inventory.stock.adjustment />
    </flux:modal>

    {{-- Report Modal --}}
    <flux:modal name="stock-report-modal" class="md:w-[600px] bg-white dark:bg-zinc-900 rounded-xl p-6">
        <livewire:inventory.stock.report />
    </flux:modal>
</div>