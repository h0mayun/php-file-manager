<?php
include('config.php');
if(getenv("os")!="Windows_NT"){
	$docRoot="//".getenv("DOCUMENT_ROOT");
}else{
$docRoot= substr(getenv("DOCUMENT_ROOT"), 0, -1);
}
$strReplace=array("./"=>"","/"=>"\\");
//***** Deny browser to cache data and to get working the reresh button   :-"
header("Expires: Mon, 1 Jul 1000 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache"); 
// *******IGNOR ROOT***********************************
$projectsListIgnore = array ('.','..','.DS_Store', 'Thumbs.db');
// *******NEEDED VALS FOR ROOT*********************
$projectFolders = "";
//Errors
error_reporting(0);
//logging
$logDir= $docRoot.'/includes/logs';
$createLog=$logDir."/copypaste.txt";
// *******LIST ALL ROOT FILES *************************
$dirName=$_GET['dir'];
$dirToPaste=$_GET['dirpaste'];
$filePaste=$_GET['filepaste'];
$fullDir=$dirToPaste.$filePaste;
if(preg_match('/\;iframe=true/',$filePaste))
{
	die('<span style="color:red">Sorry you can not use this page in separate window</span>');
}
if(!isset($dirToPaste)||!isset($filePaste)){
	die( "This file cannot be used directly");
}
if(!file_exists($fullDir))
{
	die('<span style="color:red">An Error occurred: file does not exists.</span>');
}
//fixing problem with back button to root******************
if($dirName=="\\"){
	$dirName=".";
}

if(isset($dirName) && $dirName!="."){
	// *******remove first character and check if page exsist except root 
	if (!file_exists($dirName)&&$dirName!="") {
		die( "The page ".$dirName." does not exist");
	}
}
//
$dir =$dirName;
//back button###############
$back=$localhost.'includes/paste.php?dir='.strtr(dirname($dirName).'&dirpaste='.$dirToPaste.'&filepaste='.$filePaste,"http://","");
if($back==$localhost.'includes/paste.php?dir=\&dirpaste='.$dirToPaste.'&filepaste='.$filePaste){
	$back=$localhost."includes/paste.php?dirpaste=".$dirToPaste.'&filepaste='.$filePaste;
}

$showDir='<form action="" method="get"><div style="border-bottom:1px #aaa solid;"><span style="color:#666666;text-shadow: 3px 0px 6px #fff;">'.strtr($dir,$strReplace).'\</span>
<input type="hidden" name="dirpaste" value="'.$dirToPaste.'">
<input type="hidden" name="filepaste" value="'.$filePaste.'">
<input type="hidden" name="dir" value="'.$dirName.'">
<input type="text" style="width:100px" name="name" value="'.$filePaste.'">
<input type="submit" value="Paste!" title="paste it to '.$docRoot.$dirName.'/" name="submitpaste" style="background:#eee;">
&nbsp;&nbsp
<span class="icon">
<a href="'.$back.' " >
<img src="'.$localhost.'includes/images/back.png" title="back" /></a></span>&nbsp;&nbsp
<span class="icon">
<a href="'.$localhost.'paste.php">
<img src="'.$localhost.'includes/images/home.png" title="home" /></a></span></div></form>';
$newDir=$dirName."/".$_GET['name'];

//Copy Dir **************
function copyDirectoy($fullDir,$newDir) { 
     $dir = opendir($fullDir);
	 if(file_exists($newDir)){
		die( "<font color=\"blue\">{$_GET['name']}</font> already exists <input type=\"submit\" value=\"edit name\" name=\"\" onclick=\"javascript:history.go(-1)\"/>");
	 }else
	 {
		 @mkdir($newDir); 
		 while(false !== ( $Nfile = readdir($dir)) ) { 
			 if (( $Nfile != '.' ) && ( $Nfile != '..' )) { 
				 if ( is_dir($fullDir . '/' . $Nfile) ) { 
					 copyDirectoy($fullDir . '/' . $Nfile,$newDir . '/' . $Nfile); 
				 } 
				 else { 
					 copy($fullDir . '/' . $Nfile,$newDir . '/' . $Nfile); 
				 } 
			 } 
		 } 
		 closedir($dir); 
	 }
 } 
