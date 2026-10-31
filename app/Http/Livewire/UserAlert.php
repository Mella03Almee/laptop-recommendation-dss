<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class UserAlert extends Component
{
    public $userId;

    public function render()
    {
        return view('livewire.user-alert');
    }

    public function destroy($userId)
    {
        User::find($userId)->delete();

        return redirect('/dashboard/data-user');
    }
}
