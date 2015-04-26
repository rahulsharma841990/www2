<?php

include_once "_AjaxLib.php";


$ajax = new _AjaxLib();

$fields['url']="http://google.com";
$fields['num']="2123";
$ajax->init();
$ajax->open();
//$ajax->alert('hello');
$ajax->set_ajax('http://abc.com',$fields,$ajax->alert('Working',true),$ajax->alert('Not Working',true));
$ajax->close();

?>