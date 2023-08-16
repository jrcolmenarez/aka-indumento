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

        $this->middleware('api.auth', ['except' => ['index','show']]);

    }

    public function index()
    {

    }


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


    public function show($id)
    {
        $subcategory = Subcategory::find($id);
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'product' => $subcategory
        ]);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $json=$request->input('json', null);
        $params_array=json_decode($json, true);
        $data = array(
                'code'  => 400,
                'status'=> 'Error',
                'message'=> 'No se han enviado datos para actualizar'
        );
        if(!empty($params_array)){
            $validate = Validator::make($params_array,[
                'name'     => 'required',
                'description'   => 'required',
                'category_id'   => 'required'
            ]);
                if($validate->fails()){
                    $data['errors'] = $validate->errors();
                    return response()->json($data, $data['code']);
                }
            unset($params_array['id']);
            unset($params_array['created_at']);

            $user = $this->getIdentity($request);
            if ($user->role == 'ROLE_ADMIN'){
                $subcat = Subcategory::find($id);

                if(!empty($subcat) && is_object($subcat)){
                    $subcat->update($params_array);
                    $data = array (
                        'code'      => 200,
                        'status'    => 'success',
                        'Post'      => $subcat,
                        'changes'   => $params_array
                        );}
            }else{
                $data = array(
                    'code'  => 400,
                    'status'=> 'Error',
                    'message'=> 'NO ES EL ADMIN'
            );}
        }else{
            $data = array(
                'code'  => 400,
                'status'=> 'Error',
                'message'=> 'JSON esta VACIO'
        );}
        return response()->json($data, $data['code']);
    }


    public function destroy($id, Request $request)
    {
        $subcat = new Subcategory();
        $user = $this->getIdentity($request);
        $subcat = Subcategory::find($id);
        if(!empty($subcat)){
            if ($user->role == 'ROLE_ADMIN'){
                $subcat->delete();
                $data = array(
                'code'    =>  200,
                'status'  => 'success',
                'post'    => $subcat
                );
            }else{
                $data = array(
                    'code'    =>  404,
                    'status'  => 'error',
                    'mesage'    => 'No tiene acceso de administrador'
                  );}
        }else{
            $data = array(
               'code'    =>  404,
               'status'  => 'error',
               'mesage'    => 'categoria no existe'
             );}
        return response()->json($data, $data['code']);
    }

    private function getIdentity(Request $request){
        $jwtauth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtauth->checkToken($token, true);
        return $user;
    }
}
