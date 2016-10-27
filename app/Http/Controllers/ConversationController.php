<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;

class ConversationController extends Controller
{
    public function script(Request $req){
//        $conversations = Conversation::all();
        $conversations = Conversation::where('name', 'not like', '%default%')->get();

        if($req->method() == 'GET'){
            return view('conversation.script')->with(compact('conversations'));
        }

        if($req->method() == 'POST'){
            $chatboxName = $req->get('chatbox_name');
            $conversation = $conversations->where('name', $chatboxName)->first();

            $conversation = json_decode($conversation->content, true);
            $conversation = collect($conversation);

            //query on $conversation, get out ANSWER
            $userReply = $req->get('user_reply');
            $answers = $conversation->filter(function($val) use($userReply){
                $pattern = $val['Keyword'];

                return preg_match($pattern, $userReply);
            });
            //only get the values, store as array, filter make HOLE in array
            //array like object 2=>'asdfasd', 4=>'asdfasdf', bcs 0,1,3 gone
            $answers = $answers->values();

            $answer = '';

            if($answers->count() > 0){
                //only get the higher response  $answers[0]
                //random one
                $x = random_int(1, 3);
                $responseX = "Response {$x}";
                //some answer return as -
                //what the HECK @@, give back the 'Response 1
                $answer = $answers[0][$responseX];
                $answer = ($answer != '-') ? $answer : $answers[0]['Response 1'];
            }
            //IF NO ANSWER FAULT
            //LOAD FROM default
            if(!($answers->count() > 0)){
//                $answer = $answers->count() > 0 ? $answers[0][$responseX] : 'sorry, i mis what you mean';
                $tmp = explode('.', $chatboxName);
                $chatboxNameWithoutExt = $tmp[0];
                $pattern = "(^({$chatboxNameWithoutExt}).*default)";
                //look for default
                $conversation = Conversation::where('name', 'regexp', $pattern)->first();
                if($conversation && $conversation->count() > 0){
                    $conversation = json_decode($conversation->content, true);
                    $conversation = collect($conversation);
                    //@warn pretend that $conversation not empty arr
                    $answer = $conversation[random(0, $conversation->count())];
                }
            }

            if(empty($answer))
                $answer = 'Sorry, i miss your context';

//            return compact('userReply', 'answer');
//            return response(compact('userReply', 'answer'), 200, ['Content-Type' => 'application/json']);
            return response(['answer' => $answer], 200, ['Content-Type' => 'application/json']);
        }
    }
}
