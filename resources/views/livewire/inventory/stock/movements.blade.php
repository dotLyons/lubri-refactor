<div class="flex flex-col h-full max-h-[80vh]">
    <div
        class="border-b border-zinc-200 dark:border-zinc-800 px-6 py-4 flex items-center justify-between bg-zinc-50/50 dark:bg-zinc-800/50 shrink-0">
        <div>
            <h3 class="text-lg font-semibold leading-6 text-zinc-900 dark:text-white">Movimientos de Stock</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Historial completo de entradas y salidas.</p>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-6 py-6">
        @if($productId)
            <div
                class="overflow-x-auto bg-white border border-zinc-200 rounded-lg shadow-sm dark:bg-zinc-900 dark:border-zinc-700">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                                Fecha</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                                Tipo</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                                Cantidad</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                                Usuario</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                                Razón</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                        @forelse ($movements as $movement)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $movement->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $movement->type === 'in' ? 'bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400' }}">
                                        {{ $movement->type === 'in' ? 'Entrada' : 'Salida' }}
                                    </span>
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium {{ $movement->type === 'in' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-200">
                                    {{ $movement->user->name ?? 'Sistema' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400 min-w-[300px]">
                                    {{ $movement->reason ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                    No hay movimientos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($movements, 'links') && $movements->hasPages())
                <div
                    class="mt-4 border-t border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 rounded-b-lg">
                    {{ $movements->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Seleccione un producto para ver sus movimientos.</p>
            </div>
        @endif
    </div>

    <div
        class="border-t border-zinc-200 dark:border-zinc-800 px-6 py-4 bg-zinc-50/50 dark:bg-zinc-800/50 shrink-0 flex justify-end">
        <flux:modal.close>
            <button type="button"
                class="rounded-lg bg-white dark:bg-zinc-800 px-3.5 py-2.5 text-sm font-semibold text-zinc-900 dark:text-zinc-300 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                Cerrar
            </button>
        </flux:modal.close>
    </div>
</div>