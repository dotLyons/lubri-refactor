<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Categorías de Productos</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Gestiona y organiza las categorías de tu inventario
                desde aquí.</p>
        </div>
        <flux:modal.trigger name="create-category">
            <button type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition-colors">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nueva Categoría
            </button>
        </flux:modal.trigger>
    </div>

    {{-- Controls --}}
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-zinc-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="block w-full py-2 pl-10 pr-3 text-sm border border-zinc-300 rounded-lg leading-5 bg-white text-zinc-900 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-100 dark:placeholder-zinc-400 dark:focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out shadow-sm"
                placeholder="Buscar por nombre...">
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-4 mb-6">
        @forelse ($categories as $category)
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white">{{ $category->category_name }}
                        </h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $category->description ?? '-' }}</p>
                    </div>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $category->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20' }}">
                        <span
                            class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $category->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        {{ $category->status === 'active' ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    <button wire:click="$dispatch('edit-category', { id: {{ $category->id }} })"
                        class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        Editar
                    </button>

                    <flux:modal.trigger name="delete-category">
                        <button wire:click="confirmDelete({{ $category->id }})"
                            class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            Eliminar
                        </button>
                    </flux:modal.trigger>
                </div>
            </div>
        @empty
            <div class="text-center py-8 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700">
                <p class="text-sm text-zinc-500">No se encontraron categorías</p>
                <p class="mt-1 text-xs text-zinc-400">Intenta ajustar tu búsqueda o crea una nueva.</p>
            </div>
        @endforelse

        @if($categories->hasPages())
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    {{-- Table (Desktop) --}}
    <div
        class="hidden md:block overflow-hidden bg-white border border-zinc-200 rounded-xl shadow-sm dark:bg-zinc-900 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3.5 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            Nombre
                        </th>
                        <th scope="col"
                            class="px-6 py-3.5 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            Descripción
                        </th>
                        <th scope="col"
                            class="px-6 py-3.5 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            Estado
                        </th>
                        <th scope="col" class="relative px-6 py-3.5">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                    {{ $category->category_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-500 dark:text-zinc-400 max-w-xs truncate"
                                    title="{{ $category->description }}">
                                    {{ $category->description ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $category->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20' }}">
                                    <span
                                        class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $category->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $category->status === 'active' ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    <button wire:click="$dispatch('edit-category', { id: {{ $category->id }} })"
                                        class="text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                        title="Editar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>

                                    <flux:modal.trigger name="delete-category">
                                        <button wire:click="confirmDelete({{ $category->id }})"
                                            class="text-zinc-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                                            title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </flux:modal.trigger>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-zinc-300 dark:text-zinc-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">No se encontraron
                                    categorías</p>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Intenta ajustar tu búsqueda o crea
                                    una nueva categoría.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="border-t border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800 sm:px-6">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <livewire:inventory.create />
    <livewire:inventory.edit />

    {{-- Delete Modal --}}
    <flux:modal name="delete-category" class="md:w-[400px] !p-0 overflow-hidden bg-white dark:bg-zinc-900 rounded-xl">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-start">
                <div
                    class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="ml-4 mt-0.5 text-left">
                    <h3 class="text-base font-semibold leading-6 text-zinc-900 dark:text-zinc-100">Eliminar Categoría
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            ¿Estás a punto de eliminar esta categoría. Esta acción es irreversible y podría afectar
                            productos asociados.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-200 dark:border-zinc-800">
            <button wire:click="delete" type="button"
                class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-zinc-900">
                Eliminar
            </button>
            <flux:modal.close>
                <button type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-zinc-800 px-3 py-2 text-sm font-semibold text-zinc-900 dark:text-zinc-300 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 sm:mt-0 sm:w-auto transition-colors">
                    Cancelar
                </button>
            </flux:modal.close>
        </div>
    </flux:modal>
</div>