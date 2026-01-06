<div class="ms-3 relative">
    <x-dropdown align="right" width="64">
        <x-slot name="trigger">
            <button class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none transition group">
                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 flex size-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full size-4 bg-red-600 text-[10px] text-white font-bold items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    </span>
                @endif
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="block px-4 py-2 text-xs font-bold text-gray-800 uppercase tracking-widest bg-gray-50 border-b border-gray-100">
                {{ __('Notifications') }}
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead" class="ms-2 normal-case font-medium text-blue-600 hover:text-blue-800 transition">
                        {{ __('Mark all as read') }}
                    </button>
                @endif
            </div>

            <div class="max-h-96 overflow-y-auto">
                @forelse($notifications as $notification)
                    <button wire:click="markAsRead('{{ $notification->id }}')" class="w-full text-start block px-4 py-3 hover:bg-gray-100 transition border-b border-gray-50 last:border-0 group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="p-1.5 bg-blue-100 text-blue-600 rounded-full group-hover:bg-blue-600 group-hover:text-white transition">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ms-3">
                                <p class="text-xs font-bold text-gray-900 leading-tight">
                                    {{ $notification->data['from_user'] }} marked a letter
                                </p>
                                <p class="text-[10px] font-bold text-blue-700 mt-0.5">
                                    #{{ $notification->data['register_number'] }}
                                </p>
                                <p class="text-[10px] text-gray-600 mt-1 line-clamp-2 italic">
                                    "{{ $notification->data['message'] }}"
                                </p>
                                <p class="text-[9px] text-gray-400 mt-1 font-medium italic">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="px-4 py-6 text-center">
                        <svg class="size-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-xs text-gray-500">{{ __('No new notifications') }}</p>
                    </div>
                @endforelse
            </div>
        </x-slot>
    </x-dropdown>
</div>
