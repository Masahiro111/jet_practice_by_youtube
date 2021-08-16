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

        // Pageモデルの「slug」カラムが「$urlslug」と一致する最初の行を取得するよ
        // $data = Page::where('slug', $urlslug)->first();

        // 公開メンバ変数「$title」に、取得したレコードのtitle列の値を入れるよ
        $this->title = $data->title;

        // 公開メンバ変数「$content」に、取得したレコードのcontent列の値を入れるよ
        $this->content = $data->content;
    }

    public function render()
    {
        return view('livewire.frontpage')
            ->layout('layouts.frontpage');
    }
}
