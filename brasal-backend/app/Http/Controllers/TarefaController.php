<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class TarefaController extends Controller
{
    // crud
    public function get_all()
    {
        // Get api com o status e responsavel ja com nome ao inves de id (tratamento feito nessa funcao)
        // essa api é utilizada para montar a tabela no frontend.
        //select all from table tarefa where id = id 
        $user = JWTAuth::user();
        // id do user:
        $user_id = $user['id'];

        $response = DB::table('tarefa')->where('responsavel_id', $user_id)->get();
        for ($i = 0; $i < count($response); $i++) {
            // set string as status 
            //status row:
            $status = DB::table('status')->where('id', $response[$i]->status_id)->get()->first();
            $response[$i]->status_id = $status->name;
        }
        return $response;
    }
    public function create(Request $request)
    {


        //validacao

        $this->validate($request, [
            'titulo' => 'required|string',
            'descricao' => 'required|string',
            'status_id' => 'required|exists:status,id'
        ]);
        try {
            $data = new Tarefa;
            $data->titulo = $request->get('titulo');
            $data->descricao = $request->get('descricao');
            $data->status_id = $request->get('status_id');
            // id do user:
            $data->responsavel_id = JWTAuth::user()['id'];
            $data->save();

            return response()->json(['tarefa' => $data, 'message' => 'Tarefa Cadastrada!'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Falha na criação de tarefa!'], 409);
        }

    }
    public function read($id)
    {
        // TODO: checar se o usuario atual possui o mesmo ID do da tarefa;
        // checar no read, delete e edit


        // lembrando que essa rota esta protegidaa pela jwt token
        $tarefa = Tarefa::find($id);
        return response()->json($tarefa);

    }
    public function update(Request $request, $id)
    {
        // update ja debugado.

        $tarefa = Tarefa::find($id);
        // solucionar no backend caso o post so envie uma das informaçoes para atualizar.
        // por exemplo, se no body da requisicao so tiver o titulo (somente o titulo para editar), só precisamos mudar esse valor
        if ($request->get('titulo')) {
            $tarefa->titulo = $request->get('titulo');
        }
        if ($request->get('descricao')) {
            $tarefa->descricao = $request->get('descricao');
        }
        if ($request->get('status_id')) {
            $tarefa->status_id = $request->get('status_id');
        }
        $tarefa->save();
        return response()->json(['Tarefa: ' => $tarefa, 'message' => 'Tarefa editada com sucesso!']);


    }
    public function delete($id)
    {
        // delete part
        $tarefa = Tarefa::find($id);
        $tarefa->delete();
        return response()->json(['message' => 'Tarefa deletada com sucesso'], 200);

    }


}