<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ mix('css/app.css')}}">
</head>

<body>

    <div style=width:900px; class="container max-w-full mx-auto pt-4">

        <h1 class="text-2xl font-bold mb-4">Hello Blog</h1>

        <div class="mb-6">
            <a href="/hello/create"
                class="bg-indigo-500 tracking-wide text-white px-6 py-2 inline-block mb-6 rounded shadow-lg hover:shadow mt-2">
                New hello Blog
            </a>
        </div>

        @foreach ($hellos as $hello)
        <article class=mb-2>
            <a href="/hello/{{ $hello->id }}/edit" class="text-xl">{{ $hello->title }}</a>
            <p class="text-sm text-gray-600">{{ $hello->content}}</p>
        </article>
        <hr class="mt-2">
        @endforeach
    </div>

</body>

</html>