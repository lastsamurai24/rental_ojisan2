<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Post::query();

        if ($request->has('pref_id') && $request->pref_id != '') {
            $query->where('pref_id', $request->pref_id);
        }

        $posts = $query->latest()->paginate(4);
        $prefs = config('pref'); // この行を追加

        return view('posts.index', compact('posts', 'prefs'));
    }
}
