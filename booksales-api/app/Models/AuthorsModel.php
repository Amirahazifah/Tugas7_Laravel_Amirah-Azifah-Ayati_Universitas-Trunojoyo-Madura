<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorsModel extends Model
{
    protected $table = 'authors';

    protected $fillable = [
        'name',
        'photo',
        'bio',
       
    ];
}
