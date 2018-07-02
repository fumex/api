<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table='empresas';
    protected $fillable=['nombre','ruc','direccion','departamento','provincia','distrito','telefono','web','email','logo']; 
}
