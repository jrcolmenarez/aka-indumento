<?php

namespace App\Http\Controllers;

use App\Models\Order_Detail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use app\Helpers\JwtAuth;

class OrderDetailController extends Controller
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

    }


    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        $user = $this->getIdentity($request);
            if(!empty($params_array)){
                $validate = Validator::make($params_array, [
                    'order_id' => 'required',
                    'product_id' => 'required',
                    'amount' => 'required',
                    'price_unit' => 'required',
                    'total' => 'required'
                ]);
                if($validate->fails()){
                    $data = array(
                        'code'    =>  404,
                        'status'  => 'error',
                        'mesage'    => 'Falta direccion para guardar Datos'
                      );
                }
                $detail = new Order_Detail();
                $detail->order_id= $params_array['order_id'];
                $detail->product_id= $params_array['product_id'];
                $detail->amount = $params_array['amount'];
                $detail->price_unit = $params_array['price_unit'];
                $detail->total = $params_array['total'];
                $detail->save();
                $data = array(
                    'code'    =>  200,
                    'status'  => 'success',
                    'subcategory'    => $detail
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


    public function show($id)
    {
        //
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
        $detail = new Order_Detail();
        $detail = Order_Detail::find($id);
        if(!empty($detail)){
            $detail->delete();
            $data = array(
            'code'    =>  200,
            'status'  => 'success',
            'post'    => $detail
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
}
