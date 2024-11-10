<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'id' => 1,
        	'idempresa' => 1,
        	'idsucursal' => 1,
        	'idrol' => 1,
	        'name' => "admin",
	        'lastname'=>"admin",
	        'dni' => 11111111,
	        'email' => 'admin@solucionesoggk.com',
	        'password' => bcrypt('123456'),
	        'activated' => 1,
	        'remember_token' => 'T4exNCXfcYiINRGtugGRSmgf6zqZvJNa4modHw7CmRGPyO2r7O45Oh1nYUFX',
	        'created_at' => '2020-06-06 10:05:26',
	        'updated_at' => '2020-09-24 03:20:56',
	        'telefono' => 111111111,
	        'direccion' => 'los admins',
	        'f_nac' => '2020-12-12',
	        'sexo' => 'M',
	        'f_entrada' => '2020-12-12',
	        'puesto' => 1,
	        'tienda_user' => 0,
	        'culqi_id' => 0
       	]);

       	$this->call(InitSeeder::class);

    }
}
