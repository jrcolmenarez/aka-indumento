<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use app\Helpers\JwtAuth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{

   public function __construct() {

        $this->middleware('api.auth', ['except' => ['index','show','getImage']]);

    }
    public function index()
    {
        $categories = Category::all()->load('subcategory');
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'categories' => $categories
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
                    'description'   => 'required'
                ]);

                if($validate->fails()){
                    $data = array(
                        'code'  => 400,
                        'status'=> 'Error',
                        'message'=> 'No se han enviado datos para actualizar NOMBRE o Descripcion');
                }else{
                    $category = new Category;
                    $category->name = $params_array['name'];
                    $category->description = $params_array['description'];
                    $category->image = $params_array['image'];
                    $category->save();
                    $data = [
                        'code' => 200,
                        'status' =>'success',
                        'post' => $category
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
        $category = Category::find($id)->load('subcategory');
        if(is_object($category)){
        $data = [
            'code'      => 200,
            'status'    => 'success',
            'category'      => $category
        ];
        }else{
            $data = [
            'code'      => 400,
            'status'    => 'Error',
            'mesage'      => 'Error Id no existe '
        ];}
        return response()->json($data, $data['code']);
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
                'description'   => 'required'
            ]);
                if($validate->fails()){
                    $data['errors'] = $validate->errors();
                    return response()->json($data, $data['code']);
                }
            unset($params_array['id']);
            unset($params_array['created_at']);

            $user = $this->getIdentity($request);
            if ($user->role == 'ROLE_ADMIN'){
                $category = Category::find($id);

                if(!empty($category) && is_object($category)){
                    $category->update($params_array);
                    $data = array (
                        'code'      => 200,
                        'status'    => 'success',
                        'Post'      => $category,
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
        $category = new category();
        $user = $this->getIdentity($request);
        $category = Category::find($id);
        if(!empty($category)){
            if ($user->role == 'ROLE_ADMIN'){
                $category->delete();
                $data = array(
                'code'    =>  200,
                'status'  => 'success',
                'post'    => $category
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
            'file0' => 'required|image|mimes:jpeg,png,gif,jpg,bmp,cgm,gif,jpe,svg,psd,pic,webp|min:10|max:3000'
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
