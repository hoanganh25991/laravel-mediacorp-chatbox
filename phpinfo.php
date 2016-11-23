<?php phpinfo();
$logFile = fopen('file1.log');
fwrite($logFile, 'Can root:www have permission to write file???');
fclose($logFile);