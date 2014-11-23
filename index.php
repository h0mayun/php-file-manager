<?php
    /***************************
	 * PHP FILE MANAGER  *	
	  * Please Note that this script has written a long time ago when
			i just started coding and since then i did not work on the code
			So code is pretty messy
			
	 * homayoon.info *
     ***************************/

error_reporting(0);
$u_agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/MSIE/i',$u_agent))
{
	die('Sorry,We currently do not support Internet Explorer. please use a standard web browser.');
}
include('includes/config.php');
if(getenv("os")!="Windows_NT"){
	$docRoot="//".getenv("DOCUMENT_ROOT");
}else{
	if(substr(getenv("DOCUMENT_ROOT"), -1) == "/")
	$docRoot= substr(getenv("DOCUMENT_ROOT"), 0, -1);
	else
	$docRoot= getenv("DOCUMENT_ROOT");
}
$strReplace=array($docRoot=>"");
//remove special characters
$RsC='/:|\/| /';
// *******IGNOR ROOT***********************************
$projectsListIgnore = array ('.','..','.DS_Store', 'Thumbs.db','desktop.ini');
// *******go button *************************
if (isset($_GET['dirGo'])){
	 if (substr($_GET['dirGo'], -1) == '/'){
		$dirName=substr($_GET['dirGo'],0, -1);
	 }else{
		$dirName=$_GET['dirGo'];
	 } 
}
// *******Dir*************************
if (isset($_GET['dir'])){
	$dirName=$_GET['dir'];
}else{
	$dirName=$docRoot;
}
	// *******check if page exsist except root ( \ )
