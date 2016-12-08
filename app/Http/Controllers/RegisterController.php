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
        /**
         * Support header params
         */
//        $name = $req->header('name');
        /**
         * Remove check username & uuid
         * This version no need
         */
//        $validator = Validator::make($req->all(), [
//            'name' => 'required',
//            'device_uuid' => 'required',
//        ]);
//        
//        if($validator->fails()){
//            return $this->res($req->all(), $validator->messages(), 422);
//        }
//
//        $userName = $req->get('name');
//
//        $messenger = Messenger::where('name', $userName)->first();
//
//        if(empty($messenger)){
//            /**
//             * Totally new messenger
//             */
//            $messenger = new Messenger();
//        }else{
//            /**
//             * Registered messenger
//             * Check if this register is the old one
//             * Or the new ONE take same name > ERR
//             */
//            $deviceUuid = $req->get('device_uuid');
//
//            if($messenger->device_uuid != $deviceUuid){
//                return $this->res($req->all(),
//                    'This user name already exist. If you want to change device, please update info', 422);
//            }
//        }
//        
//        $messenger->fill($req->all());
//        $messenger->save();

        $token = ApiToken::get();
        
        return $this->res(compact('token'));
    }

    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'device_uuid' => 'required',
            'new_device_uuid' => 'required'
        ]);

        if($validator->fails()){
            return $this->res($req->all(), $validator->messages(), 422);
        }

        $userName = $req->get('name');

        $messenger = Messenger::where('name', $userName)->first();
        
        if(empty($messenger)){
            $messenger = new Messenger();
        }else{
            $deviceUuid = $req->get('device_uuid');
            
            if($messenger->device_uuid != $deviceUuid){
                return $this->res($req->all(), 'User name already exist', 422);
            }

            /**
             * Accepted user, update his info
             */
            $req->merge(['device_uuid' => $req->get('new_device_uuid')]);
//            $req->replace(['device_uuid' => 3]);
        }
        
        $messenger->fill($req->all());
        $messenger->save();
        
        $token = ApiToken::get();
        
        return $this->res(compact('token'));
    }
}
