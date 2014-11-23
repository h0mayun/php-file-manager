<?php
include('config.php');
?>
<!--
<script type="text/javascript" src="<?php// echo $localhost;?>includes/js/jquery-1.4.3.min.js"></script>
-->
<script type="text/javascript">
//dd for noconflict with other div ID
function getValue(idval,dirval){
//if dirval exist remove else next function
	if ($("#dd"+dirval).length > 0){
	$("#dd"+dirval).remove();	
	}else
	if ($("#dd"+dirval).length == 0){
	send_get_data(idval,dirval)
	}
};
function send_get_data(idval,dirval){

	$.post("<?php echo $localhost;?>includes/tree.php", { dir: $("#Input_"+dirval).val()},
		function(data){
//apaned sub dir to parents
			$('<div id="dd'+dirval+'">'+data+'</div>').appendTo("#Parent_"+dirval);
		});
};
</script>
<?php 
function tree(){
error_reporting(E_ALL ^ E_NOTICE);
if(getenv("os")!="Windows_NT"){
	$docRoot="//".getenv("DOCUMENT_ROOT");
}else{
	if(substr(getenv("DOCUMENT_ROOT"), -1) == "/")
	$docRoot= substr(getenv("DOCUMENT_ROOT"), 0, -1);
	else
	$docRoot= getenv("DOCUMENT_ROOT");
}
$dirNameTree=$_POST['dir'];
if(isset($_POST['dir'])){
$dirTree =$dirNameTree;
}else{
	$dirTree =$docRoot.$dirNameTree;
}
$projectsListIgnore = array ('.','..','.DS_Store', 'Thumbs.db',"");
$handleTree=opendir($dirTree);
while ($fileSTree[] = readdir($handleTree)) 
{
natcasesort($fileSTree);
}
foreach ($fileSTree as $fileTree)
{
    $dirFileTree= $dirTree."/".$fileTree;
	$nameFileTree=$fileTree;
	$ttt= preg_replace("/:|\/| /", "", $dirFileTree);
	if(strlen($nameFileTree)>12){
		$nameFileTree=substr($nameFileTree,0,12)."...";
	}
	if (is_dir($dirFileTree) &&is_readable($dirFileTree)&& !in_array($fileTree,$projectsListIgnore)) 
	{	
		$projectFoldersTree.= '<li><div id="Parent_'.$ttt.'"><input type="checkbox" id="Input_'.$ttt.'"  value="'.$dirFileTree.'" unchecked/>
		<label onclick="getValue(\'dd'.$fileTree.'\',\''.$ttt.'\')" for="Input_'.$ttt.'"></label>
		<span class="spanLinkTree" onclick="send_get_ajax(\''.$dirFileTree.'\')" title="'.$fileTree.'">'.$nameFileTree.'</span>
		</div></li>'."\n";
	}
}
echo'<ul style="padding-left: 25px;">'."\n";	
echo $projectFoldersTree;
//if no dir
if(strlen($projectFoldersTree)<1){
echo '<i><font color="gray" size="2">No Directory!</font></i>';
}
echo '<ul>'."\n";	
}
tree();
?>