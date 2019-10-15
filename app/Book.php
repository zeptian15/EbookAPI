<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // Table yang bisa diisi
    protected $fillable = ['judul', 'file', 'deskripsi', 'gambar', 'isFavourite'];
}
