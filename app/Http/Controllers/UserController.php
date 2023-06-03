<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function userSelectOptionsIndex (Request $request)
    {
        $users = User::select('name AS label', 'institutional_id AS value')->get();

        return response()->json([
            'users' => $users
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recordsPerPage = 10;

        $query = User::query();

        $exactFilters = ['institutional_id', 'license', 'role'];
        $likeFilters = ['email', 'name'];

        foreach ($exactFilters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request[$filter]);
            }
        }

        foreach ($likeFilters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'ILIKE', '%'. $request[$filter] . '%');
            }
        }
        
        return $query->orderBy('updated_at', 'desc')->paginate($recordsPerPage);
    }

    public function switchUserLicense(Request $request)
    {
        if (!is_numeric($request->id)) {
            return response()->json([
                'message' => 'Identificador do usuário inválido.'
            ], 400);
        }
        $user = User::findOrFail($request->id);


        if ($user->role == 'admin') {
            return response()->json([
                'message' => 'Não é permitido alterar a licença de um administrador.'
            ], 400);
        }

        $license = $user->license;

        $user->license = $license == 'active' ? 'inactive' : 'active';

        $str = $user->license == 'active' ? 'ativado' : 'desativado';

        $user->save();

        return response()->json([
            'message' => 'Usuário ' . $str . ' com sucesso.'
        ], 200);
    }
}
