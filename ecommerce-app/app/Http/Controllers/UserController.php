<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use app\Helpers\JwtAuth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;


class UserController extends Controller
{

    public function register(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        //validamos vacio
        if(!empty($params_array)){
            $validate = validator::make($params_array, [
               'name'           => 'required',
               'surname'        => 'required',
               'email'          => 'required|email|unique:users',
               'password'       => 'required'
            ]);
            if($validate->fails()){
                $data = array(
                    'status' => 'Error',
                    'code'   => 404,
                    'message' => 'Error validando algun dato para rregistro',
                    'errors'  => $validate->errors()
                    );
                }else{
                    $pwd = hash('sha256',$params->password);
                    $user = new User();
                        $user->name = $params_array['name'];
                        $user->surname = $params_array['surname'];
                        $user->email = $params_array['email'];
                        $user->password = $pwd;
                        $user->address = $params_array['address'];
                        $user->phone = $params_array['phone'];
                        $user->gender = $params_array['gender'];
                        $user->role_user = $params_array['role_user'];
                        //guardar el usuario
                        $user->save();
                        $data = array(
                            'status' => 'success',
                            'code'   => 200,
                            'message' => 'Usuario se ha registrado correctamente',
                            'user' => $user
                            );
                    }
        }else{
            $data = array(
                'status' => 'error',
                'code'   => 400,
                'message' => 'El Json llego vacio al controlador',
                );
        }

        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {

        $jwtAuth = new \App\Helpers\JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = validator::make($params_array, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        if($validate->fails()){
            $data = array (
                'status'        => 'error',
                'code'          => 400,
                'message'       => 'Falta algun dato para iniciar sesion UserController',
                'error'         => $validate->errors()
            );
            $signup = $data;
        }else{
            //cifrar contraseña
            $pwd = hash('sha256',$params->password);
            $signup = $jwtAuth->signup($params->email, $pwd);
            if(!empty($params->gettoken)){
                $signup= $jwtAuth->signup($params->email, $pwd, true);
            }
        }

        return response()->json($signup, 200);
    }

    public function update(Request $request) {

        //comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checktoken = $jwtAuth->checkToken($token);
        // recoger los datos por post

        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if($checktoken && !empty($params_array)){

            //actualizar datos del usuario


            //sacar el ID (sub) del usuario validado
            $user = $jwtAuth->checkToken($token, true);

            //validar datos
            $validate = Validator::make($params_array, [
                'name'      => 'required|alpha',
                'surname'   => 'required|alpha',
                'email'     => 'required|email|unique:users,'.$user->sub
            ]);
            //Ciframos la contraseña
            $pwd = hash('sha256',$params_array['password']);
            $params_array['password']=$pwd;
            //quitar datos que no necesite
            unset ($params_array['id']);
            unset ($params_array['created_at']);
            unset ($params_array['updated_at']);
            unset ($params_array['remember_token']);

            //actualizar BBDD
            $user_update = User::where ('id',$user->sub)->update($params_array);
            //devolver array con resultado
            $data = array (
                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'Changes' => $params_array
            );
        }else{
            $data = array (
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no identificado User Controller'
            );
             //echo "<h1>LOGIN INCORRECTO</h1>";
        }
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
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al cargar imagen',
                'error'    => $validate->errors()
             );


        }else {
           $image_name = time().$image->getClientOriginalName();
            Storage::disk('users')->put($image_name, File::get($image));
           // Storage::disk('user')->put($image_name,);
            $data = array (
            'code' => 200,
            'status' => 'success',
            'image' => $image_name
         );

        }

        return response()->json($data, $data['code']);
    }
    public function getImage($filename) {
        $isset = Storage::disk('users')->exists($filename);

        if($isset){
        //conseguir la imagen
        $file = Storage::disk('users')->get($filename);
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

        public function detail($id) {
            //buscar forma de que si da null encontrar ese error tarea
            $user = User::find($id);
            if(is_object($user)){
                $data = array (
                    'code' => 200,
                    'status' => 'success',
                    'user'  => $user
                );
            }else {
                $data = array(
                    'code'  => 404,
                    'status'=>'error',
                    'message'=>'no se encontraron usuarios'
                );
            }
            return response()->json($data, $data['code']);
        }

        public function storeUser(){
            $user = User::all();
            if(is_object($user)){
                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'user' => $user
                );
            }else {
                $data = array (
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'NO HAY USUARIOS REGISTRADOS'
                );
            }
            return response()->json($data, $data['code']);
        }

        public function destroy($id){
            $user = User::find($id);
             if(is_object($user)){
                 //si no esta vacio lo eliminamos
                 $user->delete();
                 $data = array (
                     'code' => 200,
                     'status' => 'success',
                     'message' => 'Se ha eliminado Usuario',
                     'plan'    => $user
                 );
             }else {
                 $data = array (
                     'code'  => 400,
                     'status'=>'error',
                     'message'=>'No se encontro registro para ELIMINAR'
                 );
             }
             return response()->json($data, $data['code']);
         }

}
