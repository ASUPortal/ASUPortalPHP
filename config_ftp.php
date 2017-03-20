<?php
//require_once("core.php");
# the directory where ftp_rawlist starts
$startdir = "Garant";

# optional Datatypefilter (leave blank if not needed)
$suffix   = "doc;docx;pdf;odt";
# ftp-login
$ftp_server = "10.61.2.62";
$ftp_user   = "kulikov";
$ftp_pw     = "kulikov";
$conn_id    = ftp_connect($ftp_server);
ftp_login($conn_id, $ftp_user, $ftp_pw) OR die("<br>ftp-login failed");
ftp_pasv($conn_id, true);

#*********************************************************************
# create filelist (recursiv)
#*********************************************************************
$files    = array(); # must be defined here
$files    = raw_list("$startdir");

#*********************************************************************
# print result
#*********************************************************************
$i = 0; $count = count($files);
while ($i < $count):
echo "$files[$i]<br>";
$i++;
endwhile;
ftp_close($conn_id);

#*********************************************************************
# rawlist in recursive form (without parameter true!!!)
#*********************************************************************
function raw_list($folder) {
	Global $conn_id;
	Global $suffix;
	Global $files;
	$suffixes = explode(";", $suffix);
	var_dump($suffixes);
	$list     = ftp_rawlist($conn_id, $folder);
	echo $folder;
	var_dump($list);
	$anzlist  = count($list);
	$i = 0;
	while ($i < $anzlist) {
		$split = preg_split("/[\s]+/", $list[$i], 9, PREG_SPLIT_NO_EMPTY);
		$ItemName = $split[8];
		echo $ItemName;
		$endung = strtolower(substr(strrchr($ItemName,"."),1));
		echo $endung;
		$path = "$folder/$ItemName";
		if (substr($list[$i],0,1) === "d" AND substr($ItemName,0,1) != ".") {
			//array_push($files, $path)
			raw_list($path);
		} elseif (substr($ItemName,0,2) != "._" AND in_array($endung,$suffixes)) {
			echo 1;
			array_push($files, $path);
		}
		$i++;
	}
	var_dump($files);
	/*while ($i < $anzlist):
	$split    = preg_split("/[\s]+/", $list[$i], 9, PREG_SPLIT_NO_EMPTY);
	$ItemName = $split[8];
	$endung   = strtolower(substr(strrchr($ItemName,"."),1));
	$path     = "$folder/$ItemName";
	if (substr($list[$i],0,1) === "d" AND substr($ItemName,0,1) != "."):
	#      array_push($files, $path); # write directory in array if desired
	raw_list($path);
	elseif (substr($ItemName,0,2) != "._" AND in_array($endung,$suffixes)):
	array_push($files, $path);
	endif;
	$i++;
	endwhile;*/
	return $files;
}
?>