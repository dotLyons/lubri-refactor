<x-layouts::app :title="__('Dashboard')">
    <div
        class="flex h-full w-full flex-col items-center justify-center gap-6 rounded-xl bg-white p-8 text-center dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">

        <div class="rounded-full bg-indigo-50 p-6 dark:bg-indigo-500/10 animate-pulse">
            <svg class="h-16 w-16 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.703.127 1.243.729 1.215 2.483 1.215 2.483m-17 0h17" />
            </svg>
        </div>

        <div class="max-w-md space-y-2">
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Dashboard en Construcción
            </h2>
            <p class="text-zinc-500 dark:text-zinc-400">
                Estamos trabajando duro para traerte las mejores métricas y estadísticas de tu negocio. ¡Vuelve pronto
                para ver las novedades!
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <a href="{{ route('inventory.products.index') }}" wire:navigate
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                Gestionar Productos
            </a>

            <a href="{{ route('inventory.stocks.index') }}" wire:navigate
                class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-600 dark:hover:bg-zinc-700 transition-colors">
                <svg class="mr-2 -ml-1 h-5 w-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
                </svg>
                Ver Stock
            </a>
        </div>
    </div>
</x-layouts::app>