<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlaskController extends Controller
{
    public function index_products(){
        $response = Http::withHeaders([
            'Authorization' => env("TOKEN_APIS")
        ])->get(env("PRODUCTS_ENDPOINT"));

        return[
            'status' => $response->status(),
            'body' => $response->body()
        ];
    }

    public function create_products(Request $request){
        $response = Http::withHeaders([
            'Authorization' => env("TOKEN_APIS")
        ])->post(env("PRODUCTS_ENDPOINT"), [
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock,
            "category" => $request->category
        ]);

        return [
            "status" => $response->status(),
            "body" => $response->body()
        ];
    }

    public function update_products($id, Request $request){
        $response = Http::withHeaders([
            "Authorization" => env("TOKEN_APIS")
        ])->put(env("PRODUCTS_ENDPOINT")."/".$id, [
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock,
            "category" => $request->category
        ]);

        return [
            "status" => $response->status(),
            "body" => $response->body()
        ];
    }
}
