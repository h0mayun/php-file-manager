<?php
if(!isset($_GET['file'])){
	die("This file cannot be used directly");
}
?>
<style>
body{
background-color:#222222;
}
</style>
	<script language="Javascript" type="text/javascript" src="highlight/edit_area_full.js"></script>
		<script language="Javascript" type="text/javascript">
		// initialisation
		editAreaLoader.init({
			id: "php"	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: "both"
			,allow_toggle: true
			,word_wrap: true
			,language: "en"
			,syntax: "php"	
		});
		
		editAreaLoader.init({
			id: "html"	// id of the textarea to transform	
			,start_highlight: true
			,allow_toggle: false
			,language: "en"
			,syntax: "html"	
			,toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help"
			,syntax_selection_allow: "css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas,brainfuck"
			,EA_load_callback: "editAreaLoaded"
			,show_line_colors: true
		});
		
		editAreaLoader.init({
			id: "css"	// id of the textarea to transform	
			,start_highlight: true	
			,font_size: "8"
			,font_family: "verdana, monospace"
			,allow_resize: "y"
			,allow_toggle: false
			,language: "fr"
			,syntax: "css"	
			,toolbar: "new_document, save, load, |, charmap, |, search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight, |, help"
			,load_callback: "my_load"
			,save_callback: "my_save"
			,plugins: "charmap"
			,charmap_default: "arrows"			
		});	
	</script>
<?php
//Errors*********************
error_reporting(0);
//logging
if(getenv("os")!="Windows_NT"){
$logDir= getenv("DOCUMENT_ROOT").'/includes/logs';
}else{
$logDir= getenv("DOCUMENT_ROOT").'includes/logs';
}
$createLog=$logDir."/editfile.txt";
$file = $_GET['file'];
	if(!file_exists($file))
	{
		die('<span style="color:red">An Error occurred: file does not exists.</span>');
	}
if(preg_match('/\.php/',$file))
{
	$id="php";
}elseif
(preg_match('/\.htm(.*)/',$file))
{
	$id="html";
}elseif
(preg_match('/\.js/',$file))
{
	$id="html";
}elseif
(preg_match('/\.css/',$file))
{
	$id="css";
}else
{
	$id="html";					
}
if($_POST['submit'])
{
	$open = fopen($file,"w+");
	$text = $_POST['update'];
	if(fwrite($open, $text)){
		echo '<span style="color:green">the file successfully has been saved</span>';
			//logging
			$msgFile="File: (".$file.') was edited.';
			if(file_exists($logDir) && is_dir($logDir))
			{
				$open2=fopen($createLog,"a+");
				fwrite($open2, "[".date("y-m-d h:i:s A")."] ".$msgFile."\r\n");
				fclose($open2);
			}else
			{
				mkdir($logDir, 0777);
				$open2=fopen($createLog,"a+");
				fwrite($open2, "[".date('Y-m-d h:i:s A')."] ".$msgFile."\r\n");
				fclose($open2);
			}
	}else{
		echo '<span style="color:red">An Error occurred while saving file.please be sure if you have rights on existing file.</span>';
	}
	fclose($open);
	$fileData = file($file);
	echo "<form action=\"\" method=\"post\">";
	echo " <input type=\"hidden\" name=\"file\" value=\"{$file}\">";
	echo "<textarea  id=\"{$id}\"  Name=\"update\" style=\"width:95%; height:95%\">";
	foreach($fileData as $text) {
	echo htmlentities($text);
	} 
	echo "</textarea>";
	echo "<br /><input name=\"submit\" type=\"submit\" value=\"save\" />\n
	</form>";
}else{
	$fileData = file($file);
	echo "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\">";
	echo " <input type=\"hidden\" name=\"file\" value=\"{$file}\">";
	echo "<textarea  id=\"{$id}\"  Name=\"update\" style=\"width:95%; height:95%\">";
	foreach($fileData as $text) {
	echo htmlentities($text);
	} 
	echo "</textarea>";
	echo "<br /><input name=\"submit\" type=\"submit\" value=\"save\" />\n
	</form>";
}
?>
