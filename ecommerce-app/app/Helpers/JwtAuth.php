<?php
namespace app\Helpers;
use Firebase\JWT\JWT;
//para hacer consultas a la bd
//use Illuminate\Support\Facades\DB;
//modelo
use App\Models\User;

class JwtAuth{

    public $key;

    public function __construct() {
        $this->key ='esto_es_una_clave_secreta';
    }

    public function signup($email, $password, $getTokken = null){
        //buscar si el usuario existe con el metodo Where
        $user = User::where([
            'email' => $email,
            'password' => $password
        ])->first(); // devuelve el primero encontrado
        //comprobar si son correctos (objetos)
        $signup = false;
        if(is_object($user)){
            $signup = true;
        }
        //Generar el token con los datos del usuario validado
        if ($signup){

            $token = array (
                'sub'   =>      $user->id,
                'email' =>      $user->email,
                'name'  =>      $user->name,
                'surname'=>     $user->surname,
                'image' =>      $user->image,
                'role'  =>      $user->role_user,
                'description' => $user->description,
                'iat'   =>      time(),
                'exp'   =>      time() + (7 * 24 * 60 *60)
            );
            $jwt = JWT::encode($token, $this->key,'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            //devolver los datos decodificados o el token, en funcion del parametro
            if(is_null($getTokken)){
                $data=$jwt;
            }else{
               $data=$decoded;
            }

        }else{
            $data = array (
                'status'    =>  'error',
                'code'   => 404,
                'message'   =>  'Login Incorrecto'
            );
        }

        return $data;
    }

    public function checkToken ($jwt, $getidentity = false){

        $auth = false;

        try{
            $jwt = str_replace('"','',$jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        }catch(\UnexpectedValueException $e){
            $auth=false;
        }catch(\DomainException $e){
            $auth = false;
        }
        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth=true;
        }
        if ($getidentity){
           return $decoded;
        }
       return $auth;
    }

}
?>
