<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

trait ApiControllerTrait {

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

      $data = $this->model::orderBy($order[0], $order[1])
        ->where(function($query) use ($like) {
          if($like) {
            return $query->where($like[0], 'like', $like[1]);
          }
          return $query;
        })
        ->where($where)
        ->with($this->relationships())
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

    $data = $this->model::create($request->all());

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
    $data = $this->model->with($this->relationships())
      ->findOrFail($id);

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

    $data = $this->model::findOrFail($id);

    $data->update($request->all());

    return response()->json($data);

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      $this->model::destroy($id);

      return response()->json(['status' => 'resgistro deletado']);

  }

  public function relationships() {
    if(isset($this->relationships)) {
      return $this->relationships;
    }else {
      return [];
    }
  }

}
