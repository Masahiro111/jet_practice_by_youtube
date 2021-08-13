<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Pages extends Component
{

    public $modalFormVisible = false;
    public $slug;
    public $title;
    public $content;

    public function create()
    {
        $this->validate([
            'title' => 'required',
            'slug' => ['required', Rule::unique('pages', 'slug')],
            'content' => 'required',
        ]);

        Page::create($this->modelData());
        $this->modalFormVisible = false;

        $this->resetVars();
    }

    public function updatedTitle($value)
    {
        $this->generateSlug($value);
    }

    private function generateSlug($value)
    {
        $process1 = str_replace(' ', '-', $value);
        $process2 = strtolower($process1);
        $this->slug = $process2;
    }

    public function resetVars()
    {
        $this->title = null;
        $this->slug = null;
        $this->content = null;
    }

    public function createShowModal()
    {
        $this->modalFormVisible = true;
    }

    public function modelData()
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
        ];
    }

    public function render()
    {
        return view('livewire.pages');
    }
}
