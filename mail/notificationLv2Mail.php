<?php

use yii\helpers\Html;


?>
<div class="container" >
    <h3><?= $taskLabel ?> task for booking <?= $booking->booking_number ?> has not completed yet</h3>
    <p>Estimaded date:<strong><?= date('d-m-Y',strtotime($date)); ?></strong></p>
    <p>Please login into the system and complete this task</p>
</div>