if(isset($dirName) && $dirName!="."){
	if (!file_exists( $dirName)) {
		die( 'The page '.$dirName.'	does not exist 
		<span class="icon"><span onclick="send_get_ajax(\''.$docRoot.'\')"><img src="includes/images/home.png" title="'.$localhost.' | '.$docRoot.'" /></span></span>&nbsp;&nbsp;');
	}
}
//back button###############
$back=dirname($dirName);
if($back=='\\'||$back=='/'||$back==''){
	$back=$docRoot;
}
//avoid display backbutton in root
if($dirName!=$docRoot){
$backButton='<span class="icon"><span onclick="send_get_ajax(\''.$back.'\')" ><img src="includes/images/back.png" title="back" /></span></span>&nbsp;&nbsp;';
}else{
$backButton="";
}
$showDir=$backButton.'
<span class="icon"><span onclick="send_get_ajax(\''.$dirName.'\')"><img src="includes/images/reload.gif" title="reload" /></span></span>&nbsp;&nbsp;
<span class="icon"><span onclick="send_get_ajax(\''.$docRoot.'\')"><img src="includes/images/home.png" title="'.$localhost.' | '.$docRoot.'" /></span></span>&nbsp;&nbsp;

<span style="color:#666666;text-shadow: 3px 0px 6px #fff;">
<form style="display: inline;" method="get" action="" onsubmit="javascript:return false;">
<input type="text" style="width:70%;" name="dirGo"  value="'.$dirName.'" onfocus="if(this.value==\'\') { this.value=\'/\'; }" onkeydown="javascript:if (event.which || event.keyCode){if ((event.which == 13) || (event.keyCode == 13)) {checkForm(this.form);}};"/>
<input type="button" value="Go" onClick="checkForm(this.form);"/>
</form>
</span>
';
///Open dir
$handle=opendir($dirName);
while ($files[] = readdir($handle)) 
{
natcasesort($files);
}
foreach ($files as $file)
{
	$dirFile= $dirName."/".$file;
	$nameFile=$file;
	$idAjax=preg_replace($RsC, "", $dirFile);
	if(strlen($nameFile)>10){
		$nameFile=substr($nameFile,0,10)."...";
	}
// *******LIST ROOT FOLDERS OPEN THEM************  
	if (is_dir($dirFile) &&is_readable($dirFile)&& !in_array($file,$projectsListIgnore)&&$nameFile!='') 
	{		
		$projectFolders .= '<th class="gallery clearfix"><li><span class="spanLink" onclick="send_get_ajax(\''.$dirFile.'\')" title="'.$file.'">'.$nameFile.'</span>
		</li></th>';
		$projectFolders2 .= '<li><span class="spanLink" onclick="send_get_ajax(\''.$dirFile.'\')" title="'.$file.'">'.$nameFile.'</span>
		<span class="icon"><span id="span_'.preg_replace($RsC, "", $dirFile).$file.'" class="actionmenu" title="Action menu" onclick="menu(event, \''.preg_replace($RsC, "", $dirFile).$file.'\', \'span_'.preg_replace($RsC, "", $dirFile).$file.'\',\''.$dirFile.'\',\''.strtr($dirFile,$strReplace).'\',\''.$dirName.'\',\''.$file.'\',\'dir\')"></span></span>
		<div id="'.preg_replace($RsC, "", $dirFile).$file.'" class="menuaction"><br /></div></li>';	
// *******LIST ALL CHILD FILES ************************	
		$handleSub=opendir($dirFile);
		while ($filesSub[$dirFile][] = readdir($handleSub)) 
		{
		natcasesort($filesSub[$dirFile]);
		}
		foreach ($filesSub[$dirFile] as $fileSub)
		{
			$dirFileSub= $dirFile."/".$fileSub;
			$nameFileSub=$fileSub;
			if(strlen($nameFileSub)>10){
				$nameFileSub=substr($nameFileSub,0,10)."...";
			}
// *******LIST CHILD FOLDERS OPEN THEM************  			
			if (is_dir($dirFileSub)&&is_readable($dirFileSub)&&!in_array($fileSub,$projectsListIgnore)&& $nameFileSub!='') 
			{	
				$projectChilderens[$dirFile]['dir'] .= '<li><span class="spanLink" onclick="send_get_ajax(\''.$dirFileSub.'\')" title="'.$fileSub.'">'.$nameFileSub.'</span>
				<span class="icon"><span id="span_'.preg_replace($RsC, "", $dirFileSub).$fileSub.'" class="actionmenu" title="Action menu" onclick="menu(event, \''.preg_replace($RsC, "", $dirFileSub).$fileSub.'\', \'span_'.preg_replace($RsC, "", $dirFileSub).$fileSub.'\',\''.$dirFileSub.'\',\''.strtr($dirFileSub,$strReplace).'\',\''.$dirFile.'\',\''.$fileSub.'\',\'dir\')"></span></span>
				<div id="'.preg_replace($RsC, "", $dirFileSub).$fileSub.'" class="menuaction"><br /></div></li>';
			}
			elseif (!is_dir($dirFileSub)&&!in_array($fileSub,$projectsListIgnore))
			{
				// *******SHOW EDIT LINK ONLY FOR THESE FILES***
				if(preg_match('/\.php|\.htm(.*)|\.js|\.txt|\.css|\.xml|\.doc|\.inc/',strtolower($fileSub)))
				{
					$editHref='edit';
					$class="fileli";
				}else{
					$editHref='';
					$class="fileli";
					if(!preg_match('/\.jpg|\.png|\.gif|\.pdf|\.swf|\.zip|\.rar|\.tar|\.bmp|\.psd|\.jpeg/',strtolower($fileSub)))
					{
						$class="other";
					}
				}
					$projectChilderens[$dirFile]['files'] .= '<li class="'.$class.'"><a href="'.strtr($dirFileSub,$strReplace).'" target="iframe" title="'.$fileSub.'" onclick="iframemenu(\''.strtr($dirFileSub,$strReplace).'\',\''.$dirFileSub.'\',\''.$fileSub.'\')">'.$nameFileSub.'</a>
					<span class="icon"><span id="span_'.preg_replace($RsC, "", $dirFileSub).$fileSub.'" class="actionmenu" title="Action menu" onclick="menu(event,\''.preg_replace($RsC, "", $dirFileSub).$fileSub.'\',\'span_'.preg_replace($RsC, "", $dirFileSub).$fileSub.'\', \''.$dirFileSub.'\',\''.$dirFile.'\',\''.$fileSub.'\',\''.$editHref.'\',\'file\',\''.strtr($dirFileSub,$strReplace).'\')"></span></span>
					<div id="'.preg_replace($RsC, "", $dirFileSub).$fileSub.'" class="menuaction"><br /></div></li>';
			}
		}
	}
	elseif (!is_dir($dirFile) && !in_array($file,$projectsListIgnore)) 
// *******LIST ROOT FILES *********************** 
	{
		if(preg_match('/\.php|\.htm(.*)|\.js|\.txt|\.css|\.xml|\.doc|\.inc/',strtolower($file)))
		{
			$editHref='edit';
			$class="fileli";
		}else{
			$editHref='';
			$class="fileli";
			if(!preg_match('/\.jpg|\.png|\.gif|\.pdf|\.swf|\.zip|\.rar|\.tar|\.bmp|\.psd|\.jpeg/',strtolower($file)))
			{
				$class="other";
			}
		}
			$projectFiles .= '<li class="'.$class.'"><a href="'.strtr($dirFile,$strReplace).'" target="iframe" title="'.$file.'" onclick="iframemenu(\''.strtr($dirFile,$strReplace).'\',\''.$dirFile.'\',\''.$file.'\')">'.$nameFile.'</a>
			<span class="icon"><span id="span_'.preg_replace($RsC, "", $dirFile).$file.'" class="actionmenu" title="Action menu" onclick="menu(event,\''.preg_replace($RsC, "", $dirFile).$file.'\',\'span_'.preg_replace($RsC, "", $dirFile).$file.'\', \''.$dirFile.'\',\''.$dirName.'\',\''.$file.'\',\''.$editHref.'\',\'file\',\''.strtr($dirFile,$strReplace).'\')"></span></span>
			<div id="'.preg_replace($RsC, "", $dirFile).$file.'" class="menuaction"><br />
			</div></li>';
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>LocalHost Home Page</title>
<link rel="stylesheet" type="text/css" href="includes/css.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="includes/images/favicon.ico" />
<!--ajax-->
<script type="text/javascript" src="includes/js/jquery-1.4.3.min.js"></script>
<script type="text/javascript">
function send_get_ajax(idval){
	$('#content').hide();
	$('#loading').fadeIn();
	$('#shader').show();
	$.get("index.php", { dir: idval},
	function(data){
	$('#content').html(data);
	$('#content').show();
	$('#loading').hide();
	$('#shader').hide();
	//faves
	getCommand("","wrdc");
	});
};
</script>
<script type="text/javascript" src="includes/js/jquery-1.4.3.min.js"></script>
<script src="includes/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
//close iframe button
function closePretty()
{
var dirName ='<?php echo $dirName; ?>';
 jQuery.prettyPhoto.close();
 send_get_ajax(dirName);
}
 
//<!--iframe show index-->
 function makeFrame() { 
document.getElementById("iframeid").src = "<?php if(strtr($dirFile,$strReplace)!="/")	echo strtr($dirFile,$strReplace); ?>";
 }
</script>
<script type="text/javascript" src="includes/js/all.js"></script>
</head>
<body  onload='getCommand("","wrdc");return false'>
<div id="loading"></div>
<div id="shader"></div>
<div id="cookies" class="topmenu"><ul></ul>
	<div id="faveex">
		<ul>
			<li><a href="#" onclick='eraseCookie();return false'>Clear list!</a></li>
			<li><a href="#" onclick='getCommand("","rdc");return false'>Export list!</a></li>
			<li><div id="count"></div></li>
		</ul>
	</div>
</div>
<div id="content">
<div class="faves">
	<span class="favorite"><a href="#" onclick='habala();return false'>Favorites</a></span>
</div>

<div id="export"></div>
<div id="msg"></div>
<div class="tableHolder">
<?php echo $showDir; ?>

<table class="projectFoldersTbl" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%">
	<tr>
		<th class="td roots">
			<b>Root Files</b>
		</th>
		<th class="td roots" >
			<b>Root Folders</b>
		</th>
		<th class="td iframeHolder">
			<div id="iframeinfo" style="display:inline;width:400px;float:left;"></div>
			<div style="float:right;padding-right:20px;padding-top:5px">
				<a href="#" onMouseDown="makeFrame()">Show index!</a> | 
				<?php echo "<a href=\"".substr(strtr($dirFile,$strReplace), 0, -1)."/\" target=\"_blank\" ><b>Index in new tab</b></a>"; ?>
			</div>
		</th>
	</tr>
	<tr>
		<td valign="top" >
			<div>
			<ul class="gallery clearfix">	
				<li class="add"><a href="includes/create.php?createfile=<?php echo $dirName."/"; ?>&amp;dir=?dir=$dirName;iframe=true&amp;width=400&amp;height=170" id="newfile" rel="prettyPhoto[iframe]">New File</a></li>
				<?php echo $projectFiles; ?>
			</ul>
			</div>
		</td>
		<td  valign="top" >
			<div>		
			<ul class="gallery clearfix">
				<li class="add"><a href="includes/create.php?createfolder=<?php echo $dirName."/"; ?>&amp;dir=?dir=$dirName;iframe=true&amp;width=400&amp;height=170" id="newfolder" rel="prettyPhoto[iframe]">New Folder</a></li>				
				<?php echo $projectFolders2; ?>
			</ul>
			</div>			
		</td>
		<td valign="top" style="background:#fdfdfd">
		<iframe id ="iframeid" name="iframe" width="100%" height="100%" src="" frameborder="0" onload="$(this).height(this.contentDocument.documentElement.scrollHeight + 30) + 'px';"></iframe>
		</td>
  </tr>	
</table>
</div>
<div class="tableHolder">
<table class="projectFoldersTbl" border="1" cellpadding="0"  cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%">
  <tr>
	<?php echo $projectFolders; ?>
  </tr>
  <tr>
	<ul>
	<?php
		foreach ($projectChilderens as $FilesSub)
		{
			echo '<td class="gallery clearfix" valign="top">'.$FilesSub['dir'].$FilesSub['files'] ."</td>";
		}
	?>
	</ul>
  </tr>
</table>
</div>
</div>
<!--FOOTER-->
<div id="foot">
	&nbsp;|&nbsp;<a href="http://homayoon.info/" target="_blank">PHPFileManger BY Homayoon</a>&nbsp;|&nbsp;
	<span class="gallery clearfix"><a href="includes/phpinfo.php;iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]">Server Information</a>&nbsp;|&nbsp;</span>		
	<a href="<?php echo $phpMyadmin;?>" target="_blank">PhpMyAdmin</a>&nbsp;|&nbsp;
	
</div>
<!--END:FOOTER-->

<!--Tree Panel-->
<?php
if ($_GET['dir']==''){
echo'
 <script type="text/javascript" src="includes/js/slider.js"></script>
<div class="slidebar" onclick="initSlideLeftPanel();return false"><div  class="sidebarImg"><img src="includes/images/arright.png" /></div> </div> 
<div id="dhtmlgoodies_leftPanel">
<div class="slidebar" onclick="initSlideLeftPanel();return false"><div  class="sidebarImg"><img src="includes/images/arleft.png" /></div></div> 
<div id="leftPanelContent">
<div style="height:680px;overflow:auto;">
';
include('includes/tree.php');
echo'</div></div></div>';
}
?>
</body>
</html>

