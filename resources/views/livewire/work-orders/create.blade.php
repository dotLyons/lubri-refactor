<div>
    <flux:modal name="create-work-order" class="md:w-3/4 max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            <div class="flex items-center p-6 border-b border-zinc-200 dark:border-zinc-800">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Asignar Nuevo Turno (Orden de Trabajo)</h3>
            </div>

            <div class="px-6 py-4 space-y-6 max-h-[70vh] overflow-y-auto">
                {{-- Destination --}}
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider border-b border-zinc-200 dark:border-zinc-800 pb-2">1. Seleccionar Sector</h4>
                    <flux:select wire:model="destination" label="Destino del Turno" required>
                        <option value="">Seleccione a dónde va...</option>
                        @foreach($destinations as $dest)
                            <option value="{{ $dest->value }}">{{ $dest->label() }}</option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Client & Vehicle --}}
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider border-b border-zinc-200 dark:border-zinc-800 pb-2">2. Identificar Vehículo</h4>
                    
                    <flux:select wire:model.live="customer_id" label="Cliente" required searchable>
                        <option value="">Escriba Nombre, Apellido o DNI...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->last_name }}, {{ $customer->first_name }} (DNI: {{ $customer->dni }})</option>
                        @endforeach
                    </flux:select>
                    
                    @if(count($vehicles) > 0)
                        <div class="mt-4 p-4 bg-zinc-50 dark:bg-zinc-800/30 rounded-xl border border-zinc-200 dark:border-zinc-700">
                            <flux:radio.group wire:model="vehicle_id" label="¿A qué vehículo le asignaremos el turno?" class="flex flex-col gap-2" required>
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

                {{-- Scheduling --}}
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider border-b border-zinc-200 dark:border-zinc-800 pb-2">3. Agendamiento</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <flux:input type="datetime-local" wire:model="scheduled_at" label="Fecha y Hora Programada" required />
                    </div>
                </div>

            </div>

            <div class="p-6 pt-2 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-3 rounded-b-xl">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" icon="calendar-days">Agendar Turno</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
