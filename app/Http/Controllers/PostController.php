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
        
        $posts = Post::latest()->paginate(4);
        return view('posts.index', compact('posts'));
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
        $post = Post::find($id);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
        public function search(Request $request)
{
        $prefs = config('pref');
        $categories = config('category');
        $query = Post::query();

        //$request->input()で検索時に入力した項目を取得します。
        $search1 = $request->input('title');
        $search2 = $request->input('pref');
        $search3 = $request->input('category');

         // タイトル入力フォームで入力した文字列を含むカラムを取得します
        if ($search1!=null) {
            $query->where('title', 'like', '%'.$search1.'%')->get();
        }

         // プルダウンメニューで指定なし以外を選択した場合、$query->whereで選択した都道府県と一致するカラムを取得します
        if ($search2!=null) {
            $query->where('pref_id', $search2)->get();
        }

         // プルダウンメニューで指定なし以外を選択した場合、$query->whereで選択した好きなカテゴリと一致するカラムを取得します
        // if ($request->has('category'))
        if ($search3!=null) {
            $query->where('category_id', $search3)->get();
        }

        //ニュースを1ページにつき5件ずつ表示させます
        $data = $query->paginate(5);

        return view('posts.index',[
            'prefs' => $prefs,'categories' => $categories,'data' => $data
        ]);
    }
}
