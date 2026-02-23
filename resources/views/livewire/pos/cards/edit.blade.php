<flux:modal name="edit-card" class="md:w-[800px] !p-0 overflow-hidden bg-white dark:bg-zinc-900 rounded-xl">
    <div class="border-b border-zinc-200 dark:border-zinc-800 px-6 py-4 flex items-center justify-between bg-zinc-50/50 dark:bg-zinc-800/50">
        <div>
            <h3 class="text-lg font-semibold leading-6 text-zinc-900 dark:text-white">Editar Tarjeta</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Modifique la información de la tarjeta y sus planes asociados.</p>
        </div>
    </div>

    <div class="px-6 py-6 max-h-[calc(100vh-200px)] overflow-y-auto">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre de la Tarjeta -->
                <div class="md:col-span-1">
                    <label for="edit_name" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Nombre de la Tarjeta <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2 text-zinc-900 dark:text-white">
                        <flux:input wire:model="name" id="edit_name" placeholder="Ej. Visa Santander" />
                    </div>
                </div>

                <!-- Tipo -->
                <div class="md:col-span-1">
                    <label for="edit_type" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-200">
                        Tipo de Tarjeta
                    </label>
                    <div class="mt-2 text-zinc-900 dark:text-white">
                        <flux:select wire:model.live="type" id="edit_type">
                            <flux:select.option value="credit">Crédito</flux:select.option>
                            <flux:select.option value="debit">Débito</flux:select.option>
                        </flux:select>
                    </div>
                </div>
            </div>

            <!-- Planes de Pago -->
            <div class="mt-6 border-t border-zinc-200 dark:border-zinc-800 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-md font-medium text-zinc-900 dark:text-white">
                        Planes de Pago Asociados
                    </h4>
                    @if($type === 'credit')
                        <flux:button size="sm" variant="subtle" wire:click="addPlan" icon="plus">
                            Agregar Plan
                        </flux:button>
                    @endif
                </div>

                <div class="space-y-4">
                    @foreach($plans as $index => $plan)
                        <div class="flex flex-col md:flex-row items-end gap-3 p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50 dark:bg-zinc-800/30 relative">
                            
                            <div class="flex-1 w-full text-zinc-900 dark:text-white">
                                <label class="block text-xs font-medium mb-1">Nombre del Plan</label>
                                <flux:input wire:model="plans.{{ $index }}.name" placeholder="Ej. 3 Cuotas Fijas" />
                                @error("plans.{$index}.name") <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="w-full md:w-24 text-zinc-900 dark:text-white">
                                <label class="block text-xs font-medium mb-1">Cuotas</label>
                                <flux:input type="number" min="1" wire:model="plans.{{ $index }}.installments" />
                                @error("plans.{$index}.installments") <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="w-full md:w-32 text-zinc-900 dark:text-white">
                                <label class="block text-xs font-medium mb-1">% Recargo</label>
                                <flux:input type="number" step="0.01" min="0" wire:model="plans.{{ $index }}.surcharge_percentage" />
                                @error("plans.{$index}.surcharge_percentage") <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="w-full md:w-auto h-10 flex items-center shrink-0">
                                <flux:checkbox wire:model="plans.{{ $index }}.is_promotion" label="Promoción" />
                            </div>

                            @if($type === 'credit' && count($plans) > 1)
                                <div class="w-full md:w-auto mt-4 md:mt-0 shrink-0">
                                    <flux:button variant="danger" icon="trash" class="w-full md:w-auto" wire:click="removePlan({{ $index }})" />
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-x-3 pt-6 mt-6 border-t border-zinc-100 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="subtle">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" icon="check">Actualizar Tarjeta</flux:button>
            </div>
        </form>
    </div>
</flux:modal>
