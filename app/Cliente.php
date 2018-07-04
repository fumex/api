<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table='clientes';
    protected $fillable=['nombre','apellido','id_documento','nro_documento','direccion','email','subtotal','telefono','telefono2','estado','id_user']; 
}
