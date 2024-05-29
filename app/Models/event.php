<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_kepala_keluarga',
        'tahun_acara',
        'total_pendapatan',
        'biaya_perkk'
    ];

    public function detailEvents()
    {
        return $this->hasMany(DetailEvent::class);
    }
}
