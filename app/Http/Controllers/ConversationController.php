<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;

class ConversationController extends Controller
{
    public function script(Request $req){
        $conversations = Conversation::all();

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

            //only get the higher response
            $resX = random_int(1, 3);
            $responseX = "Response {$resX}";
            $answer = $answers->count() > 0 ? $answers[0][$responseX] : 'sorry, i mis what you mean';

//            return compact('userReply', 'answer');
//            return response(compact('userReply', 'answer'), 200, ['Content-Type' => 'application/json']);
            return response(['answer' => $answer], 200, ['Content-Type' => 'application/json']);
        }
    }
}
