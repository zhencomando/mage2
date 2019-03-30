<?php
use Magento\Framework\App\Bootstrap;

require  '../app/bootstrap.php';

$params = $_SERVER;

$bootstrap = Bootstrap::create(BP, $params);
 
$objectManager = $bootstrap->getObjectManager();
 
function throwError($message){
	$result = array();
	$result['status']= 'fail';
	$result['message']= $message;
	echo json_encode($result);
	exit();
}

function throwSuccess($message){
	$result = array();
	$result['status']= 'success';
	$result['message']= $message;
	echo json_encode($result);
	exit();
}