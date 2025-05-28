<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenreModel extends Model
{
    protected $table = 'genres';


    protected $fillable = [
        'name',
        'description',
    ];
}
