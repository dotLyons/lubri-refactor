<flux:modal name="edit-product" class="md:w-[800px] !p-0 overflow-hidden bg-white dark:bg-zinc-900 rounded-xl">
    <div
        class="border-b border-zinc-200 dark:border-zinc-800 px-6 py-4 flex items-center justify-between bg-zinc-50/50 dark:bg-zinc-800/50">
        <div>
            <h3 class="text-lg font-semibold leading-6 text-zinc-900 dark:text-white">Editar Producto</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Modifica los detalles del producto.</p>
        </div>
    </div>

    <div class="px-6 py-6 max-h-[calc(100vh-200px)] overflow-y-auto">
        <form wire:submit="update" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div class="md:col-span-2">
                    <label for="edit_product_name"
                        class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Nombre del producto <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-zinc-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 2c-1.716 0-3.408.106-5.07.31C3.806 2.45 3 3.414 3 4.517V17.25a.75.75 0 001.075.676L10 15.082l5.925 2.844A.75.75 0 0017 17.25V4.517c0-1.103-.806-2.068-1.93-2.207A41.403 41.403 0 0010 2z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" wire:model="product_name" id="edit_product_name"
                            class="block w-full rounded-lg border-0 py-2.5 pl-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:placeholder-zinc-500 dark:focus:ring-indigo-500"
                            placeholder="Ej. Filtro de Aceite XJ-200">
                    </div>
                    @error('product_name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Code -->
                <div>
                    <label for="edit_product_code"
                        class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Código Interno <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                            </svg>
                        </div>
                        <input type="text" wire:model="product_code" id="edit_product_code"
                            class="block w-full rounded-lg border-0 py-2.5 pl-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:placeholder-zinc-500 dark:focus:ring-indigo-500"
                            placeholder="Ej. FA-001">
                    </div>
                    @error('product_code') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bar Code -->
                <div>
                    <label for="edit_bar_code"
                        class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Código de Barras <span class="text-xs font-normal text-zinc-500">(Opcional)</span>
                    </label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 6.75h.75v2.25h-.75v-2.25zM6.75 16.5h.75v2.25h-.75v-2.25zM16.5 6.75h.75v2.25h-.75v-2.25zM13.5 13.5h.75v2.25h-.75v-2.25zM13.5 19.5h.75v2.25h-.75v-2.25zM19.5 13.5h.75v2.25h-.75v-2.25zM19.5 19.5h.75v2.25h-.75v-2.25zM16.5 16.5h.75v2.25h-.75v-2.25z" />
                            </svg>
                        </div>
                        <input type="text" wire:model="bar_code" id="edit_bar_code"
                            class="block w-full rounded-lg border-0 py-2.5 pl-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:placeholder-zinc-500 dark:focus:ring-indigo-500"
                            placeholder="Escanee o ingrese código">
                    </div>
                    @error('bar_code') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="edit_category_id"
                        class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Categoría <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2">
                        <select wire:model="category_id" id="edit_category_id"
                            class="block w-full rounded-lg border-0 py-2.5 pl-3 pr-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:focus:ring-indigo-500">
                            <option value="">Seleccionar Categoría...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subcategory -->
                <div>
                    <label for="edit_subcategory_id"
                        class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Subcategoría <span class="text-xs font-normal text-zinc-500">(Opcional)</span>
                    </label>
                    <div class="mt-2">
                        <select wire:model="subcategory_id" id="edit_subcategory_id"
                            class="block w-full rounded-lg border-0 py-2.5 pl-3 pr-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:focus:ring-indigo-500">
                            <option value="">Seleccionar Subcategoría...</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->subcategory_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('subcategory_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cost Price -->
                <div>
                    <label for="edit_cost_price"
                        class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Precio Costo <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-zinc-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" wire:model.live="cost_price" id="edit_cost_price"
                            class="block w-full rounded-lg border-0 py-2.5 pl-7 text-zinc-900 ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:placeholder-zinc-500 dark:focus:ring-indigo-500"
                            placeholder="0.00">
                    </div>
                    @error('cost_price') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sale Price -->
                <div>
                    <label for="edit_sale_price"
                        class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Precio Venta <span class="text-xs text-zinc-500 font-normal">(Auto +60%)</span>
                    </label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-zinc-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" wire:model="sale_price" id="edit_sale_price"
                            class="block w-full rounded-lg border-0 py-2.5 pl-7 text-zinc-900 ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:placeholder-zinc-500 dark:focus:ring-indigo-500"
                            placeholder="0.00">
                    </div>
                    @error('sale_price') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="edit_status"
                        class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Estado
                    </label>
                    <div class="mt-2">
                        <select id="edit_status" wire:model="status"
                            class="block w-full rounded-lg border-0 py-2.5 pl-3 pr-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:focus:ring-indigo-500">
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>
                    @error('status') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="edit_description"
                    class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                    Descripción <span class="text-xs font-normal text-zinc-500">(Opcional)</span>
                </label>
                <div class="mt-2">
                    <textarea id="edit_description" wire:model="description" rows="3"
                        class="block w-full rounded-lg border-0 py-2.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:placeholder-zinc-500 dark:focus:ring-indigo-500"
                        placeholder="Descripción detallada del producto..."></textarea>
                </div>
                @error('description') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-x-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <flux:modal.close>
                    <button type="button"
                        class="rounded-lg bg-white dark:bg-zinc-800 px-3.5 py-2.5 text-sm font-semibold text-zinc-900 dark:text-zinc-300 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        Cancelar
                    </button>
                </flux:modal.close>
                <button type="submit"
                    class="rounded-lg bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</flux:modal>