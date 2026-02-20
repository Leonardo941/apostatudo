<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profissional extends Model
{
    protected $table = 'profissionais';

    protected $fillable = [
        'nivel_id',
        'nome',
        'sexo',
        'data_nascimento',
        'hobby',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date:Y-m-d',
        ];
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class);
    }
}
