<?php
	function createlog($msg){
		$file = getcwd()."/logs/".date('Y-m-d').".txt";
		if(file_exists($file)){
			//$current = file_get_contents($file);
			// Append a new person to the file
			//$current .= $msg;
			file_put_contents($file, $msg, FILE_APPEND | LOCK_EX);
		}else{
			$ourFileHandle = fopen($file, 'w') or die("can't open file");
			//$current = file_get_contents($file);
			// Append a new person to the file
			//$current .= $msg;
			file_put_contents($file, $msg, FILE_APPEND | LOCK_EX);
			fclose($ourFileHandle);
		}
	}
?>
