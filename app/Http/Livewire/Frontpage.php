<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Livewire\Component;

class Frontpage extends Component
{
    public $urlslug;
    public $title;
    public $content;

    public function mount($urlslug)
    {
        $this->retrieveContent($urlslug);
    }

    public function retrieveContent($urlslug)
    {
        // get home page if slug is empty
        if (empty($urlslug)) {
            $data = Page::where('is_default_home', true)->first();
        } else {
            $data = Page::where('slug', $urlslug)->first();
            if (!$data) {
                $data = Page::where('is_default_not_found', true)->first();
            }
        }

        $data = Page::where('slug', $urlslug)->first();
        $this->title = $data->title;
        $this->content = $data->content;
    }

    public function render()
    {
        return view('livewire.frontpage')->layout('layouts.frontpage');
    }
}
