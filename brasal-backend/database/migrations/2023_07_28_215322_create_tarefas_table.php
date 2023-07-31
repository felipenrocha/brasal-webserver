<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarefasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarefa', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->timestamp('data_criacao')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('responsavel_id');
            $table->foreign('status_id')->references('id')->on('status');
            $table->foreign('responsavel_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarefas');
    }
}
