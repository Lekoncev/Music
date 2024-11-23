<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Album extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'artist', 'description', 'cover'];
}
