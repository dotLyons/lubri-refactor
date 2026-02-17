<div class="space-y-6">
    <div>
        <h3 class="text-lg font-semibold leading-6 text-zinc-900 dark:text-white">Corrección de Stock</h3>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
            Producto: <span class="font-medium text-zinc-900 dark:text-zinc-200">{{ $productName }}</span>
            <span class="mx-2 text-zinc-300">|</span>
            Stock Actual: <span
                class="font-medium {{ $currentStock <= 0 ? 'text-red-600' : 'text-zinc-900 dark:text-zinc-200' }}">{{ $currentStock }}</span>
        </p>
    </div>

    <form wire:submit="save" class="space-y-5">
        <!-- Type Selection -->
        <div>
            <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200 mb-2">
                Tipo de Movimiento
            </label>
            <div class="grid grid-cols-2 gap-4">
                <label
                    class="relative flex cursor-pointer rounded-lg border p-4 shadow-sm focus:outline-none {{ $type === 'in' ? 'border-indigo-600 ring-2 ring-indigo-600 bg-indigo-50 dark:bg-indigo-500/10' : 'border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800' }}">
                    <input type="radio" wire:model.live="type" value="in" class="sr-only">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span
                                class="block text-sm font-medium {{ $type === 'in' ? 'text-indigo-900 dark:text-indigo-200' : 'text-zinc-900 dark:text-zinc-300' }}">Entrada</span>
                            <span
                                class="mt-1 flex items-center text-sm {{ $type === 'in' ? 'text-indigo-700 dark:text-indigo-300' : 'text-zinc-500 dark:text-zinc-400' }}">Agregar
                                stock</span>
                        </span>
                    </span>
                    <svg class="h-5 w-5 {{ $type === 'in' ? 'text-indigo-600' : 'text-zinc-400' }}" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                    </svg>
                </label>

                <label
                    class="relative flex cursor-pointer rounded-lg border p-4 shadow-sm focus:outline-none {{ $type === 'out' ? 'border-indigo-600 ring-2 ring-indigo-600 bg-indigo-50 dark:bg-indigo-500/10' : 'border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800' }}">
                    <input type="radio" wire:model.live="type" value="out" class="sr-only">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span
                                class="block text-sm font-medium {{ $type === 'out' ? 'text-indigo-900 dark:text-indigo-200' : 'text-zinc-900 dark:text-zinc-300' }}">Salida</span>
                            <span
                                class="mt-1 flex items-center text-sm {{ $type === 'out' ? 'text-indigo-700 dark:text-indigo-300' : 'text-zinc-500 dark:text-zinc-400' }}">Descontar
                                stock</span>
                        </span>
                    </span>
                    <svg class="h-5 w-5 {{ $type === 'out' ? 'text-indigo-600' : 'text-zinc-400' }}" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                    </svg>
                </label>
            </div>
        </div>

        <!-- Quantity -->
        <div>
            <label for="quantity" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                Cantidad
                <span class="text-red-500">*</span>
            </label>
            <div class="mt-2 relative rounded-md shadow-sm">
                <input type="number" wire:model="quantity" id="quantity" min="1" step="1"
                    class="block w-full rounded-lg border-0 py-2.5 pl-3 pr-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:placeholder-zinc-500 dark:focus:ring-indigo-500"
                    placeholder="Ej. 5">
            </div>
            @error('quantity') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <!-- Reason -->
        <div>
            <label for="reason" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                Motivo de la corrección
                <span class="text-red-500">*</span>
            </label>
            <div class="mt-2">
                <textarea id="reason" wire:model="reason" rows="3"
                    class="block w-full rounded-lg border-0 py-2.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700 dark:placeholder-zinc-500 dark:focus:ring-indigo-500"
                    placeholder="Especifique la razón del ajuste (ej. merma, conteo físico, devolución)..."></textarea>
            </div>
            @error('reason') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

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
                Confirmar Ajuste
            </button>
        </div>
    </form>
</div>