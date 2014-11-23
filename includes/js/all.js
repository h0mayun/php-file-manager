jQuery.noConflict();
jQuery(document).ready(function($) {
$(".pp_pic_holder").remove();
 $(".pp_overlay").remove();
 $(".ppt").remove();
$(".gallery a[rel^='prettyPhoto']").prettyPhoto({theme:'h0mayun'});
});
//close iframe button for NO answer
function closeTheIFrameImDone()
{
	jQuery.prettyPhoto.close();
}
//go input validating
function checkForm(form)
{
	if(form.dirGo.value.charAt( form.dirGo.value.length-1 ) == "/") {
		 var formVal= form.dirGo.value.slice(0, -1) ;
		 send_get_ajax(formVal);
		return false;
	}else{
		if(form.dirGo.value == ''|form.dirGo.value == '/') {
		  form.dirGo.focus();
		  return false;
		}
		var formVal = form.dirGo.value;
		send_get_ajax(formVal);
		return false;
	}
}
// for file: dirFile=dirFileSub||||href=dirFile |||||dirName=fileSub ||||file=editHref
function menu( e, id ,spanid,dirFile,href,dirName,file,ident,favelink) 
{
	$('.actionmenu').hide();
	if(ident=='dir')
	{
		var favelink='<span class=\\\'spanLink\\\' onclick=\\\'tes(this.title)\\\' title=\\\''+dirFile+'\\\'>'+file+'</span>';

		var d = document.getElementById(id); 
		d.innerHTML = '	<div class="gallery clearfix"><span class="closeframe"  onclick="$(\'#'+spanid+'\').fadeOut();$(\'.actionmenu\').show();" ></span> \
		<input type="text" value="'+dirFile+'"  title="'+dirFile+'" onfocus="this.select()"  readonly="readonly"/><br /><br /><ul> \
		<li><span class="icon"><a href="#"  title="add to favorites" onclick="getCommand(\' '+favelink+' \',\'cc\');return false"><span class="favorite">Add to favorites</span></a></span></li> \
		<li><span class="icon"><a  href="'+href+'" target="iframe" ><span class="iframefolder" title="Preview">Preview</span></a></span></li> \
		<li><span class="icon"><a href="'+href+'" target="_blank" ><span class="newtab" title="open in new tab">Open in new tab</span></a></span></li> \
		<li><span class="icon"><a href="includes/delete.php?folder='+dirFile+'&amp;dir='+dirName+'&amp;filename='+file+';iframe=true&amp;width=400&amp;height=170"  rel="prettyPhoto[iframe]" ><span class="delete" title="delete">Delete</span></a></span></li> \
		<li><span class="icon"><a href="includes/rename.php?dir='+dirName+'/&amp;filename='+file+';iframe=true&amp;width=400&amp;height=170"  rel="prettyPhoto[iframe]" ><span class="rename" title="rename">Rename</span></a></span></li> \
		<li><span class="icon"><a href="includes/paste.php?dirpaste='+dirName+'/&amp;filepaste='+file+'&amp;dir='+dirName+';iframe=true&amp;width=600&amp;height=270"  rel="prettyPhoto[iframe]" ><span class="copy" title="copy">Copy</span></a></span></li></ul></div> ';

	}else if(ident=='file' && file=='')
		{
		var d = document.getElementById(id); 
		var favelink='<a href=\\\''+favelink+'\\\' title=\\\''+dirFile+'\\\' target=\\\'iframe\\\' id=\\\''+dirName+'\\\' onclick=\\\'iframemenu(this.href,this.title,this.id)\\\'>'+dirName.substr(0,13)+'</a>';

		d.innerHTML = '<div><span class="closeframe"  onclick="$(\'#'+spanid+'\').fadeOut();$(\'.actionmenu\').show();" ></span> \
		<input type="text" value="'+dirFile+'"  title="'+dirFile+'" onfocus="this.select()"  readonly="readonly"/><br /><br /><ul> \
		<li><span class="icon"><a href="'+dirFile+'"  title="add to favorites" onclick="getCommand(\''+favelink+'\',\'cc\');return false"><span class="favorite">Add to favorites</span></a></span></li> \
		<li><span class="icon"><a href="includes/delete.php?file='+dirFile+'&amp;dir='+href+'&amp;filename='+dirName+';iframe=true&amp;width=400&amp;height=170"  rel="prettyPhoto[iframe]" ><span class="delete" title="delete">Delete</span></a></span></li> \
		<li><span class="icon"><a href="includes/rename.php?dir='+href+'/&amp;filename='+dirName+';iframe=true&amp;width=400&amp;height=170"  rel="prettyPhoto[iframe]" ><span class="rename" title="rename">Rename</span></a></span></li> \
		<li><span class="icon"><a href="includes/paste.php?dirpaste='+href+'/&amp;filepaste='+dirName+'&amp;dir='+href+';iframe=true&amp;width=600&amp;height=270"  rel="prettyPhoto[iframe]" ><span class="copy" title="copy">Copy</span></a></span></li></ul></div> ';
	}else if(ident=='file' && file=='edit' )
	{
		var favelink='<a href=\\\''+favelink+'\\\' title=\\\''+dirFile+'\\\' target=\\\'iframe\\\' id=\\\''+dirName+'\\\' onclick=\\\'iframemenu(this.href,this.title,this.id)\\\'>'+dirName.substr(0,13)+'</a>';	
			
		var d = document.getElementById(id);
		d.innerHTML = '	<span class="closeframe"  onclick="$(\'#'+spanid+'\').fadeOut();$(\'.actionmenu\').show();" ></span> \
		<input type="text" value="'+dirFile+'"  title="'+dirFile+'" onfocus="this.select()"  readonly="readonly"/><br /><br /><ul> \
		<li><span class="icon"><a href="'+dirFile+'"  title="add to favorites" onclick="getCommand(\''+favelink+'\',\'cc\');return false"><span class="favorite">Add to favorites</span></a></span></li> \
		<li><span class="icon"><a href="includes/editfile.php?file='+dirFile+'" target="_blank" ><span class="edit" title="edit">Edit</span></a></span></li> \
		<li><span class="icon"><a href="includes/delete.php?file='+dirFile+'&amp;dir='+href+'&amp;filename='+dirName+';iframe=true&amp;width=400&amp;height=170"  rel="prettyPhoto[iframe]" ><span class="delete" title="delete">Delete</span></a></span></li> \
		<li><span class="icon"><a href="includes/rename.php?dir='+href+'/&amp;filename='+dirName+';iframe=true&amp;width=400&amp;height=170"  rel="prettyPhoto[iframe]" ><span class="rename" title="rename">Rename</span></a></span></li> \
		<li><span class="icon"><a href="includes/paste.php?dirpaste='+href+'/&amp;filepaste='+dirName+'&amp;dir='+href+';iframe=true&amp;width=600&amp;height=270"  rel="prettyPhoto[iframe]" ><span class="copy" title="copy">Copy</span></a></span></li></ul></div> ';
	}
	jQuery.noConflict();
	jQuery(document).ready(function($) {
		$(".pp_pic_holder").remove();
		 $(".pp_overlay").remove();
		 $(".ppt").remove();
		$(".gallery a[rel^='prettyPhoto']").prettyPhoto({theme:'h0mayun'});
	});
	//displaying menu
		//icon menu
	  var el2 = document.getElementById(spanid);
		el2.className = ( el2.className == 'actionmenuselect' ) ? 'actionmenu' : 'actionmenuselect';
		menu.el2 = el2;
	//div menu
	  var el = document.getElementById(id);
	  el.style.display = ( el.style.display == 'block' ) ? 'none' : 'block';
	  // save it for hiding
	  menu.el = el;

	  // stop the event right here
	  if ( e.stopPropagation )
		e.stopPropagation();
	  e.cancelBubble = true;
	  return false;
}
function iframemenu2 (a,b,c){
	alert(a);
	alert(b);
	alert(c);
}
// click outside the div
document.onclick = function() {
	if ( menu.el2 ) {
		menu.el2.className = " actionmenu";
	}
  if ( menu.el ) 
  {
	menu.el.style.display = 'none';
	$('.actionmenu').show();
  }
}
//////////////////START FAVES
function tes(id){
	send_get_ajax(id);
}
//Read cookie and do command
function getCommand(value,cmd){	 
var name="favorites";
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		var re;
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0)
		re= c.substring(nameEQ.length,c.length);
	}
	switch(cmd)	 {	 case "cc":	createCookie(name,value,re);	  break;	  case "rc":	 rCookie(name,value,re);	  break;	  case "rdc":	 readCookie(name,re);	  break;	  case "wrdc":	 writeCookie(name,re);	  break;	 	 }

}
 //Create favorite
