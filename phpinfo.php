<?php phpinfo();
$logFile = fopen('file1.log', 'w');
fwrite($logFile, 'Can root:www have permission to write file???');
fclose($logFile);