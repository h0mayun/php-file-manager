<style>
body{
font-family:Times new roman;
font-size:15px;
}
</style>
<?php
//Errors*********************
error_reporting(0);
//logging
if(getenv("os")!="Windows_NT"){
$logDir= getenv("DOCUMENT_ROOT").'/includes/logs';
}else{
$logDir= getenv("DOCUMENT_ROOT").'includes/logs';
}
$createLog=$logDir."/delete.txt";
$file= $_GET['file'];
$folder= $_GET['folder'];
$dir=$_GET['dir'];
$fileName=$_GET['filename'];
	if(!file_exists($file)&&is_file($file))
	{
		die('<span style="color:red">An Error occurred: file does not exists.</span>');
	}
	if(!file_exists($folder)&&is_dir($folder))
	{
		die('<span style="color:red">An Error occurred: Directory does not exists.</span>');
	}
	if(preg_match('/\;iframe=true/',$fileName))
	{
		die('<span style="color:red">Sorry you can not use Delete page in separate window</span>');
	}
//===================================Delete a directory and sub directorie and sub sub.... Function
 function delete_dir_tree($folder, $empty=FALSE)
 {
     if(substr($folder,-1) == '/')
     {
         $folder = substr($folder,0,-1);
     }
     if(!file_exists($folder) || !is_dir($folder))
     {
         return FALSE;
     }elseif(is_readable($folder))
     {
         $handle = opendir($folder);
         while (FALSE !== ($item = readdir($handle)))
         {
             if($item != '.' && $item != '..')
             {
                 $path = $folder.'/'.$item;
                 if(is_dir($path)) 
                 {
                     delete_dir_tree($path);
                 }else{
                     unlink($path);
                 }
             }
         }
         closedir($handle);
         if($empty == FALSE)
         {
             if(!rmdir($folder))
             {
                 return FALSE;
             }
         }
     }
     return TRUE;
 }
 //=====================================
if(!isset($_POST['no'])&&!isset($_POST['yes'])&&!isset($_POST['yesfolder'])&&!isset($_POST['nofolder']))
{
		if(isset($file))
		{
		echo '
		You are deleting the file <font color="blue">'.$fileName.'</font><br /> in <font color="blue">'.$dir.'</font>
		<p>Are You Sure?</p>
		<form action="" method="post">
		<input type="submit" value="Yes" name="yes" />
		<input type="submit" value="No" name="no" onclick="window.parent.closeTheIFrameImDone();"/>';

		}elseif(isset($folder))
		{
		echo '
		You are deleting the folder <font color="blue">'.$fileName.'</font><br /> in <font color="blue">'.$dir.'</font>
		<p>Are You Sure?</p>
		<form action="" method="post">
		<input type="submit" value="Yes" name="yesfolder" />
		<input type="submit" value="No" name="nofolder" onclick="window.parent.closeTheIFrameImDone();"/>';
		}else{
			die( "This file cannot be used directly");
		}
}
if(isset($_POST['yes']))
{
	if(unlink($file)){
		echo 'File successfully has been deleted';
			//logging
			$msgFile="File: (".$fileName.') was deleted in '.$dir;
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
		echo '<span style="color:red">An Error occurred deleting file.please be sure if you have right for this directory or file.</span>';
	}

}elseif(isset($_POST['yesfolder']))
{	


        // delete parent 
		if(delete_dir_tree($folder, $empty=FALSE)){
			echo 'Folder successfully has been deleted';
				//logging
				$msgFile="Folder: (".$fileName.') was deleted in '.$dir;
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
			}			
	else{
		echo '<span style="color:red">An Error occurred deleting folder.please be sure if you have right for this directory or file.</span>';
	}	
}
?>

