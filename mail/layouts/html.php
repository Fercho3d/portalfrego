<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
<style type="text/css">

html {
  font-family: sans-serif;
}

html,body{
  width: 100%;
  height: 100%
}

table {
  border: 1px solid #1C6EA4;
  background-color: #EEEEEE;
  text-align: left;
  border-collapse: collapse;
  border-radius: 5px;
  width: 100% !important;
}

table td, table th {
  border: 1px solid #AAAAAA;
  padding: 3px 2px;
  text-align: left;
  padding-left:5px; 
}

table tbody td {
  font-size: 14px;
}

table thead {
  background: #FFFFFF;
  border-bottom: 2px solid #898989;
}

table thead th {
  font-size: 15px;
  font-weight: bold;
  color: #191717;
  border-left: 2px solid #D0E4F5;
}

table thead th:first-child {
  border-left: none;
}

table tfoot td {
  font-size: 14px;
}
table tfoot .links {
  text-align: right;
}
table tfoot .links a{
  display: inline-block;
  background: #1C6EA4;
  color: #FFFFFF;
  padding: 2px 8px;
  border-radius: 5px;
}

.details{
  width: 100%;
}

.center {
  margin: auto;
  padding: 10px;
}

.header{
  width:100%;
  background: #000; 
  height: 200px;
  text-align: center;
  padding: 25px;
}

.header img{ 
  margin: 25px;
} 

</style>
</head>
<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
