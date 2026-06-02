<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BukuCard extends Component
{
    public $buku;
    public $showActions;

    public function __construct($buku, $showActions = true)
    {
        $this->buku        = $buku;
        $this->showActions = $showActions;
    }

    public function render()
    {
        return view('components.buku-card');
    }
}
