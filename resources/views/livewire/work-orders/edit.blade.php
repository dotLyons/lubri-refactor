<div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
    @if($workOrder)
        <div class="mb-4">
            <flux:button href="{{ route('work-orders.index') }}" variant="ghost" icon="arrow-left" wire:navigate>Volver al Listado</flux:button>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b border-zinc-200 dark:border-zinc-800 mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">
                        Orden de Trabajo #{{ str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) }}
                    </h3>
                    @if($workOrder->status->value === 'open')
                        <flux:badge size="sm" color="amber">Abierta / En Curso</flux:badge>
                    @else
                        <flux:badge size="sm" color="emerald">Cobrada - CERRADA</flux:badge>
                    @endif
                </div>
                <p class="text-sm text-zinc-500 mt-2">
                    <flux:icon.map-pin class="size-4 inline-block -mt-0.5 text-zinc-400" />
                    {{ $workOrder->destination->label() }} &mdash; 
                    <span class="font-medium text-zinc-900 dark:text-zinc-200">{{ $workOrder->customer->last_name }}, {{ $workOrder->customer->first_name }}</span> 
                    ({{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }} / <span class="uppercase">{{ $workOrder->vehicle->license_plate }}</span>)
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            
            {{-- Left Column: Core Work Order details & products --}}
            <div class="xl:col-span-8 space-y-8">
                
                {{-- Schedule Section --}}
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-white flex items-center gap-2 mb-4">
                        <flux:icon.calendar-days class="size-5 text-indigo-500" /> Fecha y Hora del Turno
                    </h4>
                    <div class="flex flex-col sm:flex-row gap-4 items-end">
                        <flux:input type="datetime-local" wire:model="scheduled_at" label="Programado para" class="flex-1" :disabled="$workOrder->status->value === 'closed'" />
                        @if($workOrder->status->value === 'open')
                            <flux:button wire:click="updateSchedule" icon="arrow-path">Actualizar Fecha</flux:button>
                        @endif
                    </div>
                    @if (session()->has('success_schedule'))
                        <p class="text-emerald-500 text-sm mt-3 font-medium flex items-center gap-1.5"><flux:icon.check-circle class="size-4" /> {{ session('success_schedule') }}</p>
                    @endif
                </div>

                {{-- Products / Stock used section --}}
                <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 border-b border-zinc-100 dark:border-zinc-800 pb-4 gap-4">
                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <flux:icon.beaker class="size-5 text-indigo-500" /> Insumos y Servicios Cargados
                        </h4>
                        @if($workOrder->status->value === 'open')
                            <flux:modal.trigger name="search-products">
                                <flux:button variant="primary" icon="plus" size="sm">Abrir Catálogo de Productos</flux:button>
                            </flux:modal.trigger>
                        @endif
                    </div>

                    <div class="overflow-x-auto ring-1 ring-zinc-200 dark:ring-zinc-800 rounded-lg">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                            <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                <tr>
                                    <th scope="col" class="py-3 px-4 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">Detalle del Ítem</th>
                                    <th scope="col" class="py-3 px-4 text-right text-xs font-semibold text-zinc-500 uppercase tracking-wider">Precio Unit.</th>
                                    <th scope="col" class="py-3 px-4 text-right text-xs font-semibold text-zinc-500 uppercase tracking-wider">Cantidad</th>
                                    <th scope="col" class="py-3 px-4 text-right text-xs font-semibold text-zinc-500 uppercase tracking-wider">Subtotal ($)</th>
                                    <th scope="col" class="py-3 px-4 w-12"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                                @forelse($items as $item)
                                    <tr>
                                        <td class="py-4 px-4 text-sm text-zinc-900 dark:text-white font-medium">
                                            {{ $item['name'] }}
                                            <span class="block text-[10px] text-zinc-500 uppercase tracking-wider mt-0.5">{{ $item['category'] }}</span>
                                        </td>
                                        <td class="py-4 px-4 text-sm text-zinc-500 dark:text-zinc-400 text-right">${{ number_format($item['unit_price'], 2) }}</td>
                                        <td class="py-4 px-4 text-sm text-right flex justify-end gap-2 items-center h-full">
                                            @if($workOrder->status->value === 'open')
                                                <input 
                                                    type="number" 
                                                    step="1" 
                                                    min="0"
                                                    max="{{ $item['max_quantity'] }}"
                                                    x-data="{ max: {{ $item['max_quantity'] }} }"
                                                    x-on:input="if(Number($el.value) > max) { $el.value = max; }"
                                                    value="{{ $item['quantity'] }}" 
                                                    wire:change="updateItemQuantity({{ $item['id'] }}, $event.target.value)" 
                                                    class="w-24 rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm shadow-sm transition duration-200 ease-in-out py-1.5 px-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-right font-medium">
                                            @else
                                                <span class="font-bold text-zinc-900 dark:text-white bg-zinc-100 dark:bg-zinc-800 px-3 py-1 rounded-md">{{ $item['quantity'] }}</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-sm font-bold text-zinc-900 dark:text-white text-right">${{ number_format($item['subtotal'], 2) }}</td>
                                        <td class="py-4 px-4 text-right">
                                            @if($workOrder->status->value === 'open')
                                                <button wire:click="removeItem({{ $item['id'] }})" class="text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 p-1.5 rounded-md transition-colors" title="Quitar ítem">
                                                    <flux:icon.trash class="size-5" />
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                            <flux:icon.inbox class="size-8 mx-auto text-zinc-300 dark:text-zinc-600 mb-3" />
                                            Aún no hay productos ni insumos cargados en esta orden.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <td colspan="3" class="py-4 px-4 text-right text-sm font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-widest">Total Acumulado:</td>
                                    <td class="py-4 px-4 text-right text-xl font-black text-indigo-600 dark:text-indigo-400">${{ number_format($totalAmount, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right Column: Billing and Closure logic --}}
            <div class="xl:col-span-4 space-y-6">
                @if($workOrder->status->value === 'open')
                    <div class="bg-indigo-600 dark:bg-indigo-700 rounded-2xl p-6 shadow-xl border border-indigo-500 text-white flex flex-col justify-between overflow-hidden relative sticky top-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        
                        <div class="relative z-10">
                            <h4 class="text-xl font-black uppercase tracking-wider mb-3 drop-shadow-sm flex justify-between items-center border-b border-indigo-400/30 pb-4">
                                Pre-Facturación 
                                <flux:icon.document-text class="size-7 text-white/50" />
                            </h4>
                            
                            <p class="text-indigo-100 text-sm mb-6 leading-relaxed bg-indigo-900/30 p-3 rounded-lg border border-indigo-400/20 text-justify">
                                Al generar la factura, el ticket pasará al área de cobranzas donde se podrán imputar múltiples pagos, transferencias o financiar con tarjeta.
                            </p>
                            
                            <div class="relative z-10 w-full mt-6 pt-6 border-t border-indigo-500/50">
                                <button wire:click="generateInvoice" wire:confirm="¿Estás seguro de generar la factura para esta orden?" class="w-full bg-white text-indigo-900 rounded-xl font-black text-xl py-4 transition-all shadow-xl hover:-translate-y-1 hover:shadow-indigo-900/50 active:translate-y-0 active:scale-95 duration-200 uppercase tracking-widest flex flex-col items-center justify-center gap-1">
                                    <span>IR A COBRAR</span>
                                    <span class="bg-indigo-100/50 text-indigo-900 px-3 py-1 rounded-md text-sm">${{ number_format($totalAmount, 2) }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Read Only Details Block for Closed Orders --}}
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-6 shadow-sm border border-emerald-200 dark:border-emerald-800 sticky top-8">
                            <div class="flex items-center gap-4 mb-5 border-b border-emerald-200 dark:border-emerald-800 pb-5">
                                <div class="size-12 rounded-full bg-emerald-100 dark:bg-emerald-800 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                                    <flux:icon.check-circle class="size-7" />
                                </div>
                                <div>
                                    <h4 class="text-emerald-900 dark:text-emerald-100 text-lg font-bold leading-none mb-1.5">Orden Cobrada</h4>
                                    <p class="text-xs text-emerald-600/80 dark:text-emerald-400 font-medium">Este servicio fue abonado y cerrado. El ticket ya fue cursado en Caja.</p>
                                </div>
                            </div>
                            <div class="bg-white/70 dark:bg-black/20 rounded-xl p-5 border border-emerald-100 dark:border-emerald-800/50 space-y-3">
                                <div class="flex justify-between items-center text-zinc-900 dark:text-white">
                                    <span class="text-sm font-semibold text-zinc-500">Monto Base:</span>
                                    <span class="text-lg font-medium text-zinc-700 dark:text-zinc-300">${{ number_format($totalAmount, 2) }}</span>
                                </div>
                                @if($workOrder->invoice)
                                    <div class="flex justify-between items-center text-zinc-900 dark:text-white pt-2 border-t border-emerald-100 dark:border-emerald-800/50 mt-2">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">Total Facturado:</span>
                                            <span class="text-xs text-zinc-500">Incluye recargos por intereses financieros</span>
                                        </div>
                                        <span class="text-xl font-black text-emerald-600 dark:text-emerald-400">${{ number_format($workOrder->invoice->total_amount, 2) }}</span>
                                    </div>
                                        <div class="mt-4 pt-3 border-t border-emerald-100 dark:border-emerald-800/50 flex flex-col gap-2">
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-zinc-500">Estado de Factura:</span>
                                                <flux:badge size="sm" color="{{ $workOrder->invoice->status->value === 'paid' ? 'emerald' : 'amber' }}">{{ $workOrder->invoice->status->label() }}</flux:badge>
                                            </div>
                                            <div class="flex gap-2 mt-2">
                                                <flux:button href="{{ route('work-orders.pdf', $workOrder->id) }}" variant="subtle" size="sm" icon="printer" class="w-full flex-1" target="_blank">Imprimir Orden</flux:button>
                                                <flux:button href="{{ route('invoices.pay', $workOrder->invoice->id) }}" variant="subtle" size="sm" icon="banknotes" class="w-full flex-1">Ver Pagos</flux:button>
                                            </div>
                                        </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    {{-- Product Search Modal --}}
    <flux:modal name="search-products" class="md:w-4/5 max-w-5xl">
        <div class="flex items-center p-6 border-b border-zinc-200 dark:border-zinc-800">
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Catálogo de Productos y Servicios</h3>
        </div>
        <div class="px-6 py-6 space-y-4">
            <flux:input wire:model.live.debounce.300ms="productSearch" icon="magnifying-glass" placeholder="Buscar por Nombre, Código o Código de Barras..." class="w-full" />
            
            <div class="overflow-x-auto ring-1 ring-zinc-200 dark:ring-zinc-800 rounded-lg mt-4 max-h-[60vh]">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                        <tr>
                            <th scope="col" class="py-3 px-4 text-left text-sm font-semibold text-zinc-500 uppercase">Detalle</th>
                            <th scope="col" class="py-3 px-4 text-right text-sm font-semibold text-zinc-500 uppercase">Stock</th>
                            <th scope="col" class="py-3 px-4 text-right text-sm font-semibold text-zinc-500 uppercase">Precio Unitario</th>
                            <th scope="col" class="py-3 px-4 w-12 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                        @forelse($modalProducts as $prod)
                            <tr wire:key="prod-{{ $prod->id }}">
                                <td class="py-3 px-4 text-sm font-medium text-zinc-900 dark:text-white">
                                    {{ $prod->product_name }}
                                    <span class="block text-xs font-normal text-zinc-500 mt-1">Ref: {{ $prod->product_code }} | EAN: {{ $prod->bar_code }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm text-right font-mono {{ ($prod->stock && $prod->stock->quantity > 0) ? 'text-zinc-500' : 'text-red-500 font-bold' }}">
                                    {{ $prod->stock ? $prod->stock->quantity : 'N/A' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-right font-bold text-zinc-900 dark:text-white">
                                    ${{ number_format($prod->sale_price, 2) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <flux:button wire:click="addItem({{ $prod->id }})" variant="primary" size="sm" icon="plus" class="w-full" title="Agregar unidad">Agregar 1</flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-sm text-zinc-500">No se encontraron productos coincidentes en el inventario.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($modalProducts->hasPages())
                <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-800">
                    {{ $modalProducts->links() }}
                </div>
            @endif
        </div>
    </flux:modal>
</div>
