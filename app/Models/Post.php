<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function image_url()
    {
        return Storage::url('images/posts/' . $this->image);
    }
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }
    public function getImagePathAttribute()
    {

        return 'images/posts/' . $this->image;
    }
    public function getPrefNameAttribute()
    {
        return config('pref.' . $this->pref_id);
    }

    public function getCategoryNameAttribute()
    {
        return config('category.' . $this->category_id);
    }
}
