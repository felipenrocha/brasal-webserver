<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


// usar php artisan make:migration create_tarefas_table --create=tarefas para migration da tabela, se nao criada


// tabela com crud implementado.



class Tarefa extends Model
{
    protected $table = 'tarefa';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'descricao',
        'data_tarefa',
        'data_criacao',
        'status_id',
        'responsavel_id',
        // Outros atributos da tabela "tarefas", se houver
    ];

    public function responsavel()
    {
        // relacionamento da tabela do id de status
        return $this->belongsTo('App\User', 'responsavel_id', 'user_id');
    }


    public function status()
    {
        return $this->belongsTo('App\Status', 'status_id', 'status_id');
    }

}