function createCookie(name,value,re) {
	//count links
	var count=0;
	var ca = unescape(re).split('|');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		count++;
	}
	//allow only 20 faves
	if(count<21){
		if (re==undefined){
			data=escape(value);
		}else{
			data=re+escape(value);
		}
		
		days=3000; // number of days to keep the cookie
	   myDate = new Date();
	   myDate.setTime(myDate.getTime()+(days*24*60*60*1000));
		document.cookie = name+"="+data+"|; expires="+myDate.toGMTString() +"; path=/";
		//after creating remove div content
		document.getElementById('cookies').childNodes[0].innerHTML="";
		//load and write cookies again
		$('#cookies').slideDown();
		$('#faveex').fadeIn();
		getCommand("","wrdc");
		$('#faveex').delay(900).fadeOut(300);
		$('#cookies').delay(1000).slideUp(400);
	}else{
			$("#msg").animate({width:'toggle'},350);
			document.getElementById('msg').innerHTML ='Sorry,You Have Exceeded the Maximum Number of Favorite Items.Currently you can only add 20 Items. \
			<br /><br /><button onclick="$(\'#msg\').animate({width:\'toggle\'},350);">Ok</button> \
			';
	}
}
 //delete specific fav link
function rCookie(name,value,re) {
	days=3000; // number of days to keep the cookie
   myDate = new Date();
   myDate.setTime(myDate.getTime()+(days*24*60*60*1000));
	av=re.replace(escape(value)+"|","");
	var expires = "";
	document.cookie = name+"="+av+"; expires="+myDate.toGMTString() +"; path=/";
	//after creating remove div content
	document.getElementById('cookies').childNodes[0].innerHTML="";
	//load and write cookies again
	getCommand("","wrdc");
}

