<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use app\Helpers\JwtAuth;
use App\Models\User;

class ProductController extends Controller
{

    public function __construct() {

        $this->middleware('api.auth', ['except' => ['index','show']]);

    }

    public function index()
    {
        $product = Product::all()->load('user');
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'product' => $product
        ]);
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
        if ($user->role == 'ROLE_ADMIN'){
            if (!empty($params_array)){

                $validate = Validator::make($params_array,[
                    'name'     => 'required',
                    'description'   => 'required',
                    'price'         => 'required',
                    'stock'         => 'required',
                    'subcategory_id'=> 'required'
                ]);

                if($validate->fails()){
                    $data = array(
                        'code'  => 400,
                        'status'=> 'Error',
                        'message'=> 'Json esta vacio o faltan datos');
                }else{
                    $produc = new Product();
                    $produc->name = $params_array['name'];
                    $produc->description = $params_array['description'];
                    $produc->price = $params_array['price'];
                    $produc->stock = $params_array['stock'];
                    $produc->subcategory_id = $params_array['subcategory_id'];
                    $produc->user_id = $user->sub;
                    $produc->save();
                    $data = [
                        'code' => 200,
                        'status' =>'success',
                        'post' => $produc
                    ];}
            }else{
                $data = [
                    'code' => 400,
                    'status' =>'error',
                    'message' => 'Falta informacion para guardar post, Json Vacio o Params Array'
                ];}
        }else{
            $data = [
                'code' => 400,
                'status' =>'error',
                'message' => 'Usuario no administrador'
            ];}
        return response()->json($data, $data['code']);

    }


    public function show($id)
    {
        $product = Product::find($id)->load('user');
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'product' => $product
        ]);
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

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
