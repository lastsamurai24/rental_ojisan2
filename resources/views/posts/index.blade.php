<x-app-layout>
    <div class="container max-w-7xl mx-auto px-4 md:px-12 pb-3 mt-3">
        <!-- 通知メッセージ -->
        @if (session('success'))
            <div class="alert alert-success" style="color: purple;">
                {{ session('success') }}
            </div>
        @endif
        <!-- 都道府県選択フォーム -->
        <form method="GET" action="{{ route('search') }}" class="mb-4 bg-gray-200 p-4 rounded-lg">
            <select name="pref_id" onchange="this.form.submit()" class="p-2 rounded">
                <option value="" hidden>都道府県▼</option>
                @foreach ($prefs as $index => $name)
                    <option value="{{ $index }}">{{ $name }}</option>
                @endforeach
            </select>
            
            <button type="submit" class="ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">検索</button>
        </form>

        <!-- 投稿一覧 -->
        <div class="container max-w-7xl mx-auto px-4 md:px-12 pb-3 mt-3">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($posts as $post)
                    <article class="bg-white rounded-lg shadow p-4">
                        <a href="{{ route('posts.show', $post) }}">
                            <h2 class="font-bold text-xl md:text-2xl">{{ $post->title }}</h2>
                            <h3 class="text-md text-gray-600">{{ $post->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $post->created_at }}</p>
                            <img class="w-full mb-2" src="{{ $post->image_url }}" alt="">
                            <p class="text-gray-700">{{ Str::limit($post->body, 50) }}</p>
                        </a>
                    </article>
                @endforeach
            </div>
            {{ $posts->links() }}
        </div>

        <hr class="my-4">
    </div>
</x-app-layout>
