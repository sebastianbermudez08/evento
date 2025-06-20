<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscrito extends Model
{
    use HasFactory;

    protected $table = 'inscritos';

    protected $fillable = [
        'evento_id',
        'nombre_completo',
        'numero_documento',
        'edad',
        'genero',
        'correo',
        'telefono',
        'profesion',
        'empresa',
        'fecha_registro',
        'comprobante_token',
    ];

    // Relación con el evento
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
