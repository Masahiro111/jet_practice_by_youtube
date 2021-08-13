# Laravel CRUD system tutorial sheet

## Install Laravel 8 via installer

コマンド入力画面を開いて、Laravel をインストールするコマンドを入力

```command
laravel new project_name --jet
```

`--jet`フラグを記入することで Jetstream 環境を Laravel 本体と一緒にインストールできます。

次に、Jetstream の view ファイルを手軽に設定できるように、resource フォルダ以下にコピーするコマンドを入力します。

```command
php artisan vendor:publish --tag=jetstream-views
```

Jetstream の view ファイル群がコピーされたら、次に Page モデルとそのマイグレーションファイルを作成します。

```command
php artisan make:model Page -m
```

作成されたマイグレーションファイルを開いて（～\_create_pages_table.php）up メソッドの部分を編集します。

```diff
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
+          $table->string('title')->nullable();
+          $table->string('slug')->nullable();
+          $table->longText('content')->nullable();
            $table->timestamps();
        });
    }
```

モデル page のマイグレーションファイルを編集したら、以下のコマンドで pages テーブルを作成しましょう。

```command
php artisan migrate
```

一緒に livewire のコンポーネントも作成します。

```command
php artisan make:livewire Pages
php artisan make:livewire Frontpage
```

`resources/views/admin` に `pages.blade.php`を追加するよ

```php:pages.blade.php
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                The Pages page.
            </div>
        </div>
    </div>
</x-app-layout>
```

`resources/views/navigation-menu.blade.php` の 13 行目あたりを編集します。メニューバーに「Pages」のリンクが表示されました！

```php
<!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-jet-nav-link>
    <x-jet-nav-link href="{{ route('pages') }}" :active="request()->routeIs('pages')">
        {{ __('Pages') }}
    </x-jet-nav-link>
</div>
```

Page モデルファイルを開いて編集します。

```diff
class Page extends Model
{
    use HasFactory;

+  protected $guarded = [];
}
```

そして、`resources/views/admin/pages.blade.php` を更に編集します。

```diff
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
+              @livewire('pages')
            </div>
        </div>
    </div>
</x-app-layout>
```

`resources/views/livewire/pages.blade.php` も編集するよ。

```html
<div>
    <p>Pages livewwire component</p>
</div>
```

ここでいったんブラウザで確認してみると`pages.blade.php` の `@livewire('pages')` の部分によって`resources\views\livewire\pages.blade.php`のファイルが取り込まれていることが分かります。

@livewire ディレクティブによって、`resources\views\livewire\`以下のファイルが読み込まれます。読み込まれるファイルは引数内の文字列で設定できます。

livewire コンポーネントの`app\Http\Livewire\Pages.php`を編集しましょう

```diff
<?php

namespace App\Http\Livewire;

use Livewire\Component;
+ use App\Models\Page;

class Pages extends Component
{

+    public $modalFormVisible = false;
+    public $slug;
+    public $title;
+    public $content;
+
+    public function createShowModal()
+    {
+        $this->modalFormVisible = true;
+    }

    public function render()
    {
        return view('livewire.pages');
    }
}
```

`resources\views\livewire\pages.blade.php`も編集します。モーダルウィンドウの表示のためのコードを書きます。

```diff
<div class="p-6">
+    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
            <x-jet-button wire:click="createShowModal">
                {{ __('Create') }}
            </x-jet-button>
+    </div>

+   {{-- <!-- Modal Form -- > --}}
+   <x-jet-dialog-modal wire:model="modalFormVisible">
+       <x-slot name="title">
+           {{ __('Save Page') }}
+       </x-slot>
+
+       <x-slot name="content">
+           The form elements goes here
+       </x-slot>
+
+       <x-slot name="footer">
+           <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
+               {{ __('Cancel') }}
+           </x-jet-secondary-button>
+
+           <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
+               {{ __('Save') }}
+           </x-jet-button>
+       </x-slot>
+   </x-jet-dialog-modal>

