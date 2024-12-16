<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WeatherNotification;
use Illuminate\Support\Facades\Auth;

class NotificationsList extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = WeatherNotification::where('user_id', Auth::id())
            ->with(['city'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $this->unreadCount = WeatherNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead($notificationId)
    {
        WeatherNotification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->update(['is_read' => true]);

        $this->loadNotifications();
    }

    public function markAllAsRead()
    {
        WeatherNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notifications-list');
    }
}