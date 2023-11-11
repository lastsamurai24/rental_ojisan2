<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use App\Models\Post;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prefs = config('pref'); // pref.php から都道府県データを取得
        $posts = Post::latest()->paginate(4);

        return view('posts.index', compact('posts', 'prefs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prefs = config('pref');
        $categories = config('category');
        return view('posts.create', compact('prefs', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);



        // 現在認証されているユーザーを取得
        $user = Auth::user();

        // ユーザーがおじさんでなければ投稿を拒否
        if (!$user->ojisan) {
            return redirect()->back()->withErrors(['error' => 'おじさんのみ投稿可能です。']);
        }

        // 投稿を作成
        $post = new Post($request->all());
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = $user->id;
        $file = $request->file('image');
        $post->category_id = $request->category;
        $post->pref_id = $request->pref;
        $post->category_id = $request->category;
        $post->pref_id = $request->pref;
        $post->image = date('YmdHis') . '_' . $file->getClientOriginalName();
        $post->save();
        // トランザクション開始
        DB::beginTransaction();
        try {
            // 登録
            $post->save();

            // 画像アップロード
            if (!Storage::putFileAs('images/posts', $file, $post->image)) {

                // 例外を投げてロールバックさせる
                throw new \Exception('画像ファイルの保存に失敗しました。');
            }

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('posts.show', $post);

        return redirect()->route('posts.index')->with('success', '記事が投稿されました。');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id); // データベースから投稿を取得
        $categories = config('category'); // category.phpからカテゴリ配列を取得
        $categoryName = $categories[$post->category_id] ?? '未定義のカテゴリー'; // カテゴリーIDに基づいて名前を取得
        $prefs = config('pref'); // category.phpからカテゴリ配列を取得
        $prefName = $prefs[$post->pref_id] ?? '未定義のカテゴリー'; // カテゴリーIDに基づいて名前を取得
        // ビューにデータを渡す
        return view('posts.show', compact('post', 'categoryName', 'prefName'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        $categories = config('category');
        $prefs = config('pref');

        return view('posts.edit', compact('post', 'categories', 'prefs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            // 他のバリデーションルール
        ]);

        // 画像がアップロードされた場合の処理
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $post->image = date('YmdHis') . '_' . $file->getClientOriginalName();
            Storage::putFileAs('images/posts', $file, $post->image);
        }

        // 更新可能なフィールドのみを更新
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'category_id' => $request->category,
            'pref_id' => $request->pref,
            // 他のフィールド
        ]);

        return redirect()->route('posts.show', $post)->with('success', '紹介ページが更新されました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        $post->delete();

        return redirect()->route('posts.index')->with('success', '紹介ページを削除しました');
    }
}