//Export
function readCookie(name,re) 
{
	var d = document.getElementById("export"); 
	$('#export').fadeIn();
	d.innerHTML ='<div style="padding:20px;"><ol> \
	<li>1) Copy the code inside the box</li> \
	<li>2) Create a html file </li> \
	<li>3) Paste code into it</li> \
	<li>4) Open the html file and follow the instruction</li> \
	</ol><div style="padding-left:50px;"> \
	<textarea onclick="this.select()" style="width:400px;height:150px;background:#dedede" readonly="readonly"> \
	<script> \
	function importcookie(){ \
	days=3000;myDate = new Date();myDate.setTime(myDate.getTime()+(days*24*60*60*1000));document.cookie = "'+name+'='+re+'; expires="+myDate.toGMTString() +"; path=/"; \
	document.getElementById(\'import\').innerHTML +="Your favorite list has been successfully imported"; \
	} \
	</script> \
	<a href="#" onclick="importcookie();return false;">Click to import list!</a> \
	<div id="import"></div> \
	</textarea></div> \
	<span style="float:right;margin-top:60px;"><a href="#" onclick="$(\'#export\').fadeOut();return false;">Close!</a></span> \
	</div>';
}
//show faves2
function writeCookie(name,re) {	
	document.getElementById('cookies').childNodes[0].innerHTML="";
	var ca = unescape(re).split('|');
	if(re==undefined||re==""){
		document.getElementById('cookies').childNodes[0].innerHTML+='<li style="float:left;font-size:19px;color:#900000">There is currently no favorite has been added.</li>';
	}else{
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			if(c!=""){
				document.getElementById('cookies').childNodes[0].innerHTML += '<li class="fileli" style="padding-left:50px;float:left;width:150px;"><span style="padding-right:5px;">'+c+
				'</span><span class="favedelete" onclick="getCommand(\''+c.replace(/'/g, "\\'")+'\',\'rc\');return false;"></span></li>';
			}
		}
	}
	//count links
	var count=-1;
	var ca = unescape(re).split('|');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		count++;
	}
	document.getElementById('count').innerHTML ="Links: "+count+"/20";
}

