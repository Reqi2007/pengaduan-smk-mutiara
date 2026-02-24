<?php

// Lokasi: app/Models/Kategori.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }
}