<style>
body{
font-family:Times new roman;
font-size:15px;
}
</style>
<?php
//Errors
error_reporting(0);
$dir=$_GET['dir'];
$fileName=$_GET['filename'];
$file=$dir.$fileName;
$execpt=array("\\","/",":","?","$",'"',"|",">","<","*");
//logging
if(getenv("os")!="Windows_NT"){
$logDir= getenv("DOCUMENT_ROOT").'/includes/logs';
}else{
$logDir= getenv("DOCUMENT_ROOT").'includes/logs';
}
$createLog=$logDir."/rename.txt";
	if(preg_match('/\;iframe=true/',$fileName))
	{
		die('<span style="color:red">Sorry you can not use Rename page in separate window</span>');
	}
	if(!file_exists($file))
	{
		die('<span style="color:red">An Error occurred: file does not exists.</span>');
	}
if(!isset($_POST['submit']))
{
	if(isset($dir) && isset($fileName))
	{
		echo "
		You are renaming the <font color=\"blue\">{$fileName}</font> file in <font color=\"blue\">{$dir}</font>
		<p>Please enter new name.</p>
		<form action=\"\" method=\"post\">
		<input type=\"hidden\" name=\"filename\" value=\"{$dir}\">
		<input type=\"text\" value=\"{$fileName}\" onblur=\"if(this.value=='') { this.value='New'; }\" name=\"name\" />
		<input type=\"submit\" name=\"submit\" value=\"Rename\"/>
		</form>";
	}else
	{
		echo "This file cannot be used directly";
	}
}else{
			if (file_exists($dir.$_POST['name'])) 
			{
			echo "The <font color=\"blue\">{$_POST['name']}</font> exists in <font color=\"blue\">{$dir}</font>";
			echo '<br /><input type="submit" value="edit name" name="" onclick="javascript:history.go(-1)"/>';
			}elseif(preg_match('/\?|\:|\$|\\\\|\\/|\"|\||\<|\>|\*/',$_POST['name']))
			{
				echo '<input type="submit" value="edit name" name="" onclick="javascript:history.go(-1)"/><br />';
				die( 'These characters \ / : ? $ " | > < * does not allowded');
			}else
			{
				if(rename($file,$dir.$_POST['name']))
				{
					echo "<font color=\"blue\">{$fileName}</font> has been renamed to <font color=\"blue\">{$_POST['name']}</font> successfully.<br />You can now close this window.";

					//logging
					$msgFile="(".$fileName.') was renamed to ('.$_POST['name'].') in '.$dir;
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
					echo '<span style="color:red">An Error occurred renaming file.please be sure if you have right for this directory or file.</span>';
				}
			}

}
?>