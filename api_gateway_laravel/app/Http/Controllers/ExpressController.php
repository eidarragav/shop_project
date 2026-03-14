<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExpressController extends Controller
{
    public function create_sales(Request $request){
        $response = Http::withHeaders([
            "Authorization" => env("TOKEN_APIS")
        ])->post(env("SALES_ENDPOINT"), [
            "quantity" => $request->quantity,
            "total" => $request->total,
            "product_id" => $request->product_id
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
        $response = Http::withHeaders([
            "Authorization" => env("TOKEN_APIS")
        ])->put(env("SALES_ENDPOINT")."/".$id,[
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
}
