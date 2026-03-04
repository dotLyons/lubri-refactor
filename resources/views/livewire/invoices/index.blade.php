<div>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-widest flex items-center gap-2">
            <flux:icon.document-currency-dollar class="size-6 text-indigo-500" />
            Facturación y Cobranzas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-800 rounded-xl p-4">
                <div class="flex-1 w-full sm:max-w-md relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <flux:icon.magnifying-glass class="size-5 text-zinc-400" />
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por cliente, DNI, o factura..." class="block w-full pl-10 bg-zinc-50 dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-zinc-900 dark:text-white transition-colors">
                </div>
                
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <input type="date" wire:model.live="dateFilter" class="block bg-zinc-50 dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-zinc-900 dark:text-white transition-colors">
                    
                    <select wire:model.live="statusFilter" class="block w-full sm:w-auto bg-zinc-50 dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-zinc-900 dark:text-white transition-colors">
                        <option value="">Todos los Estados</option>
                        @foreach(\App\Src\Invoices\Enums\InvoiceStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-xl border border-zinc-200 dark:border-zinc-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800 uppercase tracking-wider text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left">Factura</th>
                                <th scope="col" class="px-6 py-4 text-left">Cliente</th>
                                <th scope="col" class="px-6 py-4 text-left">O. Trabajo / Vehículo</th>
                                <th scope="col" class="px-6 py-4 text-right">Monto Total</th>
                                <th scope="col" class="px-6 py-4 text-center">Estado</th>
                                <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                            @forelse($invoices as $invoice)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <flux:icon.document-text class="size-4 text-indigo-500" />
                                            <span class="text-sm font-bold text-zinc-900 dark:text-white font-mono tracking-widest">
                                                #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                            {{ $invoice->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-zinc-900 dark:text-white">
                                            {{ $invoice->customer->first_name }} {{ $invoice->customer->last_name }}
                                        </div>
                                        <div class="text-xs text-zinc-500 font-medium">DNI: {{ $invoice->customer->dni }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <a href="{{ route('work-orders.edit', $invoice->work_order_id) }}" wire:navigate class="text-sm font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors uppercase tracking-wider flex items-center gap-1">
                                            TICKET #{{ $invoice->work_order_id }} 
                                        </a>
                                        <div class="text-xs text-zinc-500 mt-1">
                                            {{ $invoice->workOrder->vehicle?->brand ?? 'S/R' }} - {{ $invoice->workOrder->vehicle?->license_plate ?? 'S/R' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-black text-zinc-900 dark:text-white">
                                            ${{ number_format($invoice->total_amount, 2) }}
                                        </div>
                                        @if($invoice->balance_due > 0)
                                            <div class="text-[10px] font-bold text-rose-500 tracking-wider">
                                                DEBE: ${{ number_format($invoice->balance_due, 2) }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <flux:badge color="{{ match($invoice->status->value) { 'paid' => 'success', 'partial' => 'warning', default => 'danger' } }}" size="sm" class="uppercase tracking-widest font-bold">
                                            {{ $invoice->status->label() }}
                                        </flux:badge>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <div class="flex justify-end gap-2">
                                            @if($invoice->status->value !== 'paid')
                                                <flux:button href="{{ route('invoices.pay', $invoice->id) }}" variant="primary" size="sm" icon="banknotes">Cobrar</flux:button>
                                            @endif
                                            
                                            <flux:button href="{{ route('invoices.pdf', $invoice->id) }}" variant="subtle" size="sm" icon="document-arrow-down" target="_blank">PDF</flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                        No se encontraron facturas con los filtros actuales.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($invoices->hasPages())
                    <div class="border-t border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-900 sm:px-6">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
