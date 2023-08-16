<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use app\Helpers\JwtAuth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    public function __construct() {

        $this->middleware('api.auth', ['except' => ['index','show','getImage']]);

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
                'price'         => 'required',
                'stock'         => 'required',
                'subcategory_id'=> 'required'
            ]);
                if($validate->fails()){
                    $data['errors'] = $validate->errors();
                    return response()->json($data, $data['code']);
                }
            unset($params_array['id']);
            unset($params_array['created_at']);

            $user = $this->getIdentity($request);
            if ($user->role == 'ROLE_ADMIN'){
                $produc = Product::find($id);

                if(!empty($produc) && is_object($produc)){
                    $produc->update($params_array);
                    $data = array (
                        'code'      => 200,
                        'status'    => 'success',
                        'Post'      => $produc,
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
        $produc = new Product();
        $user = $this->getIdentity($request);
        $produc = Product::find($id);
        if(!empty($produc)){
            if ($user->role == 'ROLE_ADMIN'){
                $produc->delete();
                $data = array(
                'code'    =>  200,
                'status'  => 'success',
                'post'    => $produc
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

    public function upload(Request $request){
        //recoger datos de la peticion
        $image = $request->file('file0');
        //validar si llega una imagen
        $validate = validator::make($request->all(),[
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);
        //gurdamos la imagen
        if(!$image || $validate->fails()){

            $data = array (
                'code' => 200,
                'status' => 'error',
                'message' => 'Error al cargar imagen'
             );
            }else {
           $image_name = time().$image->getClientOriginalName();
            Storage::disk('products')->put($image_name, File::get($image));
           // Storage::disk('user')->put($image_name,);
            $data = array (
            'code' => 200,
            'status' => 'success',
            'image' => $image_name
         );}
        return response()->json($data, $data['code']);
    }


    public function getImage($filename) {
        $isset = Storage::disk('products')->exists($filename);

        if($isset){
        //conseguir la imagen
        $file = Storage::disk('products')->get($filename);
        //devolver la imagen
        return new response($file, 200);
        }else {
            $data = [
                'code'  => 404,
                'status'=> 'error',
                'message'=> 'La imagen no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }

    private function getIdentity(Request $request){
        $jwtauth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtauth->checkToken($token, true);
        return $user;
    }
}