//////////////////
if(isset($_GET['submitpaste']))
{
	if(is_file($fullDir)){
		if(file_exists($newDir)){
		die( "<font color=\"blue\">{$_GET['name']}</font> already exists <input type=\"submit\" value=\"edit name\" name=\"\" onclick=\"javascript:history.go(-1)\"/>");
		 }else
		 {
			if(copy($fullDir, $newDir))
			{
				echo '<span style="color:green">a copy of '.$filePaste.' pasted in '.$dirName."/ named ".$_GET['name']."</span>";
				//logging
				$msgFile="a copy of (".$filePaste.') pasted in '.$dirName."/ named ".$_GET['name'];
				if(file_exists($logDir) && is_dir($logDir))
				{
					$open=fopen($createLog,"a+");
					fwrite($open, "[".date("y-m-d h:i:s A")."] ".$msgFile."\r\n");
					fclose($open);
				}else
				{
					mkdir($logDir, 0777);
					$open=fopen($createLog,"a+");
					fwrite($open, "[".date('Y-m-d h:i:s A')."] ".$msgFile."\r\n");
					fclose($open);
				}
			}else{
				echo '<span style="color:red">An Error occurred while copying file.please be sure if you have right to creating Directory.</span>';
			}
		 }
	}elseif(is_dir($fullDir))
	{
		// doesn't work :(
	//	if(copyDirectoy($fullDir,$newDir))
	//	{
			copyDirectoy($fullDir,$newDir);
			echo '<span style="color:green">a copy of '.$filePaste.' pasted in '.$dirName."/ named ".$_GET['name']."</span>";
			//logging
			$msgFile="a copy of (".$filePaste.') pasted in '.$dirName."/ named ".$_GET['name'];
			if(file_exists($logDir) && is_dir($logDir))
			{
				$open=fopen($createLog,"a+");
				fwrite($open, "[".date("y-m-d h:i:s A")."] ".$msgFile."\r\n");
				fclose($open);
			}else
			{
				mkdir($logDir, 0777);
				$open=fopen($createLog,"a+");
				fwrite($open, "[".date('Y-m-d h:i:s A')."] ".$msgFile."\r\n");
				fclose($open);
			}
		//}else{
		//	echo '<span style="color:red">An Error occurred while copying folder.please be sure if you have right to creating Directory.</span>';
		//	}
	}else{
		echo '<span style="color:red">An Error occurred while copying folder/file.please be sure if you have right to creating Directory.</span>';
	}
}
$handle=opendir($dir);
while ($files[] = readdir($handle)) 
{
natcasesort($files);
}
foreach ($files as $file)
{	
//used for editing
  $dirFile= $dir."/".$file;
//used for browsing  
  $dirFile2= $dirName."/".$file;
	$nameFile=$file;
	if(strlen($nameFile)>10){
		$nameFile=substr($nameFile,0,10)."...";
	}

// *******LIST ROOT FOLDERS OPEN THEM************  
	if (is_dir($dirFile) &&is_readable($dirFile)&& !in_array($file,$projectsListIgnore)) 
	{		
		$projectFolders .= '<li class="folder"><a href="'.$localhost.'includes/paste.php?dir='.$dirFile2.'&dirpaste='.$dirToPaste.'&filepaste='.$filePaste.'" title="'.$file.'"><b>'.$nameFile.'</b></a></li>';		
	}
}
?>

<style>
body{
font-family:Times new roman;
font-size:15px;
}
a:link ,a:visited,a:active {
color:#3a3a3a;
	-webkit-transition: all 0.7s ease;
	-moz-transition: all 0.7s ease;
	-o-transition: all 0.7s ease;
	transition: opacity 0.7s ease;
}
a:hover{
color:rgb(0, 119, 204);
	-webkit-transition: all 0.7s ease;
	-moz-transition: all 0.7s ease;
	-o-transition: all 0.7s ease;
	transition: opacity 0.7s ease;
}
.folder {
line-height: 20px;padding-left: 25px;list-style: none;background: url(images/folder.png) 0 100% no-repeat;
}
.add {
line-height: 20px;padding-left: 25px;list-style: none;background: url(images/add.gif) 0 100% no-repeat;
}
input[type=text]{
	background-color:#eee;
	border: 1px #bbb solid;
	width:15px
}
.icon{
	opacity:0.4;
	-webkit-transition: all 0.7s ease;
	-moz-transition: all 0.7s ease;
	-o-transition: all 0.7s ease;
	transition: opacity 0.7s ease;
}
.icon:hover{
	opacity:1;
	-webkit-transition: all 0.7s ease;
	-moz-transition: all 0.7s ease;
	-o-transition: all 0.7s ease;
	transition: opacity 0.7s ease;
}
</style>
<script type="text/javascript">
        function preloader(){
            document.getElementById("loading").style.display = "none";
            document.getElementById("content").style.display = "block";
        }
        window.onload = preloader;
</script>
</head>
<body>
<?php echo $showDir; ?>
<div id="loading"></div>
<div id="content">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
  <td width="35%" valign="top">
  <li class="add"><a href="<?php echo $localhost; ?>includes/create.php?createfolder=<?php echo $dir."/"; ?>&dir=<?php echo $localhost."paste.php?dir=".$dirName; ?>" target="iframe">Create New Folder</a>
  <span class="icon"><img src="images/reload.gif" title="refresh" onclick="javascript:window.location.reload(1)"/></span></li>	
	<?php echo $projectFolders; ?>
  </td>
  <td width="35%" valign="top">
  <iframe name="iframe" width="100%" height="100%" frameborder="0"></iframe>
  </td>
  </tr>
</table>
</div>
