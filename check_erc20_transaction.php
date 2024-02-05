<?php
if(isset($_POST)){
    $post_file = file_get_contents('php://input');
    require_once("./db/conn.php");
    $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$post_file')";
    $result = mysqli_query($conn, $sql);

    $data = array();
    parse_str($post_file, $data);
    $_POST = array_merge($data, $_POST);
    $blockchain = $_POST['blockchain'];
    if($blockchain == 'erc20'){

                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$blockchain')";
                        $result = mysqli_query($conn, $sql);
        $contract = '0xdac17f958d2ee523a2206206994597c13d831ec7';
        $address = $_POST['addres'];
        $amount = $_POST['amount'];
        $id = $_POST['id'];
        $endpoint = "https://api.etherscan.io/api?module=account&action=tokentx&contractaddress=$contract&address=$address&apikey=6FVVDZ56A6J5EJEHH7FJ98EDQ4VZFJYZWZ";
        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$endpoint')";
                        $result = mysqli_query($conn, $sql);
        $transactions_object = file_get_contents($endpoint);
 
        $object_json = json_decode($transactions_object);

                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$transactions_object')";
                        $result = mysqli_query($conn, $sql);

        if($object_json->status == "1" || $object_json->status == "0"){

                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'status=1')";
                        $result = mysqli_query($conn, $sql);
                        
            $save_var_length = $object_json->result;
            $save_var_length = count($save_var_length);
            $count = true;
            $decrease = -1;
            $var_int = 0;
            //Crear funcion recursiva que resuelva
            function recursiveness($save_var_length, $endpoint, $var_int,$amount, $address, $id, $conn ){
                
                if($var_int < 20){
                 

                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '$address iteration...')";
                        $result = mysqli_query($conn, $sql);
                        
                    $transactions_object_2 = file_get_contents($endpoint);
                    $object_json_2 = json_decode($transactions_object_2);
                    $save_var_length_2 = count($object_json_2->result);
                     if(($save_var_length_2 > $save_var_length || $save_var_length== 1) && $object_json_2->status == "1"  ){
                        //Lets see if amount its >=
                        $transaction_obj = $object_json_2->result[$save_var_length_2 + (-1)];
                    
                        $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, '1 or 1 and 1')";
                        $result = mysqli_query($conn, $sql);
                        
                        if($save_var_length == 1){
                        $transaction_obj = $object_json_2->result[0];
                        }
                            
                                $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'tobjecr: ')";
                                $result = mysqli_query($conn, $sql);
                              
                        if($transaction_obj->to == strtolower($address)){
                      
                            $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'same address')";
                            $result = mysqli_query($conn, $sql);
                           
                            if($transaction_obj->value == strval($amount*1000000)){
                            
                                sleep(5);
                                $update_freeze = "UPDATE `erc20` SET `freezed` = '0' WHERE `erc20`.`address` = '$address'";
                                $result = mysqli_query($conn, $update_freeze);
                                //insert transaction
                                sleep(1);
                                $hash = $transaction_obj->hash;
                                $to = $transaction_obj->to;
                                $sql = "INSERT INTO `erc20_transaction_list` (`id`, `hash`, `amount`, `wallet`, `id_invoice`) VALUES (NULL, '$hash', '$amount', '$to', '$id');";
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
                    $update_freeze = "UPDATE `erc20` SET `freezed` = '0' WHERE `erc20`.`address` = '$address'";
                    $result = mysqli_query($conn, $update_freeze);
                    sleep(5);
                    $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'finished')";
                    $result = mysqli_query($conn, $sql);
                    $response_obj->status = "false";
                    $response_obj = json_encode($response_obj);
                    echo $response_obj;
                }
                
            }
            recursiveness($save_var_length, $endpoint, $var_int,$amount, $address, $id, $conn);
           $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'Function called')";
                        $result = mysqli_query($conn, $sql);
            
            
            
        }
        else{
            $update_freeze = "UPDATE `erc20` SET `freezed` = '0' WHERE `erc20`.`address` = '$address'";
            $result = mysqli_query($conn, $update_freeze);
            echo "Invalid address, contact admin";
            sleep(5);
            $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'Try again')";
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