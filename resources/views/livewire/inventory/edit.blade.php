<flux:modal name="edit-category" class="md:w-[500px] !p-0 overflow-hidden bg-white dark:bg-zinc-900 rounded-xl">
    <div
        class="border-b border-zinc-200 dark:border-zinc-800 px-6 py-4 flex items-center justify-between bg-zinc-50/50 dark:bg-zinc-800/50">
        <div>
            <h3 class="text-lg font-semibold leading-6 text-zinc-900 dark:text-white">Editar Categoría</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Modifique los detalles de la categoría existente.
            </p>
        </div>
    </div>

    <div class="px-6 py-6">
        <form wire:submit="update" class="space-y-5">
            {{-- Name --}}
            <div>
                <label for="edit_category_name"
                    class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <div class="mt-2 relative rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-zinc-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 2c-1.716 0-3.408.106-5.07.31C3.806 2.45 3 3.414 3 4.517V17.25a.75.75 0 001.075.676L10 15.082l5.925 2.844A.75.75 0 0017 17.25V4.517c0-1.103-.806-2.068-1.93-2.207A41.403 41.403 0 0010 2z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" wire:model="category_name" id="edit_category_name"
                        class="block w-full rounded-lg border-0 py-2.5 pl-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:placeholder-zinc-500 dark:focus:ring-indigo-500"
                        placeholder="Ej. Lubricantes">
                </div>
                @error('category_name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="edit_description"
                    class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                    Descripción <span class="text-xs font-normal text-zinc-500">(Opcional)</span>
                </label>
                <div class="mt-2">
                    <textarea id="edit_description" wire:model="description" rows="3"
                        class="block w-full rounded-lg border-0 py-2.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:placeholder-zinc-500 dark:focus:ring-indigo-500"
                        placeholder="Breve descripción de los productos en esta categoría..."></textarea>
                </div>
                @error('description') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="edit_status" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
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

            {{-- Footer --}}
            <div class="mt-6 flex items-center justify-end gap-x-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
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