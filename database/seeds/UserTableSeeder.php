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
            'telefono'=>'12312312',
            'nacimiento'=>'1994/12/12',
            'rol'=>'admin',
            'email'=>'admin@gmail.com',
            'estado'=>'habilitado',
        ]);
    }
}
