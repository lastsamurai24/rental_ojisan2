<x-app-layout>
    <div class="container max-w-7xl mx-auto px-4 md:px-12 pb-3 mt-3">
        <!-- 都道府県選択フォーム -->
        <form method="GET" action="{{ route('search') }}" class="mb-4">
            <select name="pref_id" onchange="this.form.submit()">
                <option value="" hidden>都道府県▼</option>
                @foreach ($prefs as $index => $name)
                    <option value="{{ $index }}">{{ $name }}</option>
                @endforeach
            </select>
            <button type="submit" style="background-color: rgb(249, 249, 255); color: rgb(7, 1, 1); padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">検索</button>

        </form>
    <div class="container max-w-7xl mx-auto px-4 md:px-12 pb-3 mt-3">
        <div class="flex flex-wrap -mx-1 lg:-mx-4 mb-4">
            @foreach ($posts as $post)
                <article class="w-full px-4 md:w-1/2 text-xl text-gray-800 leading-normal">
                    <a href="{{ route('posts.show', $post) }}">
                        <h2
                            class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl break-words">
                            {{ $post->title }}</h2>
                        <h3>{{ $post->user->name }}</h3>
                        <p class="text-sm mb-2 md:text-base font-normal text-gray-600">
                            <span
                                class="text-red-400 font-bold">{{ date('Y-m-d H:i:s', strtotime('-1 day')) < $post->created_at ? 'NEW' : '' }}</span>
                            {{ $post->created_at }}
                        </p>
                        <img class="w-full mb-2" src="{{ $post->image_url }}" alt="">
                        <p class="text-gray-700 text-base">{{ Str::limit($post->body, 50) }}</p>
                    </a>
                </article>
            @endforeach
        </div>
        {{ $posts->links() }}
    </div>

    <hr class="my-4">

    
</x-app-layout>
