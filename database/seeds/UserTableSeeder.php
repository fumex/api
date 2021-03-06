<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name'=>'Gregori',
            'apellidos'=>'perez',
            'id_documento'=>'1',
            'numero_documento'=>'12345678',
            'direccion'=>'calle',
            'telefono'=>'940808080',
            'nacimiento'=>'1994/12/12',
            'rol'=>'admin',
            'email'=>'admin@gmail.com',
            'estado'=>true,
            'strd'=>'1305',
            'imagen'=>'2.png',
            'superad'=>true
            
        ]);
    }
}
