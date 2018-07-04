<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    protected $table='monedas';
    protected $fillable=['moneda','tasa','estado','id_user'];
}
