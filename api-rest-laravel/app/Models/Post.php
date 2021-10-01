<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    /*protected $fillable = [
        'title',
        'content',
        'user_id',
        'category_id',
    ];*/

    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
