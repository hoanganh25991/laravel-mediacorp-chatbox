<?php
namespace App\Traits;

use Inflect;

trait ApiUtils{
    public function removeEmoji($text) {
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        return $clean_text;
    }

    public function checkEmoji($str) {
        $regexEmoticons = '[\x{1F600}-\x{1F64F}]';

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '[\x{1F300}-\x{1F5FF}]';

        // Match Transport And Map Symbols
        $regexTransport = '[\x{1F680}-\x{1F6FF}]';


        // Match Miscellaneous Symbols
        $regexMisc = '[\x{2600}-\x{26FF}]';

        // Match Dingbats
        $regexDingbats = '[\x{2700}-\x{27BF}]';

        $all = "/({$regexEmoticons}|{$regexSymbols}|{$regexTransport}|{$regexMisc}|{$regexDingbats})/u";

        return preg_match($all, $str);
    }

    public function transformWordsToPlural($text){
        $wordArr = explode(" ", $text);
        
        $newWordArr = [];
        foreach($wordArr as $word){
            $newWordArr[] = Inflect::pluralize($word);
        }
        
        return implode(" ", $newWordArr);
    }
    
    public function removeSpace($text){
        return preg_replace('/\s+/', '',$text);
    }
}
