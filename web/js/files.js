$(document).ready(function(){
	console.log('files');
	$("input").on("filepredelete", function(jqXHR) {
	        var abort = true;
	        if (confirm("Are you sure you want to delete this item?")) {
	            abort = false;
	        }
	        return abort; // you can also send any data/object that you can receive on `filecustomerror` event
	    });
});
