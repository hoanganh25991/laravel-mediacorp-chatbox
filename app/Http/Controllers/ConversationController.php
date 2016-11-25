<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use DB;
use Validator;
//use \PDO;
use App\Traits\ApiResponse;
use App\Traits\ApiUtils;

class ConversationController extends Controller {
    use ApiResponse;
    use ApiUtils;

    const NO_ANSWER = "I hear you, but no anwser";
    
    public function script(Request $req){
        //only get out main boyfriend chatbox
        //default is for fallback
        $conversations = Conversation::where('name', 'not like', '%default%')->get();

        if($req->method() == 'GET'){
            return view('conversation.script')->with(compact('conversations'));
        }

        if($req->method() == 'POST'){
            $validator = Validator::make($req->all(), [
                'chatbox_name' => 'required',
                'user_text' => 'required'
            ]);
            
            if($validator->fails()){
                return $this->res($req->all(), $validator->messages(), 422);
            }
            
            $chatboxName = $req->get('chatbox_name');
            //find out conversation base on chatboxName
            //store into collection
            $conversation = $conversations->where('name', $chatboxName)->first();
//            $conversation = $conversations->where('name', 'asdfasdf')->first();
            // Check no $conversation added
            if(empty($conversation)){
                $answer = 'I\'m sorry. But, no chatbox added. Ask admin for help.';
                return response(['response' => $answer], 200, ['Content-Type' => 'application/json']);
            }

            $conversation = json_decode($conversation->content, true);
            $conversation = collect($conversation);

            //query on $conversation, get out ANSWER
            $userText = $req->get('user_text');
//            $userReplyOrigin = $req->get('user_text_origin');
            $userReplySingularForm = $this->transformWordsToSingular($userText);
            /**
             * Check user only using emoji|emoticon
             */
//            $removeEmojiUserText = $this->removeEmoji($userText);
            $removeEmojiUserText = removeEmojiX($userText);
            $a = $this->removeSpace($removeEmojiUserText);

//            if(empty($this->removeSpace($removeEmojiUserText))){
            if(empty($a)){
                return $this->res(['response' => self::NO_ANSWER]);
            }
//            $userReply = "{$userReply} {$userReplyOrigin}";
            //add space into userReply, bcs day? considerd as whole ONE
            //which dif from 'day ?' considered as 'day' and '?'
            //@info php preg_match can solve 'how are you?'
            //no need to modify here
//            $arr = explode('?', $userReply);
//            $userReply = implode(' ?', $arr);
            $userText = preg_replace('/\.|\.\.|!/', '', $userText);
//            $userReplyOrigin = preg_replace("/\.|\.\.|!/", '', $userReplyOrigin);
            //find out matched answer in conversation
            $answers = $conversation->filter(function($val) use($userText){
                $pattern = $val['Keyword'];

                return preg_match($pattern, $userText);
            });

//            $answers2 = $conversation->filter(function($val) use($userReplyOrigin){
//                $pattern = $val['Keyword'];
//
//                return preg_match($pattern, $userReplyOrigin);
//            });
            //only get the values, store as array, filter make HOLE in array
            //array like object 2=>'asdfasd', 4=>'asdfasdf', bcs 0,1,3 gone
            $answers = $answers->values();
//            $answers2 = $answers2->values();

//            $answers = $answers->merge($answers2);

            $answer = '';

            /**
             * may be more then 1 $answers
             * just choose the first one $answer[0]
             */
            if($answers->count() > 0){
//                $NO_ANSWER = "I hear you, but no anwser";
                try{
                    if($answers[0]['Response 1'] == '-'
                        && $answers[0]['Response 2'] == '-'
                        && $answers[0]['Response 3'] == '-'){
//                        $answer = $NO_ANSWER;
                        $answer = self::NO_ANSWER;
                    }
                }catch(\Exception $e){
                    $answer = '';
                }


                if(empty($answer)){

                    /**
                     * SET DEFAULT AT ONE
                     */
                    if(!empty($answers[0]['Response 1'])){
                        $answer = $answers[0]['Response 1'];
                    }

                    //Play random case
                    //only get the higher response  $answers[0]
                    //random one
                    $x = random_int(1, 3);
                    $responseX = "Response {$x}";
                    //some answer return as -
                    //what the HECK @@, fall back to the 'Response 1'
                    if(!empty($answers[0][$responseX]) && $answers[0][$responseX] != '-'){
                        $answer = $answers[0][$responseX];
                    }
                }
            }
            //if NO answer found, load random one in default
//            if(!($answers->count() > 0)){
            if(empty($answer)){
                $tmp = explode('.', $chatboxName);
                $chatboxNameWithoutExt = $tmp[0];
//                $pattern = "(^({$chatboxNameWithoutExt}).*default)";
                $pattern = "%{$chatboxNameWithoutExt}%default%";
                //look for default conversation
//                $conversation = Conversation::where('name', 'regexp', $pattern)->first();
                // 'expr' to work with sqlite
//                $conversation = Conversation::where('name', 'expr', $pattern)->first();
//                $conversation = Conversation::where('name', '=', 'boyfriend1_default.xlsx')->first();


                $conversation = Conversation::where('name', 'like', $pattern)->first();

                if($conversation && $conversation->count() > 0){
                    $conversation = json_decode($conversation->content, true);
                    $conversation = collect($conversation);
//                    $answer = $conversation[random_int(0, $conversation->count() - 1)];
                    if($conversation->count() > 0){
                        $answer = $conversation->random(1);
                    }
                }
            }

            // Still empty, notify her
            if(empty($answer))
                $answer = 'Sorry, i miss your context';

            return response(['response' => $answer], 200, ['Content-Type' => 'application/json']);
        }
    }
}
