<?php
header('Content-Type: text/html');
function pr_ar($a){echo '<pre>'; print_r($a); die;}
//require_once 'app/Mage.php';


$api_url_v2 = "http://www.nextone.com.ua/api/v2_soap/?wsdl";
$username = 'nextonec';
$password = 'nextonec_andrew';
$cli = new SoapClient($api_url_v2);
//retreive session id from login
$session_id = $cli->login($username, $password);
//call customer.list method
$result = $cli->salesOrderList($session_id);
pr_ar($result);die;



$api_url_v1 = "http://www.nextone.com.ua/api/soap/?wsdl=1";

$username = 'nextonec';
$password = 'nextonec_andrew';

$data = array( 
    array(
        'customer_email' => 'ay52bog@gmail.com',
        //'customer_phone' => $phone,
    )
);

$soapParameters = array('login' => "nextonec", 'password' => "nextonec_andrew") ;
$cli = new SoapClient($api_url_v1, $soapParameters);

$soapClient = new SoapClient($api_url_v1, $soapParameters);

$soapResult = $soapClient->__soapCall('sales_order.list', $data) ;
pr_ar($soapResult);



if(is_array($soapResult) && isset($soapResult['someFunctionResult'])) {
    
}

print_r($cli);
//retreive session id from login

$session_id = $cli->login(array('login' => "nextonec", 'password' => "nextonec_andrew"));
if(!$session_id){
    echo 'Autorization error!';
    exit();
}


//$data = array(array('customer_email' => 'ay52bog@gmail.com'));

//call customer.list method
$result = $cli->call($session_id,  'sales_order.list', $data );

$cli->endSession($session_id);