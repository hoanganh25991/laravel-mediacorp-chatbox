<?php
namespace App\Traits;

use Emojione\Client;
use Emojione\Ruleset;

trait ApiUtils{
    public function removeEmoji($text){
        $emojion = new Client(new Ruleset());
        $textEmojiToShortname = $emojion->toShort($text);

        $emojiPattern = '/(:\w+:|<[\/\\]?3|[\(\)\\\D|\*\$][\-\^]?[:;=]|[:;=B8][\-\^]?[3DOPp@\$\*\\\)\(\/\|])(?=\s|[!\.\?]|$)/';

        return preg_replace($emojiPattern, '', $textEmojiToShortname);
    }

    public function transformWordsToSingular($text){
        $wordArr = explode(" ", $text);
        
        $newWordArr = [];
        foreach($wordArr as $word){
            $newWordArr[] = Inflect::singularize($word);
        }
        
        return implode(" ", $newWordArr);
    }
    
    public function removeSpace($text){
        return preg_replace('/\s+/', '',$text);
    }
}
