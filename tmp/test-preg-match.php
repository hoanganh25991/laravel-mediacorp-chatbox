<?php
/* The \b in the pattern indicates a word boundary, so only the distinct
 * word "web" is matched, and not a word partial like "webbing" or "cobweb" */

// if (preg_match("/\bweb\b/i", "PHP is the web scripting language of choice.")) {
//     echo "A match was found.";
// } else {
//     echo "A match was not found.";
// }

// if (preg_match("/\bweb\b/i", "PHP is the website scripting language of choice.")) {
//     echo "A match was found.";
// } else {
//     echo "A match was not found.";
// }

/**
 * start with
 */
$strs = ["hoang anh le", "hoanganh le", "le hoanganh", "hello hoanganh le", "hi hoang anhle", "hello anh", "yo?", "yo", "hey tinker", "hey anh",  "hello hoang anh", "helloyo anh", "anh hello", "I like", "should I like"];

foreach ($strs as $str) {

	// echo "startWith hoanganh?:";
	// if(preg_match("/^\bhoanganh\b/", $str)){
	// 	echo "hoanganh in {$str}\n";
	// }else{
	// 	echo "hoanganh not in {$str}\n";
	// }

	// echo "endWith hoanganh?:";
	// if(preg_match("/\bhoanganh\b$/", $str)){
	// 	echo "hoanganh in {$str}\n";
	// }else{
	// 	echo "hoanganh not in {$str}\n";
	// }
	 
	// echo "has hoang anh?:";
	// if(preg_match("/\bhoang anh\b/", $str)){
	// 	echo "hoang anh in {$str}\n";
	// }else{
	// 	echo "hoang anh not in {$str}\n";
	// }

	// echo "has hoang anh?:";
	// if(preg_match("/\bhoang anh\b|\ble\b/", $str)){
	// 	echo "hoang anh || le in {$str}\n";
	// }else{
	// 	echo "hoang anh || le not in {$str}\n";
	// }
	
//	 $pattern = "/^(hoanganh|hello|hi)/";
//	 echo "try pattern: ";
//	 if(preg_match($pattern, $str)){
//	 	echo "pattern in {$str}\n";
//	 }else{
//	 	echo "pattern not in {$str}\n";
//	 }

	
//	 $pattern = "/^(?=.*hello)(?=.*anh)/s";
//	$pattern = "/^(?=.*hello)(?=.*\banh\b)/s";
//	$pattern = "/^(?=.*hello)(?=.*\banh\b)/";
//	$pattern = "/^(?=.*hello)(?=.*\banh\b)|^(hi|yo)/";
//	$pattern = "/^(?=.*\bhello\b)(?=.*\banh\b)|^(hi|yo)/";
//	$pattern = "/^(?=.*^(hello))(?=.*\banh\b)/";
//	echo "try pattern: ";
//	if(preg_match($pattern, $str)){
//		echo "pattern in {$str}\n";
//	}else{
//		echo "pattern not in {$str}\n";
//	}

//	$pattern = "/^(hello|hi|hey|yo)/";
////	$pattern = "/^(\bhello\b|\bhi\b|\bhey\b|\byo\b)/";
//	echo "try pattern: ";
//	if(preg_match($pattern, $str)){
//		echo "pattern in {$str}\n";
//	}else{
//		echo "pattern not in {$str}\n";
//	}

	// $pattern = "/^(hello|hi)|(anh|tinker)$/";
	// echo "try pattern: ";
	// if(preg_match($pattern, $str)){
	// 	echo "pattern in {$str}\n";
	// }else{
	// 	echo "pattern not in {$str}\n";
	// }
	
//	$pattern = "/^(hello|hi)|(anh|tinker)$|\bhoanganh\b/";
//	echo "try pattern: ";
//	if(preg_match($pattern, $str)){
//		echo "pattern in {$str}\n";
//	}else{
//		echo "pattern not in {$str}\n";
//	}

//	$pattern = "/(?=.*^(I like))/";
//	echo "try pattern: ";
//	if(preg_match($pattern, $str)){
//		echo "pattern in {$str}\n";
//	}else{
//		echo "pattern not in {$str}\n";
//	}

	$pattern = "//";
	echo "try pattern: ";
	if(preg_match($pattern, $str)){
		echo "pattern in {$str}\n";
	}else{
		echo "pattern not in {$str}\n";
	}

}





