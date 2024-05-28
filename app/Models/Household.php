<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'head_name',
        'address',
        'phone',
        'member_count',
    ];

    public function detailEvents()
    {
        return $this->hasMany(DetailEvent::class);
    }
}
