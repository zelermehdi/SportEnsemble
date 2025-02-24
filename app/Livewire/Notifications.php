<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    public $notifications = [];

    protected $listeners = ['notificationRecu' => 'chargerNotifications'];

    public function mount()
    {
        $this->chargerNotifications();
    }

    public function chargerNotifications()
    {
        if (Auth::check()) {
            $this->notifications = Auth::user()->notifications()->take(5)->get();
        }
    }

    public function marquerCommeLue($id)
    {
        if (auth()->check()) {
            $notification = auth()->user()->notifications->find($id);
            if ($notification) {
                $notification->markAsRead();
            }
        }
    }
    

    public function render()
    {
        return view('livewire.notifications');
    }
}