</div>
```

続いて、`<x-slot name="content">`タグ内にコンテンツとなるコードを書き込んでいきます。(2-14:30)

```diff
<x-slot name="content">
+    <div class="my-4">
+        <x-jet-label for="title" value="{{ __('Title') }}" />
+        <x-jet-input id="title" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="title" />
+    </div>
+    <input wire:model="title">
+    <div class="my-4">
+        <x-jet-label for="slug" value="{{ __('Slug') }}" />
+        <x-jet-input id="slug" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="slug" />
+    </div>
+    <input wire:model="slug">
</x-slot>
```

そして、コンテンツ部分のテキストエリアを加えると以下のようになります。

```html
<x-slot name="content">
    <div class="my-4">
        <x-jet-label for="title" value="{{ __('Title') }}" />
        <x-jet-input
            id="title"
            class="block mt-1 w-full"
            type="text"
            wire:model.debounce.800ms="title"
        />
    </div>
    <input wire:model="title" />

    <div class="my-4">
        <x-jet-label for="slug" value="{{ __('Slug') }}" />
        <div class="mt-1 flex rounded-md shadow-sm">
            <span
                class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"
            >
                http://laravel12.localhost/
            </span>
            <input
                type="text"
                id="slug"
                wire:model.debounce.800ms="slug"
                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md"
            />
        </div>
    </div>
    <input wire:model="slug" />

    <div class="my-4">
        <x-jet-label for="content" value="{{ __('Content') }}" />
        <textarea
            wire:model.debounce.800ms="content"
            id="content"
            rows="3"
            class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md "
        ></textarea>
    </div>
    <input wire:model="content" />
</x-slot>
```

`app\Http\Livewire\Pages.php` の編集

```diff
<?php

namespace App\Http\Livewire;

use Livewire\Component;
+use App\Models\Page;

class Pages extends Component
{

    public $modalFormVisible = false;
    public $slug;
    public $title;
    public $content;

+    public function create()
+    {
+        Page::create($this->modelData());
+        $this->modalFormVisible = false;
+    }

    public function createShowModal()
    {
        $this->modalFormVisible = true;
    }

+    public function modelData()
+    {
+        return [
+            'title' => $this->title,
+            'slug' => $this->slug,
+            'content' => $this->content,
+        ];
+    }

    public function render()
    {
        return view('livewire.pages');
    }
}

```

CREATE ボタンをクリックすると、新しいデータを記入するモダールウィンドウが表示されます。必要な情報を入力したら SAVE ボタンをクリックするとデータベースに保存されます。しかし、データを保存した後に、もう一度 CREATE ボタンをクリックすると、以前入力したデータがそのまま残っています。

なので、次に、この残ったデータを空にするメソッドを書いていきます。

`app\Http\Livewire\Pages.php` の編集

```diff
    public function create()
    {
        Page::create($this->modelData());
        $this->modalFormVisible = false;

+      $this->resetVars();
    }

+  public function resetVars()
+  {
+      $this->title = null;
+      $this->slug = null;
+      $this->content = null;
+  }
```

create メソッドに追加の処理を書いて、そして、reseetVars メソッドを新しく追加します。
一度、表示の確認を行ってみましょう。CREATE ボタンをクリックして、情報を入力したあとにＳＡＶＥボタンを押します。
その後に、もう一度、ＣＲＡＴＥボタンをクリックすると、さきほど入力した情報がクリアされているのが分かると思います。

次に、バリデーションの設定をします。

`app\Http\Livewire\Pages.php` の編集

```diff
public function create()
{
+ $this->validate();
    Page::create($this->modelData());
    $this->modalFormVisible = false;

    $this->resetVars();
}

+ public function rules()
+ {
+     return [
+         'title' => 'required',
+         'slug' => ['required', Rule::unique('pages', 'slug')],
+         'content' => 'required',
+     ];
+ }
```

`resources\views\livewire\pages.blade.php` の編集

```diff
        <x-slot name="content">
            <div class="my-4">
                <x-jet-label for="title" value="{{ __('Title') }}" />
                <x-jet-input id="title" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="title" />
+                @error('title')
+                <span class="error">{{ $message }}</span>
+                @enderror
            </div>
            <input wire:model="title">

            <div class="my-4">
                <x-jet-label for="slug" value="{{ __('Slug') }}" />
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        http://laravel12.localhost/
                    </span>
                    <input type="text" id="slug" wire:model.debounce.800ms="slug"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md">
                </div>
+                @error('slug')
+                <span class="error">{{ $message }}</span>
+                @enderror
            </div>
            <input wire:model="slug">

            <div class="my-4">
                <x-jet-label for="content" value="{{ __('Content') }}" />
                <textarea wire:model.debounce.800ms="content" id="content" rows="3"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md "></textarea>
