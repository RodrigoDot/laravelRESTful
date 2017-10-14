<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BanksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //adiciona um limitado a quantidade de registros retornados
        $limit = $request->all()['limit'] ?? 15;

        //ordena os registros
        $order = $request->all()['order'] ?? null;
        // transforma o valor de $order em um array se ele for diferente de null
        if($order !== null) {
          $order = explode(',', $order);
        }
        // define os valores do array
        $order[0] = $order[0] ?? 'id';
        $order[1] = $order[1] ?? 'asc';

        // define um campo especifico a ser pesquisado
        $where = $request->all()['where'] ?? [];

        //define uma expressao a ser pesquisada
        $like = $request->all()['like'] ?? null;
        if($like) {
          $like = explode(',', $like);
          $like[1] = '%' . $like[1] . '%';
        }

        $data = \App\Bank::orderBy($order[0], $order[1])
          ->where(function($query) use ($like) {
            if($like) {
              return $query->where($like[0], 'like', $like[1]);
            }
            return $query;
          })
          ->where($where)
          ->paginate($limit);
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $data = \App\Bank::create($request->all());

      return response()->json($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = \App\Bank::findOrFail($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
