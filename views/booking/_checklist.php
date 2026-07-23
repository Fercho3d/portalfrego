<?php 
use kartik\checkbox\CheckboxX;
use \kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$chekFields = [
'arrival',
'realeased_from_shiping',
'customs_cleared',
'truck_service_request',
'delivered_consigned',
];


$label = [
'Arrival',
'Realeased From Shiping',
'Customs Cleared',
'Truck Service Request',
'Delivered consigned',
];

function dDate($date){

	return empty($date) ? '': date("d-m-Y h:i:s A", strtotime($date));
}

$pluginOptions =  [
	'autoclose'=>true,
	'format' => 'dd-m-yyyy HH:ii:ss P'
];

if($booking->locked) { 
	$readOnly = true;
  }

?> 

<div class="col-md-12" >
	<table class="table table-condensed table-striped">
		<thead>
			<th>Field</th>
			<th>Check</th>
			<th>Checked Date</th>
		</thead>
			<tbody>
			<?php foreach ($chekFields as $key => $field): ?>
				<tr>
				   <td><?= $label[$key] ?></td>
				   <td>
				   	<div style="width:190px" class="checkList_cont" data-field="<?= 'booking-'. $field  ?>"  >
	                      <?=  CheckboxX::widget([
	                          'name'=> 'booking-'. $field. '_chk',
	                          'disabled'=> true,
	                          'value' => (int) !empty($booking[$field]),
	                          'options'=>['id'=> 'booking-'. $field . '_chk' ],
	                          'pluginOptions'=>[ 'threeState'=> false ] 
	                          ]); 
	                      ?>
	                </div>
				   </td>
				   <td>
		           <div style="width:250px" class="checkList_date"  data-field="<?= 'booking-'. $field . '_chk'  ?>" >
		                <?=  DateTimePicker::widget([
		                      'name'=> 'Booking['.$field.']',
		                      'id'=>'booking-'. $field,
		                      'disabled'=> true,
		                      'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
		                      'value' => dDate($booking[$field]),
		                      'pluginOptions' => $pluginOptions 
		                  ]);
		                 ?>
			        </div>
			        </td>
				</tr>
		    <?php endforeach; ?>
			</tbody>
	</table>

</div>		


<script type="text/javascript">
	
	function today(){
		let date = new Date()
		let day = date.getDate()
		let month = date.getMonth() + 1
		let year = date.getFullYear()
		let hours = date.getHours()
		let minutes = date.getMinutes();
		let seconds = date.getSeconds();

		 day = day < 10 ?  '0' + day : day; 
		 month = month < 10 ? '0' + month : month; 
		 hours = hours < 10 ?  '0' + hours : hours; 
		 minutes = minutes < 10 ? '0' + minutes  : minutes; 
		 seconds = seconds < 10 ? '0' + seconds  : seconds; 
		
			return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
		
		}


	$(document).ready(function(){

	$('.checkList_cont input').each(function(){
		var checkElement = $(this);
		var id = checkElement.attr('id');
		var dateField =  $('#'+ checkElement.parent().data('field') );

		checkElement.change(function(){

			dateField.val(checkElement.val() == 1 ? today(): '');
	
		});

		dateField.change(function(){
			
			var dateField = $(this);

			if(dateField.val().length === 0){
				checkElement.val(0);
				checkElement.checkboxX({value:1});
				checkElement.prev().children('.cbx-icon').html('');
			}else{ 
				checkElement.val(1);
				checkElement.checkboxX({value:1});
				checkElement.prev().children('.cbx-icon').html('<i class="glyphicon glyphicon-ok"></i>');
			}

		});	

	});
});

</script>