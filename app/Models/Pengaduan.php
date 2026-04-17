<?php

// Lokasi: app/Models/Pengaduan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduans';
    protected $fillable = [
        'user_id', 
        'kategori_id', 
        'lokasi', 
        'keterangan', 
        'foto', 
        'status', 
        'feedback',
        'feedback_foto'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    
    public function ulasans()
    {
        return $this->hasMany(Ulasan::class);
    }
}