+                @error('content')
+                <span class="error">{{ $message }}</span>
+                @enderror
            </div>
            <input wire:model="content">
        </x-slot>

```

次に Slug の対応した title にするため、`app\Http\Livewire\Pages.php` の編集を行います。

```diff
+    public function updatedTitle($value)
+    {
+        $this->generateSlug($value);
+    }
+
+    private function generateSlug($value)
+    {
+       $process1 = str_replace(' ', '-', $value);
+        $process2 = strtolower($process1);
+       $this->slug = $process2;
+   }
```

---

ここまで Part2

---

ページネーションを作成していきます。

`app\Http\Livewire\Pages.php` の編集を行います。

```diff
+    public function read()
+    {
+        return Page::paginate(5);
+    }

    public function render()
    {
+      return view('livewire.pages', [
+          'data' => $this->read(),
+      ]);
    }
```

read メソッドを新しく追加しましょう。これは、ページネーションのオブジェクトを返します。
次に、`resources\views\livewire\pages.blade.php`に Page 情報の一覧を表示するテーブルを書いていきます。

```html
<div class="p-6">
    <div class="flex items-center justify-end px-4 pb-6 text-right sm:px-4">
        <x-jet-button wire:click="createShowModal">
            {{ __('Create') }}
        </x-jet-button>
    </div>

    {{-- The data table --}}
    <div class="flex flex-col px-4 sm:px-4">
        <div class=" -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div
                class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
            >
                <div
                    class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
                >
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Title
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Link
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Content
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if ($data->count()) @foreach ($data as $item)
                            <tr>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-gray-500"
                                >
                                    {{ $item->title }}
                                </td>
                                <td
                                    class=" px-6 py-4 whitespace-nowrap text-gray-500"
                                >
                                    {{ $item->slug }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-gray-500"
                                >
                                    {{ $item->content }}
                                </td>
                                <td
                                    class=" px-6 py-4 whitespace-nowrap text-gray-500 text-right"
                                >
                                    <x-jet-button
                                        wire:click="updateShowModal({{ $item->id }})"
                                    >
                                        {{ __('Update') }}
                                    </x-jet-button>
                                    <x-jet-danger-button
                                        wire:click="deleteShowModal({{ $item->id }})"
                                    >
                                        {{ __('Delete') }}
                                    </x-jet-danger-button>
                                </td>
                            </tr>
                            @endforeach @else
                            <tr>
                                <td
                                    class=" px-6 py-4 whitespace-nowrap text-gray-500 text-right"
                                    colspan="4"
                                >
                                    No Results Found
                                </td>
                            </tr>
                            @endif

                            <!-- More items... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    ...
</div>
```

`app\Http\Livewire\Pages.php` の編集を行います。

```diff

class Pages extends Component
{

    public $modalFormVisible = false;
+ public $modelId;
    public $slug;
    public $title;
    public $content;

+   public function updateShowModal($id)
+   {
+       $this->modelId = $id;
+       $this->modalFormVisible = true;
+       $this->loadModel();
+   }
+
+   /**
+   * モデルデータを読み込みます。
+   * 対象のmodelIdから、目的のデータを探します。
+   * データを取得したら、livewireの変数に入れ込みます。
+   *
+   * @return void
+   */
+   public function loadModel()
+   {
+       $data = Page::find($this->modelId);
+       // dd($data);
+       $this->title = $data->title;
+       $this->slug = $data->slug;
+       $this->content = $data->content;
+   }
```

\$modelId の変数があるかどうかによっての If 文を作成します。\$modelId がある場合は、ボタンが Update と表示され、wire:click により、update メソッドが実行されます。\$modelId がない場合は、ボタンは Create となり wire:click により create メソッドが実行されます。

`resources\views\livewire\pages.blade.php`を編集します。

```html
<x-slot name="footer">
    <x-jet-secondary-button
        wire:click="$toggle('modalFormVisible')"
        wire:loading.attr="disabled"
    >
        {{ __('Cancel') }}
    </x-jet-secondary-button>

    @if($modelId)
    <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
        {{ __('Update') }}
    </x-jet-button>
    @else
    <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
        {{ __('Create') }}
    </x-jet-button>
    @endif
</x-slot>
```

次に Upload ボタンをクリックしたときに、バリデーションエラーのデータをリセットする処理を記入します。

`app\Http\Livewire\Pages.php` の編集を行います。

```diff
    public function createShowModal()
    {
+      $this->resetValidation();
+      $this->resetVars();
        $this->modalFormVisible = true;
    }

    public function updateShowModal($id)
    {
+      $this->resetValidation();
+      $this->resetVars();
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
    }

    public function resetVars()
    {
+      $this->modelId = null;
        $this->title = null;
        $this->slug = null;
        $this->content = null;
    }

```

それでは、update メソッドを追加してきます。

`app\Http\Livewire\Pages.php` の編集を行います。update メソッドを追加しましょう。

```php
public function update()
{
    // dd('updating');
    $this->validate();
    Page::find($this->modelId)->update($this->modelData());
    $this->modalFormVisible = false;
}
```

次に　ページネーションを作成していくぺこ～。

`app\Http\Livewire\Pages.php` の編集をするぺこ～。

```diff
<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Page;
use Illuminate\Validation\Rule;
+ use Livewire\WithPagination;

class Pages extends Component
{

+    use WithPagination;

    public $modalFormVisible = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;

```

blade ファイルのテーブル部分を編集するぺこ～。`resources\views\livewire\pages.blade.php`を編集します。

```diff
    {{-- The data table --}}
    <div class="flex flex-col px-4 sm:px-4">
        <div class=" -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                <-- table部分 -->

                </div>
            </div>
        </div>
+      <div class="pt-8">
+          {{ $data->links() }}
+      </div>
    </div>
```

ページネーションの設定として、ページをリロードしたらページ情報を初期化するようにするぺこ。
`app\Http\Livewire\Pages.php` に mount メソッドを追加するぺこ。この mount メソッドは livewire のメソッドで、ページが読み込まれる時に必ず実行されるメソッドぺこ。

```php
public function mount()
{
    // ページをリロードした後にページネーションをリセットするぺこ。
    $this->resetPage();
}
```

次に、Slug 部分のリンクを編集するぺこ。

```diff
<tbody class="bg-white divide-y divide-gray-200">

    @if ($data->count())
    @foreach ($data as $item)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
            {{ $item->title }}
        </td>
        <td class=" px-6 py-4 whitespace-nowrap text-gray-500">
+          <a href="{{ URL::to('/' . $item->slug)}}" target="_blank"
+              class="text-blue-500 underline">
+              {{ $item->slug }}
+          </a>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
            {{ $item->content }}
        </td>
        <td class=" px-6 py-4 whitespace-nowrap text-gray-500 text-right">
            <x-jet-button wire:click="updateShowModal({{ $item->id }})">
                {{ __('Update') }}
            </x-jet-button>
            <x-jet-danger-button wire:click="deleteShowModal({{ $item->id }})">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </td>
    </tr>
    @endforeach
    @else
    <tr>
        <td class=" px-6 py-4 whitespace-nowrap text-gray-500 text-right" colspan="4">
            No Results Found
        </td>
    </tr>
    @endif

    <!-- More items... -->
</tbody>
```

次に、Delete ボタンをクリックしたら表示されるモーダルの設定をやっていくぺこ。

`resources\views\livewire\pages.blade.php`を編集するぺこ～。以前記入したモーダルのタグの下に追記するぺこ。

```diff
    {{-- <!-- Modal Form -- > --}}
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <!-- ここは、新規と更新のモーダルぺこ -->
    </x-jet-dialog-modal>


+    <!-- The Delete Modal -->
+    <x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">
+        <x-slot name="title">
+            {{ __('Delete Page') }}
+        </x-slot>
+
+        <x-slot name="content">
+            {{ __('Are you sure you want to delete this page?') }}
+        </x-slot>
+
+        <x-slot name="footer">
+            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
+                {{ __('Cancel') }}
+            </x-jet-secondary-button>
+
+            <x-jet-danger-button class="ml-2" wire:click="delete"
+                wire:loading.attr="disabled">
+                {{ __('Delete') }}
+            </x-jet-danger-button>
+        </x-slot>
+    </x-jet-dialog-modal>
```

そうしたら、delete 用のモーダルを表示する処理と delete メソッドを作成するぺこ。

```diff

class Pages extends Component
{

    use WithPagination;

    public $modalFormVisible = false;
+  public $modalConfirmDeleteVisible = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;

+  public function delete()
+  {
+      Page::destroy($this->modelId);
+      $this->modalConfirmDeleteVisible = false;
+      $this->resetPage();
+  }

+  public function deleteShowModal($id)
+  {
+      $this->modelId = $id;
+      $this->modalConfirmDeleteVisible = true;
+  }
```

`resources\views\livewire\pages.blade.php`の少し編集するぺこ～。

```diff
    public function rules()
    {
        return [
            'title' => 'required',
+          'slug' => ['required', Rule::unique('pages', 'slug')->ignore($this->modelId)],
            'content' => 'required',
        ];
    }
```

slug のページを表示するために、web.php を編集するぺこ。

```diff
<?php

use App\Http\Livewire\Frontpage;
+ use Illuminate\Support\Facades\Route;

...

+ Route::get('/{urlslug}', Frontpage::class);
+ Route::get('/', Frontpage::class);
```

slug 用のビューページのレイアウトを作成するぺこ。`resources\views\layouts\frontpage.blade.php`を作成いて以下を記入するぺこ。

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"
        />

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}" />

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>

    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            <!-- Page Content -->
            <main>{{ $slot }}</main>
        </div>

        @stack('modals') @livewireScripts
    </body>
</html>
```

`app\Http\Livewire\Frontpage.php`を編集するぺこ～。

```diff
class Frontpage extends Component
{
    public function render()
    {
+      return view('livewire.frontpage')->layout('layouts.frontpage');
    }
}
```

`app\Http\Livewire\Frontpage.php`を編集するぺこ～。

```php
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
        $data = Page::where('slug', $urlslug)->first();
        $this->title = $data->title;
        $this->content = $data->content;
    }

    public function render()
    {
        return view('livewire.frontpage')->layout('layouts.frontpage');
    }
}
```

`resources\views\livewire\frontpage.blade.php`を編集するぺこ～。

```html
<div>
    <h1>{{ $title }}</h1>
    <p>{{ $content }}</p>
</div>
```

あたらしいデータベースのカラムを入れるためにマイグレーションファイルを新しく作成するぺこ。

```command
php artisan make:migration add_set_default_pages_to_pages_table --table=pages
```

作成されたマイグレーションファイルに以下を追記するぺこ。

```diff
public function up()
{
    Schema::table('pages', function (Blueprint $table) {
+      $table->boolean('is_default_home')->nullable()->after('id');
+      $table->boolean('is_default_not_found')->nullable()->after('is_default_home');
    });
}
```

そしたらしっかり、migrate コマンドをするぺこ～。

```command
php artisan migrate
```

`app\Http\Livewire\Pages.php`を編集するぺこ。追加したカラムの情報に対応する変数を設定するぺこ

```diff
<?php

class Pages extends Component
{

    use WithPagination;

    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;

+    public $isSetToDefaultHomePage;
+    public $isSetToDefaultNotFoundPage;
```

そして、`resources\views\livewire\pages.blade.php`の content 部分を編集するぺこ。

```diff
        <x-slot name="content">

            ...

+         <div class="mt-4">
+             <label>
+                 <input class="form-checkbox" type="checkbox" value="{{ $isSetToDefaultHomePage }}"
+                     wire:model="isSetToDefaultHomePage">
+                 <span class="ml-2 text-sm text-gray-600">Set as the default home page</span>
+             </label>
+         </div>

+         <div class="mt-4">
+             <label>
+                 <input class="form-checkbox" type="checkbox" value="{{ $isSetToDefaultNotFoundPage }}"
+                     wire:model="isSetToDefaultNotFoundPage">
+                 <span class="ml-2 text-sm text-red-600">Set as the default 404 page</span>
+             </label>
+         </div>
```

Frontpage.php を編集するぺこ。

```diff
    public function retrieveContent($urlslug)
    {
+     // get home page if slug is empty
+     if (empty($urlslug)) {
+         $data = Page::where('is_default_home', true)->first();
+     } else {
+         $data = Page::where('slug', $urlslug)->first();
+         if (!$data) {
+             $data = Page::where('is_default_not_found', true)->first();
+         }
+     }

        // $data = Page::where('slug', $urlslug)->first();
        $this->title = $data->title;
        $this->content = $data->content;
    }
```

Pages.php を編集するペコ。

```diff
<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Page;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;
+ use Illuminate\Support\Str;

class Pages extends Component
{

    use WithPagination;

    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;

    public $isSetToDefaultHomePage;
    public $isSetToDefaultNotFoundPage;

    /**
     * The validation rules
     *
     * @return void
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'slug' => ['required', Rule::unique('pages', 'slug')->ignore($this->modelId)],
            'content' => 'required',
        ];
    }

    public function mount()
    {
        // ページをリロードした後にページネーションをリセットするぺこ。
        $this->resetPage();
    }

    /**
     * Runs everytime the title
     * variable is updated.
     *
     * @param  mixed $value
     * @return void
     */
    public function updatedTitle($value)
    {
+      // $this->generateSlug($value);
+      $this->slug = Str::slug($value);
    }

+  public function updatedIsSetToDefaultHomePage()
+  {
+      $this->isSetToDefaultNotFoundPage = null;
+  }

+  public function updatedIsSetToDefaultNotFoundPage()
+  {
+      $this->isSetToDefaultHomePage = null;
+  }

    public function create()
    {
        $this->validate();
+      $this->unassignDefaultHomePage();
+      $this->unassignDefaultNotFoundPage();
        Page::create($this->modelData());
        $this->modalFormVisible = false;

+      $this->reset();
    }

    /**
     * read
     *
     * @return void
     */
    public function read()
    {
        return Page::paginate(5);
    }


    public function update()
    {
        // dd('updating');
        $this->validate();
+        $this->unassignDefaultHomePage();
+        $this->unassignDefaultNotFoundPage();
        Page::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    public function delete()
    {
        Page::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }

    public function createShowModal()
    {
        $this->resetValidation();
+        $this->reset();
        $this->modalFormVisible = true;
    }

    public function updateShowModal($id)
    {
        $this->resetValidation();
+        $this->reset();
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
    }

    public function deleteShowModal($id)
    {
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }

    /**
     * モデルデータを読み込みます。
     * 対象のmodelIdから、目的のデータを探します。
     * データを取得したら、livewireの変数に入れ込みます。
     *
     * @return void
     */
    public function loadModel()
    {
        $data = Page::find($this->modelId);
        // dd($data);
        $this->title = $data->title;
        $this->slug = $data->slug;
        $this->content = $data->content;

+        $this->isSetToDefaultHomePage = !$data->is_default_home ? null : true;
+        $this->isSetToDefaultNotFoundPage = !$data->is_default_not_found ? null : true;
    }

    public function modelData()
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'is_default_home' => $this->isSetToDefaultHomePage,
            'is_default_not_found' => $this->isSetToDefaultNotFoundPage,
        ];
    }

    // public function resetVars()
    // {
    //     $this->modelId = null;
    //     $this->title = null;
    //     $this->slug = null;
    //     $this->content = null;
    //     $this->isSetToDefaultNotFoundPage = null;
    //     $this->isSetToDefaultHomePage = null;
    // }

    /**
     *
     * Generates a url generateSlug
     * base on the title.
     *
     * @param  mixed $value
     * @return void
     */
    // private function generateSlug($value)
    // {
    //     $process1 = str_replace(' ', '-', $value);
    //     $process2 = strtolower($process1);
    //     $this->slug = $process2;
    // }

+    private function unassignDefaultHomePage()
+    {
+        if ($this->isSetToDefaultHomePage != null) {
+            Page::where('is_default_home', true)->update([
+                'is_default_home' => false,
+            ]);
+        }
+    }
+
+    private function unassignDefaultNotFoundPage()
+    {
+        if ($this->isSetToDefaultNotFoundPage != null) {
+            Page::where('is_default_not_found', true)->update([
+                'is_default_not_found' => false,
+            ]);
+        }
+    }

    public function render()
    {
        return view('livewire.pages', [
            'data' => $this->read(),
        ]);
    }
}
```

pages.blade.php のテーブル部分を少し修正するペコ

```diff
@if ($data->count())
@foreach ($data as $item)
<tr>
<td class="px-6 py-4 whitespace-nowrap text-gray-500">
+    {{ $item->title }}
+    {!! $item->is_default_home ? '<span
+        class="text-green-400 text-xs font-bold">[Default Home]</span>' :
+    '' !!}
+    {!! $item->is_default_not_found ? '<span class="text-red-400 text-xs font-bold">[404
+        page]</span>' :
+    '' !!}

</td>
```
