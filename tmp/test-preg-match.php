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
$strs = ["hoang anh le", "hoanganh le", "le hoanganh", "hello hoanganh le", "hi hoang anhle", "hello anh", "yo?", "yo", "hey tinker", "hey anh",  "hello hoang anh", "helloyo anh", "anh hello", "I like", "should I like", "how are you ?", "how are you?", "how your day ?", "how your day?", "your day?", "hey your day"];

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

	// $pattern = "/''/";
	// echo "try pattern: ";
	// if(preg_match($pattern, $str)){
	// 	echo "pattern in {$str}\n";
	// }else{
	// 	echo "pattern not in {$str}\n";
	// }

//	$pattern = "/(\?)$/";
//	echo "try pattern: ";
//	if(preg_match($pattern, $str)){
//		echo "pattern in {$str}\n";
//	}else{
//		echo "pattern not in {$str}\n";
//	}

//	$pattern = "/(yo\?)$/";
//	echo "try pattern: ";
//	if(preg_match($pattern, $str)){
//		echo "pattern in {$str}\n";
//	}else{
//		echo "pattern not in {$str}\n";
//	}

	//try to start ONLY by "hoa"
//	$pattern = "/^(hoa)/";
//	$pattern = "/^(\bhoa\b)/";
//	echo "try pattern: ";
//	if(preg_match($pattern, $str)){
//		echo "pattern in {$str}\n";
//	}else{
//		echo "pattern not in {$str}\n";
//	}

//	$pattern = "/(\b\?\b)$/"; //wrong one, no thing match this
//	$pattern = "/(\?)$/";
//	$pattern = "/(?=.*(\byour day\b))(?=.*((\?)$))/i"; //good work for 'your day' && *?
//	echo "try pattern: ";
//	if(preg_match($pattern, $str)){
//		echo "pattern in {$str}\n";
//	}else{
//		echo "pattern not in {$str}\n";
//	}

//	$pattern = "/(?)$/i"; //good work for 'your day' && *?
//	echo "try pattern: ";
//	if(preg_match($pattern, $str)){
//		echo "pattern in {$str}\n";
//	}else{
//		echo "pattern not in {$str}\n";
//	}

//	$pattern= "/(?=.*(^(\byour day\b)|(\byour day\b)$))(?=.*((?)$))/i";
////	$pattern= "/(?=.*(^(\byour day\b)|(\byour day\b)$))(?=.*(()$))/i";
//	echo "try pattern: ";
//	if(preg_match($pattern, $str)){
//		echo "pattern in {$str}\n";
//	}else{
//		echo "pattern not in {$str}\n";
//	}

}

$userReplys = ['hello.', 'i mis you..', "love you!"];

foreach($userReplys as $userReply){
	$a = preg_replace("/\.|\.\.|!/", '', $userReply);
	echo $a . "\n";
}