//top slider ;)
function habala(){
	$('#faveex').slideToggle();
	$('#cookies').slideToggle();
}
//clear fave list
function eraseCookie() {
	$("#msg").animate({width:'toggle'},350);
	document.getElementById('msg').innerHTML ='Are You Sure You Want to Clear List? \
	<br /><br /><button id="clear" onclick="clearlist();">Yes</button> \
	<button id="clearno" onclick="$(\'#msg\').animate({width:\'toggle\'},350);">No</button> \
	';
}
function clearlist(){
	document.cookie = "favorites=;expires=Thu, 01-Jan-1970 00:00:01 GMT; path=/";
	document.getElementById('cookies').childNodes[0].innerHTML="";
	getCommand("","wrdc");
	document.getElementById('msg').innerHTML ="Your favorite list has been cleared.";
	$('#msg').delay(1900).animate({width:'toggle'},350);
	$('#cookies').delay(2200).slideToggle();
}

function iframemenu(url,edit,name)
{
	var favelink='<a href=\\\''+url+'\\\' onclick=\\\'iframemenu(this.href,this.title,this.id)\\\' title=\\\''+edit+'\\\' target=\\\'iframe\\\'>'+name.substr(0,13)+'</a>';
	var d = document.getElementById("iframeinfo"); 
	d.innerHTML = '<ul style="display:inline"> \
	<li style="display:inline"><a  href="'+url+'" target="_blank" >'+name+'</a></li> \
	<li style="display:inline"><span class="icon"><span class="reload" title="Larger Screen" onclick="document.getElementById(\'iframeid\').contentWindow.location.reload();" />|</span></li> \
	<li id="expandscreen" style="display:inline"><span class="icon"><span class="expandscreen" title="Larger Screen" onclick="expandscreen();" />|</span></li> \
	<li id="contract" style="display:none;"><span class="icon"><span class="contract" title="Smaller Screen" onclick="contract();" />|</span></li> \
	<li style="display:inline"><span class="icon"><a  href="'+url+'" target="_blank" ><span class="newtab" title="open in new tab"></span></a>|</span></li> \
	<li style="display:inline"><span class="icon"><a href="includes/editfile.php?file='+edit+'" target="_blank" ><span class="edit" title="edit"></span></a>|</span></li> \
	<li style="display:inline"><span class="icon"><a href="'+edit+'"  title="add to favorites" onclick="getCommand(\''+favelink+'\',\'cc\');return false"><span class="favorite"></span></a>|</span></li> \
	</ul> ';
}
//resize iframe
function expandscreen()
{
	$("#expandscreen").hide();
	$("#contract").css('display', 'inline');		
	$(".roots").animate({'width':'6%'});		
	$(".iframeHolder").animate({'width':'88%'});		
	$("#content .tableHolder").animate({'height':'80%'});		
	
}
function contract(){
	$("#expandscreen").css('display', 'inline-block');
	$("#contract").hide();
	$(".roots").animate({'width':'12%'});		
	$(".iframeHolder").animate({'width':'76%'});	
	$("#content .tableHolder").animate({'height':'50%'});		
}