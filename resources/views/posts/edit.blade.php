<x-app-layout>
    <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-white shadow-md rounded-lg">
        <h2 class="text-center text-lg font-bold pt-6 tracking-widest">編集</h2>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-2 rounded" role="alert">
                <p>
                    <b>{{ count($errors) }}件のエラーがあります。</b>
                </p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data"
            class="rounded pt-3 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="title">タイトル</label>
                <input type="text" name="title" class="form-input w-full" required placeholder="タイトル" value="{{ old('title', $post->title) }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="body">本文</label>
                <textarea name="body" rows="10" class="form-textarea w-full" required>{{ old('body', $post->body) }}</textarea>
            </div>
            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm mb-2" for="pref">都道府県</label>
                    <select class="form-select w-full" id="pref" name="pref" required>
                        <option value="" hidden>選択してください</option>
                        @foreach($prefs as $index => $name)
                            <option value="{{ $index }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm mb-2" for="category">カテゴリー</label>
                    <select class="form-select w-full" id="category" name="category" required>
                        <option value="" hidden>選択してください</option>
                        @foreach($categories as $index => $name)
                            <option value="{{ $index }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            <input type="datetime-local">

            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="image">画像</label>
                <img src="{{ $post->image_url }}" alt="" class="mb-4 md:w-2/5 sm:auto">
                <input type="file" name="image" class="form-file">
            </div>
            <input type="submit" value="更新" class="w-full btn btn-primary">
        </form>
    </div>
</x-app-layout>
