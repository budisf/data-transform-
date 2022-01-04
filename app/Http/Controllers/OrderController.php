<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Order;


class OrderController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
   
        $data = Transaction::with('customer')
        ->with(array('order' => function($query) {
            $query->with('product');
        }))->paginate(request()->all());

        return response()->json(['status' => 200, 'message' =>'success', 'data' => $data]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $user =  json_decode(auth()->user());
        $dataTransaction = [
            "transaction_time" => $request->transaction_time,
            "id_user"          =>   $user->id
        ];

        $transaction = Transaction::create($dataTransaction);
        $id_transaction = $transaction->id;

        foreach ($request->products as $product){
            $data = [
                "id_product"        => $product['id_product'],
                "qty"               => $product['qty'],
                "id_transaction"    => $transaction->id
            ];
            $order = Order::create($data);
        }
        return response()->json(['status' => 200, 'message' =>'order created success']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      
        $data = Transaction::with('customer')
        ->with(array('order' => function($query) {
            $query->with('product');
        }))->find($request->id);
        
        return response()->json(['status' => 200, 'message' =>'success', 'data' => $data]);
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
    public function update(Request $request)
    {
        
        $dataTransaction = [
            "transaction_time" => $request->transaction_time,
        ];

        $transaction = Transaction::find($request->id_transaction)
        ->update($dataTransaction);

        $deleteOrder = Order::where('id_transaction', $request->id_transaction)->delete();
      
        foreach ($request->products as $product){
            $data = [
                "id_product"        => $product['id_product'],
                "qty"               => $product['qty'],
                "id_transaction"    => $request->id_transaction
            ];
            $order = Order::create($data);
        }

        $dataUpdate = Transaction::with(array('order' => function($query) {
            $query->with('product');
        }))->find($request->id_transaction);

        return response()->json(['status' => 200, 'message' =>'order updated success', 'data' => $dataUpdate]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data = Transaction::find($request->id_transaction)->delete(); 
        $deleteOrder = Order::where('id_transaction', $request->id_transaction)->delete();
        return response()->json(['status' => 200, 'message' =>'deleted success']);
    }
}
