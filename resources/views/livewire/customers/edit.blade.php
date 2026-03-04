<div>
    <flux:modal name="edit-customer" class="md:w-3/4 max-w-4xl">
        <form wire:submit="save" class="space-y-6">
            <div class="flex items-center p-6 border-b border-zinc-200 dark:border-zinc-800">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Editar Cliente</h3>
            </div>

            <div class="px-6 py-4 space-y-6 max-h-[70vh] overflow-y-auto">
                {{-- Client Info --}}
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider border-b border-zinc-200 dark:border-zinc-800 pb-2">Información Personal</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <flux:input wire:model="dni" label="DNI" placeholder="Ej. 12345678" required />
                        <flux:input wire:model="birth_date" type="date" label="Fecha de Nacimiento (Opcional)" />
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <flux:input wire:model="first_name" label="Nombre" placeholder="Nombre/s" required />
                        <flux:input wire:model="last_name" label="Apellido" placeholder="Apellido/s" required />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <flux:input wire:model="primary_phone" label="Celular Principal" placeholder="Ej. 3511234567" required />
                        <flux:input wire:model="secondary_phone" label="Celular Alternativo (Opcional)" placeholder="Ej. 3517654321" />
                    </div>
                </div>

                {{-- Vehicles Info --}}
                <div class="space-y-4 pt-4">
                    <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 pb-2">
                        <h4 class="text-sm font-semibold text-zinc-500 uppercase tracking-wider">Vehículos a Registrar</h4>
                        <flux:button size="sm" icon="plus" wire:click="addVehicle" variant="ghost">Agregar Vehículo</flux:button>
                    </div>

                    @foreach($vehicles as $index => $vehicle)
                        <div class="bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 relative">
                            @if(count($vehicles) > 1)
                                <button type="button" wire:click="removeVehicle({{ $index }})" wire:confirm="¿Seguro que deseas eliminar este vehículo de la lista?" class="absolute top-2 right-2 text-zinc-400 hover:text-red-500">
                                    <flux:icon.x-mark class="size-5" />
                                </button>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                <flux:select wire:model.live="vehicles.{{ $index }}.type" label="Categoría" required>
                                    @foreach($vehicleTypes as $type)
                                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                    @endforeach
                                </flux:select>
                                
                                <flux:input wire:model="vehicles.{{ $index }}.brand" label="Marca" placeholder="Ej. Toyota, Ford" required />
                                <flux:input wire:model="vehicles.{{ $index }}.model" label="Modelo" placeholder="Ej. Hilux, Focus" required />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
                                <flux:input wire:model="vehicles.{{ $index }}.year" type="number" label="Año" required />
                                <flux:input wire:model="vehicles.{{ $index }}.license_plate" label="Patente" placeholder="AA123BB" class="uppercase" required />
                                <flux:input wire:model="vehicles.{{ $index }}.version" label="Versión (Opcional)" placeholder="Ej. 2.0 Titanium" />
                                <flux:input wire:model="vehicles.{{ $index }}.color" label="Color (Opcional)" placeholder="Ej. Blanco" />
                            </div>

                            {{-- Dynamic fields based on Vehicle Type --}}
                            @if($vehicles[$index]['type'] === 'pickup_truck')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-indigo-50 dark:bg-indigo-900/20 p-3 rounded-lg border border-indigo-100 dark:border-indigo-800/30">
                                    <flux:select wire:model="vehicles.{{ $index }}.pickup_cabin_type" label="Tipo de Cabina (Camionetas)">
                                        <option value="">Seleccione tipo...</option>
                                        @foreach($pickupCabinTypes as $cabinType)
                                            <option value="{{ $cabinType->value }}">{{ $cabinType->label() }}</option>
                                        @endforeach
                                    </flux:select>
                                </div>
                            @elseif($vehicles[$index]['type'] === 'motorcycle')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-emerald-50 dark:bg-emerald-900/20 p-3 rounded-lg border border-emerald-100 dark:border-emerald-800/30">
                                    <flux:input wire:model="vehicles.{{ $index }}.engine_displacement" label="Cilindrada (Motos)" placeholder="Ej. 150cc, 250cc, 1000" />
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="p-6 pt-2 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-3 rounded-b-xl">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" icon="check">Actualizar Cliente</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
