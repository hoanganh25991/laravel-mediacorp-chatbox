<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ApiToken;
use App\Messenger;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Validator;

class RegisterController extends Controller
{
    use ApiResponse;
    public function regis(Request $req){
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'device_uuid' => 'required',
        ]);
        
        if($validator->fails()){
            return $this->res($req->all(), $validator->messages(), 422);
        }

        $userName = $req->get('name');

        $messenger = Messenger::where('name', $userName)->first();
        if(empty($messenger)){
            $messenger = new Messenger();
        }
        
        $messenger->fill($req->all());
        $messenger->save();

        $token = ApiToken::get();
        
        return $this->res(compact('token'));
    }
}
