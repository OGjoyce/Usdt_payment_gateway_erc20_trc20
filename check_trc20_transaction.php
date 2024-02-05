<?php
ignore_user_abort( true );

if(isset($_POST)){
    $post_file = file_get_contents('php://input');
    require_once("./db/conn.php");
    $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$post_file')";
    $result = mysqli_query($conn, $sql);

    $data = array();
    parse_str($post_file, $data);
    $_POST = array_merge($data, $_POST);
    $blockchain = $_POST['blockchain'];
    if($blockchain == 'trc20'){

                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$blockchain')";
                        $result = mysqli_query($conn, $sql);
        $contract = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t';
        $address = $_POST['addres'];
        $amount = $_POST['amount'];
        $id = $_POST['id'];
        $endpoint = "https://apilist.tronscanapi.com/api/token_trc20/transfers?limit=&start=&contract_address=$contract&start_timestamp=&end_timestamp=&confirm=&toAddress=$address";
        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$address')";
        $result = mysqli_query($conn, $sql);
        $transactions_object = file_get_contents($endpoint);
 
        $object_json = json_decode($transactions_object);

                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$transactions_object')";
                        $result = mysqli_query($conn, $sql);

        if($object_json->total >= 0){

                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'total>1')";
                        $result = mysqli_query($conn, $sql);
                        
            $save_var_length = $object_json->total;
            $count = true;
            $decrease = -1;
            $var_int = 0;
            //Crear funcion recursiva que resuelva
            function recursiveness($save_var_length, $endpoint, $var_int,$amount, $address, $id, $conn ){
                
                if($var_int < 20){
                 

                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'iteration... $var_int')";
                        $result = mysqli_query($conn, $sql);
                    $transactions_object_2 = file_get_contents($endpoint);
                    $object_json_2 = json_decode($transactions_object_2);
                    $save_var_length_2 = $object_json_2->total;
                    $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'first - $save_var_length_2 is bigger than $save_var_length')";
                        $result = mysqli_query($conn, $sql);
                     if(($save_var_length_2 > $save_var_length || $save_var_length== 0)){
                        //Lets see if amount its >=
                        $large = count($object_json_2->token_transfers);
                        $transaction_obj = $object_json_2->token_transfers[0];
                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$save_var_length_2 is bigger than $save_var_length')";
                        $result = mysqli_query($conn, $sql);
                        $converted_obj = json_encode($transaction_obj);
                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'info: $converted_obj')";
                        $result = mysqli_query($conn, $sql);
                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'checkpoint')";
                        $result = mysqli_query($conn, $sql);
                        if($save_var_length == 0){
                        $transaction_obj = $object_json_2->token_transfers[0];
                        }

                        $object_in_array = $transaction_obj;
                        $toaddress = $object_in_array->to_address;
                       
                                $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'to: $toaddress' )";
                                $result = mysqli_query($conn, $sql);
                                 $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'frontend:$address ' )";
                                $result = mysqli_query($conn, $sql);
                              
                        if(strtolower($toaddress) == strtolower($address)){
                      
                            $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'same address')";
                            $result = mysqli_query($conn, $sql);
                            $cantidad = $amount*1000000;
                            $cantidad = strval($cantidad);
                            $quant =$transaction_obj->quant;
                            $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$cantidad')";
                            $result = mysqli_query($conn, $sql);
                            $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, $quant)";
                            $result = mysqli_query($conn, $sql);
                            
                            if($transaction_obj->quant == strval($amount*1000000)){
                                sleep(5);
                                $update_freeze = "UPDATE `trc20` SET `freezed` = '0' WHERE `trc20`.`address` = '$address'";
                                $result = mysqli_query($conn, $update_freeze);
                                
                                sleep(5);
                                //insert transaction
                                $hash = $transaction_obj->transaction_id;
                                $to = $transaction_obj->to_address;
                                $sql = "INSERT INTO `trc20_transaction_list` (`id`, `hash`, `amount`, `wallet`, `id_invoice`) VALUES (NULL, '$hash', '$amount', '$to', '$id');";
                                $result = mysqli_query($conn, $sql);
                                $response_obj->status = "true";
                                $response_obj = json_encode($response_obj);
                                
                                echo $response_obj;
                                
                                $conn->close();
                                return true;
                            }
                            else{
                                sleep(10);
                               
                                $var_int = $var_int + 1;
                                recursiveness($save_var_length, $endpoint, $var_int,$amount, $address,$id, $conn);
                                
                            }
                        }
                        else{
                            sleep(10);
                            $var_int = $var_int + 1;
                            recursiveness($save_var_length, $endpoint, $var_int,$amount, $address,$id, $conn);
                              
                        }
                        
                        
                    }
                    else{
                        sleep(10);
                        $var_int++;
                        recursiveness($save_var_length, $endpoint, $var_int,$amount, $address,$id, $conn);
                        
                    }
                    
                }
                else{
                    sleep(1);
                    $update_freeze = "UPDATE `trc20` SET `freezed` = '0' WHERE `trc20`.`address` = '$address'";
                    $result = mysqli_query($conn, $update_freeze);
                    sleep(5);
                    $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'time expired. $address')";
                    $result = mysqli_query($conn, $sql);
                    $response_obj->status = "false";
                    $response_obj = json_encode($response_obj);
                    
                     
                     echo $response_obj;
                     return true;
                }
                
            }
            recursiveness($save_var_length, $endpoint, $var_int,$amount, $address, $id, $conn);
           $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'Function called')";
            $result = mysqli_query($conn, $sql);
            
            
            
        }
        else{
            echo "403";
            $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '403')";
                        $result = mysqli_query($conn, $sql);
        }

        
        //endpoint https://api.etherscan.io/api?module=account&action=tokentx&contractaddress=0x9f8f72aa9304c8b593d555f12ef6589cc3a579a2&address=0x4e83362442b8d1bec281594cea3050c8eb01311c&page=1&offset=100&startblock=0&endblock=27025780&sort=asc&apikey=YourApiKeyToken
    }
    else{
        echo "blockchain notfound";
    
                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'blockchain notfound')";
                        $result = mysqli_query($conn, $sql);
                        $conn->close();
    }
}
?>