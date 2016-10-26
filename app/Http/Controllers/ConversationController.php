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
            $content = $conversations->where('name', $chatboxName);

            $conversation = json_decode($content, true);
            $conversation = collect($conversation);

            //query on $conversation, get out ANSWER
            $userReply = $req->get('user_reply');
            $answers = $conversation->filter(function($val) use($userReply){
                $pattern = $val['Keyword'];

                return preg_match($pattern, $userReply);
            });

            //only get the higher response
            $answer = $answers->count() > 0 ? $answers[0] : 'sorry, i don\'t mis what you mean';

            return compact('userReply', 'answer');
        }
    }
}
