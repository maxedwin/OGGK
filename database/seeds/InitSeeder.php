<?php

use Illuminate\Database\Seeder;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'inserts_db.sql';
        DB::unprepared(file_get_contents($path));
    }
}
