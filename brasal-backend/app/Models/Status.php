<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// usar php artisan make:migration php artisan make:migration create_status_table --create=status para migration da tabela, se nao criada
class Status extends Model
{

    /*
      Tabela nao terá crud e contera os tipos de status que uma tarefa pode estar. (utlizado para mostrar noção de rlacionamento e seeds somente)
    */
    // tabela status
    protected $table = 'status';
    protected $primaryKey = 'status_id';
    public $timestamps = false; // nao utilizar nesse caso por ser semeada no banco de dados

    protected $fillable = [
        'name',
    ];
}