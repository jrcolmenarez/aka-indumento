<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use app\Helpers\JwtAuth;

class OrderController extends Controller
{

    public function __construct() {

        $this->middleware('api.auth', ['except' => ['index']]);

    }

    public function index()
    {
        //
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
            if(!empty($params_array)){
                $validate = Validator::make($params_array, [
                    'address_order' => 'required'
                ]);
                if($validate->fails()){
                    $data = array(
                        'code'    =>  404,
                        'status'  => 'error',
                        'mesage'    => 'Falta direccion para guardar Datos'
                      );
                }
                $order = new Order();
                $order->user_id=$user->sub;
                $order->state='pending';
                $order->address_order= $params_array['address_order'];
                $order->paymen_methods = $params_array['paymen_methods'];
                $order->save();
                $data = array(
                    'code'    =>  200,
                    'status'  => 'success',
                    'subcategory'    => $order
                  );
            }else{
                $data = array(
                    'code'    =>  404,
                    'status'  => 'error',
                    'mesage'    => 'Jason esta vacio..!'
                  );
            }

        return response()->json($data, $data['code']);
    }

    public function show($id, Request $request)
    {
        $user = $this->getIdentity($request);
        $order = Order::find($id);
        echo "este es el id del logado ".$user->sub;
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'order' => $order
        ]);
    }


    public function edit($id, Request $request)
    {

    }


    public function update(Request $request, $id)
    {
        $json=$request->input('json', null);
        $params_array=json_decode($json, true);
        $user = $this->getIdentity($request);
        $data = array(
                'code'  => 400,
                'status'=> 'Error',
                'message'=> 'No se han enviado datos para actualizar'
        );
        if(!empty($params_array)){
            $validate = Validator::make($params_array,[
                'id'     => 'required',
                'address_order'   => 'required'
            ]);
                if($validate->fails()){
                    $data['errors'] = $validate->errors();
                    return response()->json($data, $data['code']);
                }
            unset($params_array['id']);
            unset($params_array['user_id']);
            unset($params_array['created_at']);

                $order = Order::find($id)->where('user_id',$user->sub);

                if(!empty($order) && is_object($order)){
                    $order->update($params_array);
                    $data = array (
                        'code'      => 200,
                        'status'    => 'success',
                        'order'      => $order,
                        'changes'   => $params_array
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
        $order = new Order();
        $user = $this->getIdentity($request);
        $order = Order::where('id',$id)
                            ->where('user_id',$user->sub)
                            ->first();
        if(!empty($order)){
            $order->delete();
            $data = array(
            'code'    =>  200,
            'status'  => 'success',
            'post'    => $order
            );
        }else{
            $data = array(
               'code'    =>  404,
               'status'  => 'error',
               'mesage'    => 'Orden no existe o No pertenece a usuario actual'
              // 'order'      => $order
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
