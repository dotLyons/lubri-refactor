<div>
    <flux:modal name="create-budget" class="md:w-3/4 max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            <div class="flex items-center p-6 border-b border-zinc-200 dark:border-zinc-800">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Nuevo Presupuesto</h3>
            </div>

            <div class="px-6 py-4 space-y-6 max-h-[70vh] overflow-y-auto">
                {{-- Client & Vehicle --}}
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider border-b border-zinc-200 dark:border-zinc-800 pb-2">1. Identificar Cliente y Vehículo</h4>
                    
                    <flux:select wire:model.live="customer_id" label="Cliente" required searchable>
                        <option value="">Escriba Nombre, Apellido o DNI...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->last_name }}, {{ $customer->first_name }} (DNI: {{ $customer->dni }})</option>
                        @endforeach
                    </flux:select>
                    
                    @if(count($vehicles) > 0)
                        <div class="mt-4 p-4 bg-zinc-50 dark:bg-zinc-800/30 rounded-xl border border-zinc-200 dark:border-zinc-700">
                            <flux:radio.group wire:model="vehicle_id" label="¿Para qué vehículo es el presupuesto?" class="flex flex-col gap-2" required>
                                @foreach($vehicles as $vehicle)
                                    <flux:radio 
                                        value="{{ $vehicle->id }}" 
                                        label="{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }}) - {{ $vehicle->license_plate }}" 
                                    />
                                @endforeach
                            </flux:radio.group>
                        </div>
                    @elseif($customer_id)
                        <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 text-sm rounded-xl border border-red-200 dark:border-red-900/30">
                            Este cliente no tiene ningún vehículo registrado. Registra un vehículo primero desde el menú de Clientes.
                        </div>
                    @endif
                </div>

                {{-- Notes --}}
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider border-b border-zinc-200 dark:border-zinc-800 pb-2">2. Observaciones (Opcional)</h4>
                    
                    <flux:textarea wire:model="notes" label="Notas del Presupuesto" placeholder="Agregue cualquier observación relevante..." rows="3" />
                </div>

            </div>

            <div class="p-6 pt-2 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-3 rounded-b-xl">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" icon="document-plus">Crear Presupuesto</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
