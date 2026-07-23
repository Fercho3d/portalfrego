function markChecked(field,dateField,booking_id){
	$.ajax({
	  method: "POST",
	  url: "check?booking_id=" + booking_id,
	  data:{
	  	field: field
	  },
	}).done(function(data) {
		if(data.success){
			$('#'+dateField).val(data.date);
			if(!isAdmin){
				$('#'+dateField).attr('readonly', true);
			}
			
		}else{
			alert(data.msg);
		}
	});
}



function markUnchecked(field,dateField,booking_id){
	$.ajax({
	  method: "POST",
	  url: "uncheck?booking_id=" + booking_id,
	  data:{
	  	field: field,
	  },
	}).done(function(data) {
		if(data.success){
			$('#'+ dateField).val(data.date);
		}else{
			alert(data.msg);
		}
	});
}


function setDate(field,checkField,date,booking_id){
	$.ajax({
	  method: "POST",
	  url: "setdate?booking_id=" + booking_id,
	  data:{
	  	field: field,
	  	 date: date
	  },
	}).done(function(data) {
		if(data.success){
			if(date.lenght == 0){
				$('#'+checkField).val(0);
				$('#'+checkField).checkboxX({value:1});
				$('#'+checkField).prev().children('.cbx-icon').html('');
			}else{ 
				$('#'+checkField).val(1);
				$('#'+checkField).checkboxX({value:1});
				$('#'+checkField).prev().children('.cbx-icon').html('');
			}
		}else{
			alert(data.msg);
		}
	});
}


$(document).ready(function(){

	$('.checkList_cont input').each(function(){
		var element = $(this);
		var id = element.attr('id');
		required =  element.parent().data('required');
		var prevEl = $('#' + required + '_chk' );
		
		if(prevEl.length == 1 ){
			var prevVal = prevEl.val();
			if(prevVal == 0){
				element.parent().first().hide();		
			}
		}
		prevEl.change(function(){
			if(prevEl.val() == 1 ){
				console.log(element.prev().parent().parent());
				element.prev().parent().parent().show();	
			}
		})
	});


	$('.checkList_cont input').change(function(){
		var element = $(this);
		var field = element.parent().parent().data('field');
		var booking_id = element.parent().parent().data('booking');
		var dateField = field + '_chk_date';
		element.parent();
		var value = element.val();
		if (value==1){
			markChecked(field,dateField,booking_id);
			if(!isAdmin){ 
				element.prev().addClass('cbx-disabled');
			}
			//$('#'+dateField).attr('disabled', true);
			//$('#'+dateField).next().off();
			//$('#'+dateField).next().next().off();
		}else{
			markUnchecked(field,dateField,booking_id);
		}
	});	

	$('.checkList_date .input-group.date input').change(function(){
		var element = $(this);
		var field = element.parent().parent().data('field');
		var booking_id = element.parent().parent().data('booking');
		var dateField = field + '_chk_date';
		var checkField = field + '_chk';
		var date = element.val();
		var value = element.val();
		setDate(field,checkField,date,booking_id);
	});
});




