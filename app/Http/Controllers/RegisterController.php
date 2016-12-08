<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ApiToken;
use App\Messenger;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Validator;
use App\Conversation;

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

        $term_condition = "By downloading or using the app, these terms will automatically apply to you – please read them carefully before using the app.

We are offering you this app to use for your own personal use without cost, but the app itself, and all trade marks, copyright, database rights and other intellectual property rights related to it, still belong to Mediacorp. In particular, all content including images and videos in the app are the property of Mediacorp and you may not reproduce such content in any form.

You should be aware that you cannot send it on to anyone else, and you’re not allowed to copy, translate or modify the app, any part of the app, or our trademarks in any way. You also may not attempt to extract the source code of the app.

Mediacorp is committed to ensuring that the app is as useful and efficient as possible. For that reason, we reserve the right to make changes to the app or to charge for its services, at any time and for any reason. You agree that we may access, store and use any information that you provide in accordance with the terms of the Privacy Policy (insert link).

The app stores and processes messages that you have submitted to us so that you can receive responses from our virtual boyfriends. It’s your responsibility to keep your phone and access to the app secure and to use the app in a responsible manner.We also recommend that you do not share any personal data in the App/chat. We do not accept any liability for any injuries, damage or losses you may incur as a result of your use of the app.

We recommend that you do not jailbreak or root your phone. It could make your phone vulnerable to malware/viruses/malicious programs, compromise your phone’s security features and it could mean that the Love Preparatory app won’t work properly or at all. 

We offer the app to you as-is and we are not responsible for the app not working at full functionality. If you’re using the app outside of an area with Wi-Fi, you should remember that your terms of agreement with your mobile network provider will still apply. As a result, you may be charged by your mobile provider for the cost of data for the duration of the connection while accessing the app, or other third party charges.

In using the app, you’re accepting responsibility for any such charges, including roaming data charges if you use the app outside of your home territory (i.e. region or country) without turning off data roaming.

We may update the app at any point in time. The app is currently available on Android and iOS – the requirements for both systems may change, and you’ll need to download the updates if you want to keep using the app.
 
Mediacorp does not promise that it will always update the app so that it is relevant to you and/or works with the iOS/Android version that you have installed on your device.
 
We may also wish to stop providing the app, and may terminate use of it at any time without giving notice of termination to you. Unless we tell you otherwise, upon any termination, (a) the rights and licenses granted to you in these terms will end; (b) you must stop using the app, and (if needed) delete it from your device.";

//        $conversations = Conversation::where('name', 'not like', '%default%')->get();
        
        return $this->res(compact('token', 'term_condition'));
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
