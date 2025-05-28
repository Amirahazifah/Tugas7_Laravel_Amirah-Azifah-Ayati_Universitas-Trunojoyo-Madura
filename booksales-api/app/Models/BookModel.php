<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookModel extends Model
{
    protected $table = 'books';


    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'cover_photo',
        'genre_id',
        'author_id'
    ];

    public function genre()
    {
        return $this->belongsTo(GenreModel::class);
    }

    public function author()
    {
        return $this->belongsTo(AuthorsModel::class);
    }
}
