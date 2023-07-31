<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Support\Facades\DB;



class StatusController extends Controller
{
    public function read()
    {
        // sem autenticacao (qualquer um pode acessar)

        // so teremos funcao read para poder relacionar o id de cada status com seu nome no frontend.
        $status_list = DB::table('status')->get();
        return response()->json($status_list);

    }


}