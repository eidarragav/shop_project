<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ExpressController extends Controller
{
    public function create_sales(Request $request){
        $user = Auth::user();

        $product_id = $request->product_id;
        $quantity = $request->quantity;
        
        //Consultamos si el producto existe y su stock
        $stock_res = Http::withHeaders([
            "Authorization" => env("TOKEN_APIS")
        ])->get(env("CHECK_PRODUCT_STOCK_ENDPOINT")."/".$product_id);

        //Si no lo encuentra
        if($stock_res->status() == 404){
            return response()->json(["mensaje" => "producto no encontrado"]);
        }

        //Si no es suficiente el stock
        if($stock_res["stock"] < $quantity){
            return response()->json(["mensaje"=> "stock no disponible"]);
        }


        //Si ninguna se cumple, puede crear la venta con seguridad
        $response = Http::withHeaders([
            "Authorization" => env("TOKEN_APIS")
        ])->post(env("SALES_ENDPOINT"), [
            "user_id" =>$user->id,
            "quantity" => $request->quantity,
            "total" => $request->total,
            "product_id" => $request->product_id
        ]);

        $stockUpdate = Http::withHeaders([
            "Authorization" => env("TOKEN_APIS")
        ])->post(env("UPDATE_PRODUCT_STOCK_ENDPOINT")."/".$product_id, [
            "quantity" => $request->quantity    
        ]);

        return [
            "status" => $response->status(),
            "body" => $response->body()
        ];
    }

    public function index_sales(){
        $response = Http::withHeaders([
            "Authorization" => env("TOKEN_APIS")
        ])->get(env("SALES_ENDPOINT"));

        return [
            "status" => $response->status(),
            "body" => $response->body()
        ];
    }

    public function update_sales(Request $request, $id){
        $user = Auth::user();

        $response = Http::withHeaders([
            "Authorization" => env("TOKEN_APIS")
        ])->put(env("SALES_ENDPOINT")."/".$id,[
            "user_id" => $user->id,
            "quantity" => $request->quantity,
            "total" => $request->total,
            "product_id" => $request->product_id
        ]);

        return [
            "status" => $response->status(),
            "body" => $response->body()
        ];
    }

    public function delete_sales($id){
        $response = Http::withHeaders([
            "Authorization" => env("TOKEN_APIS")
        ])->delete(env("SALES_ENDPOINT")."/".$id,);

        return [
            "status" => $response->status(),
            "body" => $response->body()
        ];
    }

    public function my_sales(){
        $user = Auth::user();

        $response = Http::withHeaders([
            "Authorization" => env("TOKEN_APIS")
        ])->post(env("MY_SALES_ENDPOINT"), [
            "user_id" => $user->id
        ]);

        return [
            "reponse" => $response->status(),
            "body" => $response->body()
        ];
    }
}
