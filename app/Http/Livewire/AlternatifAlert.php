<?php

namespace App\Http\Livewire;

use App\Models\Alternatif;
use Livewire\Component;

class AlternatifAlert extends Component
{
    public $alternatifId;

    public function render()
    {
        return view('livewire.alternatif-alert');
    }

    public function destroy($alternatifId)
    {
        Alternatif::find($alternatifId)->delete();

        return redirect('/dashboard/data-alternatif');
    }
}
