<?php
require 'lib/nusoap.php';
$client = new nusoap_client("http://localhost/soap/service.php?wsdl");
$book_name="c";

$price=$client->call('price',array("name"=>"c"));
echo $price;
?>