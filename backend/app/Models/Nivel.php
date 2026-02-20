<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nivel extends Model
{
    protected $table = 'niveis';

    protected $fillable = ['nivel'];

    protected $hidden = ['created_at', 'updated_at'];

    public function profissionais(): HasMany
    {
        return $this->hasMany(Profissional::class);
    }
}
