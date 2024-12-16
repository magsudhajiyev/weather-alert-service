<!-- resources/views/livewire/notifications-list.blade.php -->
<div>
    <div x-data="{ open: false }" class="relative">
        <!-- Notification Bell -->
        <button @click="open = !open" class="relative p-1 text-gray-600 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            @if($unreadCount > 0)
                <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                    {{ $unreadCount }}
                </span>
            @endif
        </button>

        <!-- Dropdown -->
        <div x-show="open" 
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-3 w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50">
            
            <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
                @if($notifications->isNotEmpty())
                    <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                        @if($unreadCount > 0)
                            <button wire:click="markAllAsRead" 
                                    class="text-xs text-indigo-600 hover:text-indigo-900 font-medium">
                                Mark all as read
                            </button>
                        @endif
                    </div>

                    @foreach($notifications as $notification)
                        <div class="relative px-4 py-3 {{ !$notification->is_read ? 'bg-blue-50' : '' }} hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ ucfirst($notification->type) }} Alert
                                        </p>
                                        @if(!$notification->is_read)
                                            <button wire:click="markAsRead({{ $notification->id }})" 
                                                    class="ml-2 text-indigo-600 hover:text-indigo-900">
                                                <span class="sr-only">Mark as read</span>
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="mt-1">
                                        <p class="text-sm text-gray-600">
                                            {{ $notification->city->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Current: {{ $notification->getFormattedValue() }}
                                            (Threshold: {{ number_format($notification->threshold_value, 1) }})
                                        </p>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="px-4 py-12 text-center">
                        <span class="text-gray-500 text-sm">No notifications</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>