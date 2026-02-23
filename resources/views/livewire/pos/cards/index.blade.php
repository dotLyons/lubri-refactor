<div>
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Tarjetas y Planes</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Gestiona todas las tarjetas de crédito, débito y sus respectivos planes de financiación o promociones.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <flux:modal.trigger name="create-card">
                    <flux:button variant="primary" icon="plus" class="w-full sm:w-auto">
                        <span>Nueva Tarjeta</span>
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    @if($cards->isEmpty())
                        <div class="text-center rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border border-dashed border-zinc-200 dark:border-zinc-700 p-12">
                            <svg class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-zinc-900 dark:text-white">No hay tarjetas registradas</h3>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Comienza agregando una nueva tarjeta y sus planes de pago.</p>
                            <div class="mt-6">
                                <flux:modal.trigger name="create-card">
                                    <flux:button variant="primary" icon="plus">
                                        Nueva Tarjeta
                                    </flux:button>
                                </flux:modal.trigger>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($cards as $card)
                                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm flex flex-col">
                                    <div class="p-5 flex-1 relative">
                                        
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white leading-tight">{{ $card->name }}</h3>
                                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $card->type->value === 'credit' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' }} mt-2">
                                                    {{ $card->type->value === 'credit' ? 'Crédito' : 'Débito' }}
                                                </span>
                                            </div>
                                            
                                            <flux:dropdown align="end">
                                                <flux:button variant="ghost" icon="ellipsis-vertical" class="!px-2" />
                                                <flux:menu>
                                                    <flux:menu.item icon="pencil" wire:click="$dispatch('edit-card', { cardId: {{ $card->id }} })">Editar</flux:menu.item>
                                                    <flux:menu.item icon="trash" variant="danger" wire:click="deleteCard({{ $card->id }})" wire:confirm="¿Estás seguro de eliminar esta tarjeta? Esta acción no se puede deshacer.">Eliminar</flux:menu.item>
                                                </flux:menu>
                                            </flux:dropdown>
                                        </div>

                                        <div class="mt-4 border-t border-zinc-100 dark:border-zinc-800 pt-4">
                                            <h4 class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Planes ({{ $card->plans->count() }})</h4>
                                            
                                            <div class="space-y-2">
                                                @foreach($card->plans as $plan)
                                                    <div class="flex justify-between items-center text-sm bg-zinc-50 dark:bg-zinc-800/50 p-2 rounded-lg">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $plan->name }}</span>
                                                            @if($plan->is_promotion)
                                                                <span class="bg-amber-100 text-amber-800 text-[10px] px-1.5 py-0.5 rounded-md font-bold dark:bg-amber-900/30 dark:text-amber-400">PROMO</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-right">
                                                            <span class="text-zinc-900 dark:text-white font-mono">{{ $plan->surcharge_percentage }}%</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @livewire('pos.cards.create')
    @livewire('pos.cards.edit')
</div>
