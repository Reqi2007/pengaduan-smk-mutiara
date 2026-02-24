<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;
    protected $fillable = ['pengaduan_id', 'user_id', 'rating', 'komentar'];

    public function user() { return $this->belongsTo(User::class); }
    public function pengaduan() { return $this->belongsTo(Pengaduan::class); }
}