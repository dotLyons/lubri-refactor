<div class="space-y-6">
    <div>
        <h3 class="text-lg font-semibold leading-6 text-zinc-900 dark:text-white">Generar Reporte de Stock</h3>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Seleccione los filtros para generar el reporte en PDF.
        </p>
    </div>

    <div class="space-y-4">
        <!-- Stock Status Filter -->
        <div>
            <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200 mb-2">Estado de
                Stock</label>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <label
                    class="relative flex cursor-pointer rounded-lg border p-3 shadow-sm focus:outline-none {{ $stockStatus === 'all' ? 'border-indigo-600 ring-1 ring-indigo-600 bg-indigo-50 dark:bg-indigo-500/10' : 'border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800' }}">
                    <input type="radio" wire:model.live="stockStatus" value="all" class="sr-only">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span
                                class="block text-sm font-medium {{ $stockStatus === 'all' ? 'text-indigo-900 dark:text-indigo-200' : 'text-zinc-900 dark:text-zinc-300' }}">Todos</span>
                        </span>
                    </span>
                </label>

                <label
                    class="relative flex cursor-pointer rounded-lg border p-3 shadow-sm focus:outline-none {{ $stockStatus === 'with_stock' ? 'border-indigo-600 ring-1 ring-indigo-600 bg-indigo-50 dark:bg-indigo-500/10' : 'border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800' }}">
                    <input type="radio" wire:model.live="stockStatus" value="with_stock" class="sr-only">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span
                                class="block text-sm font-medium {{ $stockStatus === 'with_stock' ? 'text-indigo-900 dark:text-indigo-200' : 'text-zinc-900 dark:text-zinc-300' }}">Con
                                Stock</span>
                        </span>
                    </span>
                </label>

                <label
                    class="relative flex cursor-pointer rounded-lg border p-3 shadow-sm focus:outline-none {{ $stockStatus === 'no_stock' ? 'border-indigo-600 ring-1 ring-indigo-600 bg-indigo-50 dark:bg-indigo-500/10' : 'border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800' }}">
                    <input type="radio" wire:model.live="stockStatus" value="no_stock" class="sr-only">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span
                                class="block text-sm font-medium {{ $stockStatus === 'no_stock' ? 'text-indigo-900 dark:text-indigo-200' : 'text-zinc-900 dark:text-zinc-300' }}">Sin
                                Stock</span>
                        </span>
                    </span>
                </label>
            </div>
        </div>

        <!-- Category Filter -->
        <div>
            <label for="report-category"
                class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">Categoría
                (Opcional)</label>
            <select id="report-category" wire:model.live="selectedCategory"
                class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Subcategory Filter -->
        <div>
            <label for="report-subcategory"
                class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">Subcategoría
                (Opcional)</label>
            <select id="report-subcategory" wire:model.live="selectedSubcategory"
                class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700">
                <option value="">Todas las subcategorías</option>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}">{{ $subcategory->subcategory_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="flex items-center justify-end gap-x-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
        <flux:modal.close>
            <button type="button"
                class="rounded-lg bg-white dark:bg-zinc-800 px-3.5 py-2.5 text-sm font-semibold text-zinc-900 dark:text-zinc-300 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                Cancelar
            </button>
        </flux:modal.close>
        <button wire:click="generate"
            class="rounded-lg bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Generar PDF
        </button>
    </div>
</div>