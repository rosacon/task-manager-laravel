<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    public $title;
    public $image;
    public $link;

    public function __construct($title = null, $image = null, $link = null)
    {
        $this->title = $title;
        $this->image = $image;
        $this->link = $link;
    }

    public function render()
    {
        return view('components.card');
    }
}
