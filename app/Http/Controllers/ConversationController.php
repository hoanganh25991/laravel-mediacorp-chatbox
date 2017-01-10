<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use Validator;
use App\Traits\ApiResponse;
use App\Traits\ApiUtils;

class ConversationController extends Controller{
    use ApiResponse;
    use ApiUtils;

    const NO_ANSWER = "I hear you, but no anwser";
    const MIS_CONTEXT = "Sorry, i miss your context";
    // boyfriend1 is the 3rd boyfriend (The Sunshine Boy) in the app
    // boyfriend 2 is 2nd bofriend (The Pracitcal One) in the app
    // boyfriend3 is last boyfriend (the sweet guy) in the app
    // boyfriend4 is actually the first boyfriend in the app (The devoted introvert)
    const CHATBOX_MAP = [
        "2" => "boyfriend1.xlsx",
        "1" => "boyfriend2.xlsx",
        "0" => "boyfriend4.xlsx",
        "3" => "boyfriend3.xlsx",
        "99" => "image.xlsx"
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
            'user_text' => 'required',
            'device_id' => 'required'
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
        //store userText origin for log
        $userTextOrigin = $userText;
        // Remove some special character
        $userText = $this->removeSomeSC($userText);
        // Support singular form, when user type in plural
        $userTextSingularForm = $this->transformWordsToSingular($userText);

        // Find out answer for origin user_text
        $answersUserText = $conversation->filter(function ($val) use ($userText){
                $pattern = $val['Keyword'];
                return preg_match($pattern, $userText);
            })// After filter, array has "HOLE", just get values as array
            ->values();

        $answersUserTextSingularForm = $conversation->filter(function ($val) use ($userTextSingularForm){
                $pattern = $val['Keyword'];
                return preg_match($pattern, $userTextSingularForm);
            })// After filter, array has "HOLE", just get values as array
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
        if(empty($answer)){
            $answer = self::MIS_CONTEXT;
        }

        /**
         * Build log file
         */
//        $this->buildLog($req->get('device_id'), $chatboxId, $userTextOrigin, $answer);
        $this->buildCSVLog($req->get('device_id'), $chatboxId, $userTextOrigin, $answer);

        /**
         * Decide answer type
         */
        $answerType = 'text';
        /**
         * Because image note as 'image:abc.png'
         * > have to remove 'image:'
         */
        if(strpos($answer, "image:") === 0){
            $answer = substr($answer, 6);
        }
        $filePath = storage_path('app/photos/' . $answer);
        $fileInfo = @getimagesize($filePath);
        if(!empty($fileInfo) && strpos($fileInfo['mime'], 'image') !== false){
            $answerType = 'image';
            $answer = url('photos/' . $answer);
        }

        return response([
            'response' => $answer,
            'type' => $answerType
        ], 200, ['Content-Type' => 'application/json']);
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
//                $answer1 format 
//                [
//                    'Keyword' => 'adasd',
//                    'Response 1' => 'asda',
//                    'Resoibse 2' => 'asdfasd',
//                    //if missing Response 3, no thing here
//                ]
                // So that, just random to the max of answer
                // (Implicit has 3 means has 2, 1, has 2 means has 1)
                $numOfRes = count($answer1) - 1; //remove Keyword
                $x = random_int(1, $numOfRes);
                $responseX = "Response {$x}";

                if($answer1[$responseX] == '-'){
                    return self::NO_ANSWER;
                }

                return $answer1[$responseX];
            }catch(\Exception $e){
                return isset($answer1['Response 1'])? $answer1['Response 1'] : "";
            }
        }

        return $answer;
    }

    private function buildLog($device_id, $boyfriend_id, $user_message, $boyfriend_response){
//        device_id,boyfriend_id,user_message,boyfriend_response, timestamp
        $logName = base_path('user-response.log');
        $logFile = fopen($logName, 'a');

        $timestamp = time();
        $content =
            json_encode(compact('device_id', 'boyfriend_id', 'user_message', 'boyfriend_response', 'timestamp')) . "\n";
        fwrite($logFile, $content);
        fclose($logFile);
    }

    private function buildCSVLog($device_id, $boyfriend_id, $user_message, $boyfriend_response){
        $logName = base_path('user-response.csv');

        $logFile = fopen($logName, 'a');

        $timestamp = time();
        fputcsv($logFile, compact('device_id', 'boyfriend_id', 'user_message', 'boyfriend_response', 'timestamp'));

        fclose($logFile);
    }

    public function checkImageExist(){
        $conversations = Conversation::where('name', 'not like', '%default%')->get();

        $conversations->each(function ($conversation){
            echo "Check on: {$conversation->name}\n";

            $conversation = json_decode($conversation->content, true);
            // Convert to collection of laravel for easy manipulate
            //[
            //      [
            //        'Key word': 'asdfasdf',
            //        'Response 1': 'safasdf',
            //        'Response 2': 'safasdf',
            //        'Response 3': 'safasdf',
            //       ],
            //      [
            //        'Key word': 'asdfasdf',
            //        'Response 1': 'safasdf',
            //        'Response 2': 'safasdf',
            //        'Response 3': 'safasdf',
            //       ],
            //]
            $conversation = collect($conversation);

            $allowImageFormat = [
                "gif",
                "jpeg",
                "jpg",
                "png"
            ];



            $conversation->each(function ($row) use($allowImageFormat){
                $count = 1;
                while($count < 4){
                    try{
                        $responseX = "Response " . $count;
                        $answer = $row[$responseX];

                        if(strpos($answer, "image:") === 0){
                            $answer = substr($answer, 6);
                        }

                        $answerExt = substr($answer, strpos($answer, ".") + 1);
                        $isAnswerImage = in_array($answerExt, $allowImageFormat);

                        if($isAnswerImage !== false){
                            echo "\tAnswer as image: {$answer}\n";
                            $filePath = storage_path('app/photos/' . $answer);
                            $fileInfo = @getimagesize($filePath);

                            $findImageMsg = "\tImage file NOT exist\n";
                            if(!empty($fileInfo) && strpos($fileInfo['mime'], 'image') !== false){
                                $findImageMsg = "\tImage file exist\n";
                            }

                            echo $findImageMsg;
                        }
                    }catch(\Exception $e){

                    }

                    $count++;
                }
            });
        });

        echo "=========Completed=========\n";
        
        return compact('nothing');
    }
}
