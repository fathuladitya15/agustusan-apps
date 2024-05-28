<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEvent extends Model
{
    use HasFactory;

    protected $fillable = ['household_id', 'event_id', 'minggu_ke', 'jumlah_bayar', 'status_bayar'];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
