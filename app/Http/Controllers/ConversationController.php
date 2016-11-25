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
            'chatbox_name' => 'required',
            'user_text' => 'required'
        ]);

        if($validator->fails()){
            return $this->res($req->all(), $validator->messages(), 422);
        }

        $chatboxName = $req->get('chatbox_name');
        $conversation = $conversations->where('name', $chatboxName)->first();
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
                if($answer1['Response 1'] == '-'
                    && $answer1['Response 2'] == '-'
                    && $answer1['Response 3'] == '-')
                {
                    return self::NO_ANSWER;
                }

                // Play random
                $x = random_int(1, 3);
                $responseX = "Response {$x}";

                return $answer1[$responseX];
            }catch(\Exception $e){
                return isset($answer1['Response 1']) ? $answer1['Response 1'] : "";
            }
        }

        return $answer;
    }
}
