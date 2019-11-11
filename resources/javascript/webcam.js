function getSWFObj(movieName) {
	if (navigator.appName.indexOf("Microsoft") != -1) { 
		return window[movieName]; 
	} else { 
		return document[movieName];
	} 
}
function noCamDevice(){
	alert("No Camera detected!");	
}
function loadCam(){
	getSWFObj("captureWebcam")._init(Nexus.noPro_url);
}

function _init(){
	try{
		setTimeout("loadCam()",500);
		setTimeout("loadDataReapeter()",1000);
	}catch(e){
		setTimeout("_init()",1000);
	}
}