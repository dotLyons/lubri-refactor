<flux:modal name="manual-movement" class="md:w-[500px] !p-0 overflow-hidden bg-white dark:bg-zinc-900 rounded-xl">
    <div class="border-b border-zinc-200 dark:border-zinc-800 px-6 py-4 flex items-center justify-between bg-zinc-50/50 dark:bg-zinc-800/50">
        <div>
            <h3 class="text-lg font-semibold leading-6 text-zinc-900 dark:text-white">Movimiento Manual</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Registre un ingreso o extracción manual (Requiere PIN).</p>
        </div>
    </div>

    <div class="px-6 py-6 max-h-[calc(100vh-200px)] overflow-y-auto">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Tipo de Operación
                    </label>
                    <div class="mt-2 text-zinc-900 dark:text-white">
                        <flux:select wire:model="type" id="type">
                            <flux:select.option value="income">Ingreso (+)</flux:select.option>
                            <flux:select.option value="expense">Extracción (-)</flux:select.option>
                        </flux:select>
                    </div>
                </div>

                <!-- Monto -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Monto <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2 text-zinc-900 dark:text-white relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-zinc-500 sm:text-sm">$</span>
                        </div>
                        <flux:input type="number" step="0.01" min="0" wire:model="amount" class="!pl-7" placeholder="0.00" />
                    </div>
                    @error('amount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Descripción -->
            <div>
                <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                    Motivo / Descripción <span class="text-red-500">*</span>
                </label>
                <div class="mt-2 text-zinc-900 dark:text-white">
                    <flux:input wire:model="description" placeholder="Ej. Pago a proveedor, Retiro de socio..." />
                </div>
                @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- PIN -->
            <div>
                <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                    PIN de Autorización <span class="text-red-500">*</span>
                </label>
                <div class="mt-2 text-zinc-900 dark:text-white relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <flux:icon.lock-closed class="size-4 text-zinc-400" />
                    </div>
                    <flux:input type="password" wire:model="passcode" class="!pl-9 tracking-[0.2em]" placeholder="********" />
                </div>
                @error('passcode') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-x-3 pt-6 border-t border-zinc-100 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="subtle">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Confirmar</flux:button>
            </div>
        </form>
    </div>
</flux:modal>
