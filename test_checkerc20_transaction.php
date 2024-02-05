<?php

$endpoint = "https://apilist.tronscanapi.com/api/token_trc20/transfers?limit=&start=&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&start_timestamp=&end_timestamp=&confirm=&toAddress=TKazhpZ6pwxzua6X7GYdoSGy1oHvT9zMZJ";

$transactions_object = file_get_contents($endpoint);
echo $endpoint;
echo "<br>";

echo "<br>";
$object_json = json_decode($transactions_object);
$token_transfer = $object_json->token_transfers;
echo "<br>";
$total = $object_json->total;
echo $total;
echo "<br>";
$inside_json = json_decode($token_transfer);
$inside_json_last = $inside_json[total - 1];
var_dump($token_transfer);
echo "<br> PERRRO";
echo "<br>";
echo count($token_transfer);

?>