$(document).ready(function(){
	var url = document.location.toString();
	if (url.match('tab=')) {
    	$('.nav-tabs a[href="#' + url.split('tab=')[1] + '"]').tab('show');
	} 

	//Change hash for page-reload
	$('.nav-tabs a').on('shown.bs.tab', function (e) {
    	//window.location.hash = e.target.hash;
	})
});