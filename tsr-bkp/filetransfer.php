<?php
set_time_limit(0); //Unlimited max execution time
 
$path = 'code.zip';
$url = 'http://myprojectdemonstration.com/development/palcura/demo/code.zip';
//$url = 'http://localhost/projects/nualawoulfe/nualawoulfe.zip';
$newfname = $path;
echo 'Starting Download!<br>';
$file = fopen ($url, "rb");
if($file) {
	$newf = fopen ($newfname, "wb");
	if($newf)
		while(!feof($file)) {
			fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			echo '1 MB File Chunk Written!<br>';
		}
}
if($file) {
	fclose($file);
}
if($newf) {
	fclose($newf);
}
echo 'Finished!';
?>