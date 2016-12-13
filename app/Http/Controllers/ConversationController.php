<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use Validator;
use App\Traits\ApiResponse;
use App\Traits\ApiUtils;

class ConversationController extends Controller {
    use ApiResponse;
    use ApiUtils;

    const NO_ANSWER = "I hear you, but no anwser";
    const MIS_CONTEXT = "Sorry, i miss your context";
    const CHATBOX_MAP = [
        "2" => "boyfriend1.xlsx",
        "1" => "boyfriend2.xlsx",
        "0" => "boyfriend4.xlsx",
        "3" => "boyfriend3.xlsx"
    ];

    public function script(Request $req){
        /**
         * 1. Only get out main boyfriend chatbox
         * 2. Boyfriend default is for fallback
         */
        $conversations = Conversation::where('name', 'not like', '%default%')->get();

        /**
         * Handle GET
         */
        if($req->method() == 'GET'){
            return view('conversation.script')->with(compact('conversations'));
        }

        /**
         * Handle NOT GET, implicit for POST
         */
        $validator = Validator::make($req->all(), [
            'chatbox_id' => 'required',
            'user_text' => 'required'
        ]);

        if($validator->fails()){
            return $this->res($req->all(), $validator->messages(), 422);
        }

        $chatboxId = $req->get('chatbox_id');
//        $chatboxNames = $conversations->pluck('name');
        $chatboxNames = self::CHATBOX_MAP;
        if(empty($chatboxNames[$chatboxId])){
            $msg = 'chatbox_id wrong';
            return $this->res($req->all(), $msg, 422);
        }
        $chatboxName = $chatboxNames[$chatboxId];
        $conversation = $conversations->where('name', $chatboxNames[$chatboxId])->first();
//        $conversation = $conversations->where('name', $chatboxName)->first();
        if(empty($conversation)){
            $msg = 'I\'m sorry. But, no chatbox added. Ask admin for help.';
            return $this->res($req->all(), $msg, 422);
        }
        /**
         * Extract conversation on content
         */
        $conversation = json_decode($conversation->content, true);
        // Convert to collection of laravel for easy manipulate
        $conversation = collect($conversation);

        $userText = $req->get('user_text');
        /**
         * If user only reply emoji|emoticon
         * "NO RESPONSE"
         */
        $removeEmojiUserText = $this->removeEmoji($userText);
        if(empty($this->removeSpace($removeEmojiUserText))){
            return $this->res(['response' => self::NO_ANSWER]);
        }
        // Remove some special character
        $userText = $this->removeSomeSC($userText);
        // Support singular form, when user type in plural
        $userTextSingularForm = $this->transformWordsToSingular($userText);

        // Find out answer for origin user_text
        $answersUserText =
            $conversation
                ->filter(function($val) use($userText){
                    $pattern = $val['Keyword'];
                    return preg_match($pattern, $userText);
                })
                // After filter, array has "HOLE", just get values as array
                ->values();

        $answersUserTextSingularForm =
            $conversation
                ->filter(function($val) use($userTextSingularForm){
                    $pattern = $val['Keyword'];
                    return preg_match($pattern, $userTextSingularForm);
                })
                // After filter, array has "HOLE", just get values as array
                ->values();

        $answers = $answersUserText->merge($answersUserTextSingularForm);

        $answer = $this->getMostRelevanceAnswer($answers);

        /**
         * In case, no answer found out
         * Try on fallback of boyfriend default
         */
        if(empty($answer)){
            $tmp = explode('.', $chatboxName);
            $chatboxNameWithoutExt = $tmp[0];
            $pattern = "%{$chatboxNameWithoutExt}%default%";
            $defaultConversation = Conversation::where('name', 'like', $pattern)->first();

            if(!empty($defaultConversation)){
                // Parse out content
                $defaultConversation = json_decode($defaultConversation->content, true);
                // Use laravel collection
                $defaultConversation = collect($defaultConversation);

                if($defaultConversation->count() > 0){
                    $answer = $defaultConversation->random(1);
                }
            }
        }

        /**
         * Seldomly, but still empty, notify out
         */
        if(empty($answer))
            $answer = self::MIS_CONTEXT;

        return response(['response' => $answer], 200, ['Content-Type' => 'application/json']);
    }

    private function getMostRelevanceAnswer($answers){
        $answer = "";

        if($answers->count() > 0){
            /**
             * Just choose the first one $answer[0] as most relavanace
             */
            $answer1 = $answers[0];
            /**
             * 1. Response 1, 2, 3 may not exist!!! Ask the script writer why @@
             */
            try{
                /**
                 * When res 1, 2, 3 == '-', "NO_ANSWER"
                 */
//                if($answer1['Response 1'] == '-'
//                    && $answer1['Response 2'] == '-'
//                    && $answer1['Response 3'] == '-')
//                {
//                    return self::NO_ANSWER;
//                }

                // Play random
                $x = random_int(1, 3);
                $responseX = "Response {$x}";

                if($answer1[$responseX] == '-')
                    return self::NO_ANSWER;

                return $answer1[$responseX];
            }catch(\Exception $e){
                return isset($answer1['Response 1']) ? $answer1['Response 1'] : "";
            }
        }

        return $answer;
    }

    public function termCondition(){
        $termCondition = "By downloading or using the app, these terms will automatically apply to you – please read them carefully before using the app.

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
        return response(['term_condition' => $termCondition], 200, ['Content-Type' => 'application/json']);;
    }
}
