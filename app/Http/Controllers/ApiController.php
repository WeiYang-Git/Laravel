<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Api;

class ApiController extends Controller
{
    public function getData(){
        return response()->json([
            'head' => 'OK',
            'response' => API::all(),
        ]);
    }

    public function getDataValueByKey(Request $req){
        $api = API::select('value')->where('key_value', $req->key)->first();
        
        $timestamp = '';
        if(!empty($req->timestamp)){
            $timestamp = date('g:i a', strtotime($req->timestamp));
        }

        if(!empty($api->value)){
            return response()->json([
                'head' => 'OK',
                'response' => 'Response: '.$api->value.' '.$timestamp,
            ]);
        }else{
            return response()->json([
                'head' => 'ERROR',
                'response' => 'Unable to find the key.',
            ]);
        }
    }

    public function addData(Request $req){
        $api = new API;
        
        if(empty($req->key) || empty($req->value)){
            return response()->json([
                'head' => 'ERROR',
                'response' => 'Key or Value given is empty.',
            ]);
        }

        if (API::where('key_value', '=', $req->key)->exists()) { // Key Found
            $result = API::where('key_value', '=', $req->key)->update(['value' => $req->value]);
        }else{
            $api->key_value = $req->key;
            $api->value = $req->value;
            $result = $api->save();
        }

        if($result){
            return response()->json([
                'head' => 'OK',
                'response' => 'Data has been successfully saved.',
            ]);
        }else{
            return response()->json([
                'head' => 'ERROR',
                'response' => 'Operation add failed.',
            ]);
        }
    }

    public function updateData(Request $req){
        $api = API::find($req->id);
        
        if(empty($req->key) || empty($req->key)){
            return response()->json([
                'head' => 'ERROR',
                'response' => 'Key or Value given cannot be empty.',
            ]);
        }

        $result = '';
        if($api){
            $api->key_value = $req->key;
            $api->value = $req->value;
            $result = $api->save();
        }

        if($result){
            return response()->json([
                'head' => 'OK',
                'response' => 'Data has been successfully updated.',
            ]);
        }else{
            return response()->json([
                'head' => 'ERROR',
                'response' => 'Operation update failed. ID not found.'
            ]);
        }
    }
}
