/*
 * @package		Easytagcloud
 * @version		2.3
 * @author		Kee 
 * @copyright	Copyright (c) 2012 www.joomlatonight.com. All rights reserved
 */
function initUpdateTime(nextupdatetime){
   var nowtime=new Date();
   endtime=nowtime.getTime()+nextupdatetime;
   showUpdateTime();
}

function resettimenow(){
   var nowtime=new Date();	
   endtime=nowtime.getTime();
}


function showUpdateTime(){  
    var nowtime=new Date();  	
    var t=endtime-nowtime.getTime();  
    var showhour=Math.floor(t/(1000*60*60)) % 24;  
    var showminute=Math.floor(t/(1000*60)) % 60;  
    var showsecond=Math.floor(t/1000) % 60;  
    var showmsecond=Math.floor(t/100) % 10;
    if(t>= 0){  
        document.getElementById("showcountdown").innerHTML=showhour+" hours "+showminute+" minutes "+showsecond+"."+showmsecond+" seconds";    
    }  
    else {  
        document.getElementById("showcountdown").innerHTML=updateLans[0];  
    }  
    setTimeout("showUpdateTime()",100);  
}  

function updateNow(updatequery) {
	var xmlhttp;
    document.getElementById("updatenow").innerHTML=updateLans[1];	
	document.getElementById("updatenow").disabled="disabled";	
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
	xmlhttp.onreadystatechange=function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              document.getElementById("updatenow").innerHTML=updateLans[2];
			  document.getElementById("updatenow").disabled="disabled";
			  resettimenow();
			  
          }
    }

    xmlhttp.open("GET",updatequery,true);
    xmlhttp.send();

	
}
// -->  