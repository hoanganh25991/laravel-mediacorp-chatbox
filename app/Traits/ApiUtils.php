<?php
namespace App\Traits;


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

    public function checkEmoji($str)
    {
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        preg_match($regexEmoticons, $str, $matches_emo);
        if (!empty($matches_emo[0])) {
            return false;
        }

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        preg_match($regexSymbols, $str, $matches_sym);
        if (!empty($matches_sym[0])) {
            return false;
        }
        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        preg_match($regexTransport, $str, $matches_trans);
        if (!empty($matches_trans[0])) {
            return false;
        }

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        preg_match($regexMisc, $str, $matches_misc);
        if (!empty($matches_misc[0])) {
            return false;
        }
        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        preg_match($regexDingbats, $str, $matches_bats);
        if (!empty($matches_bats[0])) {
            return false;
        }
        return true;
    }
}
