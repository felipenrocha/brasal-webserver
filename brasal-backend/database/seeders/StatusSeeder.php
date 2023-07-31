<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// rodar a seed: php artisan db:seed --class=StatusSeeder


class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status')->insert([
            'name' => 'Concluido'
        ]);
        DB::table('status')->insert([
            'name' => 'Em Andamento'
        ]);
        DB::table('status')->insert([
            'name' => 'Adiada'
        ]);
        DB::table('status')->insert([
            'name' => 'Cancelada'
        ]);
       
       
       
    }
}
