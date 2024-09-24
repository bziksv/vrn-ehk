<?php

if ($_GET['id']) // если имеется get запрос id
{
	$id = $_GET['id'];
	header("Location: https://www.paypal.com/invoice/p/#" . $id); /* Перенаправление браузера */
}
else // если не имеется get запроса id 
{
	$url = $_SERVER['REQUEST_URI']; 
	$url = str_replace( basename($_SERVER['SCRIPT_NAME']) . "/" , "", $url); //оставляем всё что после go.php/ 
	header("Location: https://www.paypal.com/invoice/p/#" . $url); /* Перенаправление браузера */
	
}
exit; //Всё что ниже не будет работать.
?>