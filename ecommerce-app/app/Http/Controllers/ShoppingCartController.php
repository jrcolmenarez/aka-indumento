<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shopping_Cart;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use app\Helpers\JwtAuth;

class ShoppingCartController extends Controller
{

    public function __construct() {

        $this->middleware('api.auth', ['except' => ['show']]);

    }

    public function index(Request $request)
    {
        $user = $this->getIdentity($request);
        $shop_cart = Shopping_Cart::all()->where('user_id',$user->sub)->load('products');
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'shop_cart' => $shop_cart
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
            if(!empty($params_array)){
                $validate = Validator::make($params_array, [
                    'product_id' => 'required',
                    'amount' => 'required'
                ]);
                if($validate->fails()){
                    $data = array(
                        'code'    =>  404,
                        'status'  => 'error',
                        'mesage'    => 'Falta direccion para guardar Datos'
                      );
                }
                $shop_cart = new Shopping_Cart();
                $shop_cart->user_id=$user->sub;

                $shop_cart->product_id=$params_array['product_id'];

                $shop_cart->amount= $params_array['amount'];
                $shop_cart->save();
                $data = array(
                    'code'    =>  200,
                    'status'  => 'success',
                    'subcategory'    => $shop_cart
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
                'product_id' => 'required',
                'amount' => 'required'
            ]);
                if($validate->fails()){
                    $data['errors'] = $validate->errors();
                    return response()->json($data, $data['code']);
                }
            unset($params_array['id']);
            unset($params_array['user_id']);
            unset($params_array['created_at']);
                $shop_cart = new Shopping_Cart();
                $shop_cart = Shopping_Cart::where('id',$id)
                ->where('user_id',$user->sub)
                ->first();

                if(!empty($shop_cart) && is_object($shop_cart)){
                    $shop_cart->update($params_array);
                    $data = array (
                        'code'      => 200,
                        'status'    => 'success',
                        'shop_cart'      => $shop_cart,
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

    }
    public function empty (Request $request){
        $user = $this->getIdentity($request);
        $shop_cart = new Shopping_Cart();
        $shop_cart = Shopping_Cart::where('user_id',$user->sub)->delete();
        if(!empty($shop_cart)){
            //$shop_cart->delete();
            $data = array(
            'code'    =>  200,
            'status'  => 'success',
            'post'    => $shop_cart
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
