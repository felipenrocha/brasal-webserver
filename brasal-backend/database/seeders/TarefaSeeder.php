<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// rodar a seed: php artisan db:seed --class=TarefaSeeder


class TarefaSeeder extends Seeder
{
    // obs.: como nao existe seeder para usuario, é necessario criá-lo no frontend e  checar seu id para o seed funcionar.
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tarefa')->insert([
            'titulo' => 'Compras',
            'descricao' => 'Comprar itens essenciais para casa.',
            'status_id' => 1,
            'data_tarefa' => '22/11/1998 10:45',
            'responsavel_id' => 1, // O ID do usuário responsável (deve existir na tabela "users")
        ]);
        DB::table('tarefa')->insert([
            'titulo' => 'Estudar para a prova',
            'descricao' => 'Revisar os tópicos importantes para a prova.',
            'status_id' => 1,
            'data_tarefa' => '22/11/1998 10:45',

            // O ID do status "Em andamento"
            'responsavel_id' => 1, // O ID do usuário responsável (deve existir na tabela "users")
        ]);
        DB::table('tarefa')->insert([
            'titulo' => 'Enviar relatório',
            'data_tarefa' =>  '22/11/1998 10:45',
            'descricao' => 'Preparar e enviar o relatório mensal para o cliente.',
            'status_id' => 2,
            // O ID do status "Concluído"
            'responsavel_id' => 1, // O ID do usuário responsável (deve existir na tabela "users")
        ]);






    }
}