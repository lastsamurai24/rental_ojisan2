@section('content')
<div class="row">
        <div class="col-sm-4">
            <div class="text-center my-4">
                <h3 class="brown border p-2">投稿検索</h3>
            </div>
        <form method="GET" action="{{ route('search') }}" class="p-5" enctype="multipart/form-data">
                    @csrf
            <div class="select-tag">
                <div class="dn-title">
                    <input id="title" type="text" placeholder="タイトル" name="title" >
                <div class="st-area">
                    <p>タグを選択してください</p>
                    <div class="st-flex">

                            <select class="form-control" id="pref" name="pref" >
                                @foreach($prefs as $index => $name)
                                    <option value="" hidden>都道府県▼</option>
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="category">
                            <select class="form-control" id="category" name="category" >
                                @foreach($categories as $index => $name)
                                    <option value="" hidden>カテゴリー▼</option>
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
             </div>
        <div class="col-sm-8">
            <div class="text-center my-4">
                <h3 class="brown p-2">投稿一覧</h3>
            </div>

            <div class="container">
                <!--検索ボタンが押された時に表示されます-->
                @if(!empty($data))
                    <div class="my-2 p-0">
                        <div class="row  border-bottom text-center">
                            <div class="col-sm-4">
                                <p>タイトル</p>
                            </div>
                            <div class="col-sm-4">
                                <p>カテゴリ</p>
                            </div>
                            <div class="col-sm-4">
                                <p>都道府県</p>
                            </div>
                        </div>
                        <!-- 検索条件に一致したユーザを表示します -->
                        @foreach($data as $post)
                                <div class="row py-2 border-bottom text-center">
                                    <div class="col-sm-4">
                                        <a href="">{{ $post->title }}</a>
                                    </div>
                                    <div class="col-sm-4">
                                        {{ $post->CategoryName }}
                                    </div>
                                    <div class="col-sm-4">
                                        {{ $post->prefName }}
                                    </div>
                                </div>
                        @endforeach
                    </div>
                    {{ $data->appends(request()->input())->render('pagination::bootstrap-4') }}
                @endif
            </div>
                <div id="ulb-frame" class="form-group mb-0 mt-3">
                    <button type="submit" id="uploadBtn" class="btn btn-block btn-secondary">
                        検索する
                    </button>
                </div>
        </form>
        </div>
    </div>
@endsection
