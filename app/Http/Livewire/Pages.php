<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Page;
use Illuminate\Validation\Rule;

class Pages extends Component
{

    public $modalFormVisible = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;

    public function updateShowModal($id)
    {
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
    }

    public function loadModel()
    {
        $data = Page::find($this->modelId);
        $this->title = $data->title;
        $this->slug = $data->slug;
        $this->content = $data->content;
    }

    public function create()
    {
        $this->validate();
        Page::create($this->modelData());
        $this->modalFormVisible = false;

        $this->resetVars();
    }

    public function rules()
    {
        return [
            'title' => 'requried',
            'slug' => ['required', Rule::unique('pages', 'slug')],
            'content' => 'requreid',
        ];
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

    public function read()
    {
        return Page::paginate(5);
    }

    public function render()
    {
        return view('livewire.pages', [
            'data' => $this->read(),
        ]);
    }
}
