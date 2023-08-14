<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Subcategory;
use app\Helpers\JwtAuth;

class SubcategoryController extends Controller
{

    public function __construct() {

        $this->middleware('api.auth', ['except' => ['index']]);

    }

    public function index()
    {

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


    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        $user = $this->getIdentity($request);
        if($user->role == "ROLE_ADMIN"){
            if(!empty($params_array)){
                $validate = Validator::make($params_array, [
                    'name'      => 'required',
                    'description' => 'required',
                    'category_id' => 'required'
                ]);
                if($validate->fails()){
                    $data = array(
                        'code'    =>  404,
                        'status'  => 'error',
                        'mesage'    => 'Falta Nombre o Descripcion para guardar Datos'
                      );
                }
                $subcategory = new Subcategory;
                $subcategory->name=$params_array['name'];
                $subcategory->description=$params_array['description'];
                $subcategory->category_id= $params_array['category_id'];
                $subcategory->save();
                $data = array(
                    'code'    =>  200,
                    'status'  => 'success',
                    'subcategory'    => $subcategory
                  );
            }
        }else{
            $data = array(
                'code'    =>  404,
                'status'  => 'error',
                'mesage'    => 'NO es ADMIN sin acceso'
              );
        }
        return response()->json($data, $data['code']);
    }


    public function show($id, Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        $user = $this->getIdentity($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    private function getIdentity(Request $request){
        $jwtauth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtauth->checkToken($token, true);
        return $user;
    }
}
