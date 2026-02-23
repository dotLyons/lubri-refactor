<flux:modal name="close-register" class="md:w-[500px] !p-0 overflow-hidden bg-white dark:bg-zinc-900 rounded-xl">
    <div class="border-b border-zinc-200 dark:border-zinc-800 px-6 py-4 flex items-center justify-between bg-red-50/50 dark:bg-red-900/10">
        <div>
            <h3 class="text-lg font-semibold leading-6 text-red-800 dark:text-red-400">Cerrar Caja</h3>
            <p class="mt-1 text-sm text-red-600 dark:text-red-500/80">Declara el dinero físico encontrado para cerrar sesión.</p>
        </div>
    </div>

    <div class="px-6 py-6">
        <form wire:submit="save" class="space-y-6">
            <!-- Actual Amount -->
            <div>
                <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                    Dinero Físico en Caja <span class="text-red-500">*</span>
                </label>
                <div class="mt-2 text-zinc-900 dark:text-white relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-zinc-500 sm:text-sm">$</span>
                    </div>
                    <flux:input type="number" step="0.01" min="0" wire:model="closing_actual_amount" id="closing_actual_amount" class="!pl-7" placeholder="0.00" autofocus />
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-x-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="subtle">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" icon="lock-closed">Finalizar Turno</flux:button>
            </div>
        </form>
    </div>
</flux:modal>
