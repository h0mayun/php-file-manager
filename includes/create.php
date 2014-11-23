<style>
body{
font-family:Times new roman;
font-size:15px;
}
</style>
<?php
//Errors
error_reporting(0);
$dirFileRoot= $_GET['createfile'];
$dirFolderRoot= $_GET['createfolder'];
$dir=$_GET['dir'];
	if(preg_match('/\;iframe=true/',$dir))
	{
		die('<span style="color:red">Sorry you can not use Create page in separate window</span>');
	}
//logging
if(getenv("os")!="Windows_NT"){
$logDir= getenv("DOCUMENT_ROOT").'/includes/logs';
}else{
$logDir= getenv("DOCUMENT_ROOT").'includes/logs';
}
$createLog=$logDir."/create.txt";
//Creating File ##############
if(!isset($_POST['submit'])&&!isset($_POST['submitFolder']))
{
	if(isset($dirFileRoot))
	{
		echo "
		You are creating a new file in <font color=\"blue\">{$dirFileRoot}</font>
		<p>Please enter the file name.</p>
		<form action=\"\" method=\"post\">
		<input type=\"hidden\" name=\"createfile\" value=\"{$dirFileRoot}\">
		<input type=\"text\" value=\"New File.php\" onfocus=\"if(this.value == 'New File.php') { this.value = ''; }\" onblur=\"if(this.value=='') { this.value='New File.php'; }\" name=\"FileName\" />
		<input type=\"submit\" name=\"submit\" value=\"Create\"/>
		</form>";

	//Creating Folder ##############	
	}elseif(isset($dirFolderRoot))
	{
		echo "
		You are creating a new Folder in <font color=\"blue\">{$dirFolderRoot}</font>
		<p>Please enter the file name.</p>
		<form action=\"\" method=\"post\">
		<input type=\"hidden\" name=\"createfolder\" value=\"{$dirFolderRoot}\">
		<input type=\"text\" value=\"New Folder\" onfocus=\"if(this.value == 'New Folder') { this.value = ''; }\" onblur=\"if(this.value=='') { this.value='New Folder'; }\" name=\"FolderName\" />
		<input type=\"submit\" name=\"submitFolder\" value=\"Create\" />
		</form>";
		
	}else{
	echo "This file cannot be used directly";
	}
}elseif(isset($_POST['submitFolder']))
	{
		$dirFolderRoot= $_GET['createfolder'];
		$folder = $dirFolderRoot.$_POST['FolderName'];
		if (file_exists($folder)) 
		{
			echo "The folder <font color=\"blue\">{$_POST['FolderName']}</font> exists in <font color=\"blue\">{$dirFolderRoot}</font>";
			echo '<br /><input type="submit" value="edit name" name="no" onclick="javascript:history.go(-1)"/>';
		}elseif(preg_match('/\?|\:|\$|\\\\|\\/|\"|\||\<|\>|\*|\./',$_POST['FolderName']))
		{
			echo '<input type="submit" value="edit name" name="" onclick="javascript:history.go(-1)"/><br />';
			die( 'These characters \ / : ? $ " | > < * does not allowded');
		}else
		{
			if(mkdir($folder, 0777)){
				echo 'Folder successfully has been created';
				//logging
				$msgFile="Folder: (".$_POST['FolderName'].') was created in '.$dirFolderRoot;
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
				echo '<span style="color:red">An Error occurred while creating folder.please be sure if you have right to creating Directory.</span>';
			}
		} 
	}elseif(isset($_POST['submit']))
	{
		// if name does not contain dot add default .php
		if(strstr($_POST['FileName'], '.') !== false)
		{
			$_POST['FileName'] = $_POST['FileName'];
		}else{
			$_POST['FileName'] .= ".php";
		}
		$dirFileRoot= $_GET['createfile'];
		$file = $dirFileRoot.$_POST['FileName'];
		if (file_exists($file)) 
		{
			echo "The file <font color=\"blue\">{$_POST['FileName']}</font> exists in <font color=\"blue\">{$dirFileRoot}</font>";
			echo '<br /><input type="submit" value="edit name" name="" onclick="javascript:history.go(-1)"/>';
		}elseif(preg_match('/\?|\:|\$|\\\\|\\/|\"|\||\<|\>|\*/',$_POST['FileName']))
			{
				echo '<input type="submit" value="edit name" name="" onclick="javascript:history.go(-1)"/><br />';
				die( 'These characters \ / : ? $ " | > < * does not allowded');
			}else
			{
				if($open = fopen($file,"w+")){
					fclose($open);
					echo 'File successfully has been created.';
					//logging
					$msgFile="File: (".$_POST['FileName'].') was created in '.$dirFileRoot;
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
					echo '<span style="color:red">An Error occurred creating file.please be sure if you have right to creating Directory.</span>';
				}
			} 
	}
		
?>
