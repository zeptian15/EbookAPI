<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // Table yang bisa diisi
    protected $fillable = ['judul_buku', 'link_buku'];
}
