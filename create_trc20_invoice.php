<?php

if(isset($_POST)){
    require_once("./db/conn.php");
    #1. select first unfreezed
    #2. Freeze
    #3. create QR
    #4. send QR and create invoice on db
    #5. Show QR with 15 mins timer and wallet
    #6. call a script that checks etherscan on that address endpoint is on tiendabtc
    #7 if usdt is catched return true and transaction hash with security checks 
    #8. update database transaction table with hashes
    #9. tell on frontend that payment is done
    $post_file = file_get_contents('php://input');
    $sql = "INSERT INTO `logs` (`id`, `text`) VALUES (NULL, 'ctrc20i $post_file')";
    $result = mysqli_query($conn, $sql);
    #1
    $api = $_POST['api'];
    $select_unfreezed = "SELECT * FROM `trc20` WHERE `freezed` = 0 AND `api_key`='$api' LIMIT 1";
    $result = mysqli_query($conn, $select_unfreezed);
    $wallet = '';
     $contract = 0;
    if ($result->num_rows > 0) {
        $contract = 1;
  // output data of each row
      while($row = $result->fetch_assoc()) {
        $wallet = $row;
      }
    } else {
        $contract = 0;
        $response_obj_err->status = "400";
        $response_obj_err->msg = "No TRC20 address available for apikey, try again in 15 minutes";
        $response_obj_err = json_encode($response_obj_err);
        echo $response_obj_err;
    }
    
    $wallet_id = $wallet['id'];
    $address = $wallet['address'];
    $amount = $_POST['amount'];
    $blockchain = $_POST['bc'];
    #2
    $update_freeze = "UPDATE `trc20` SET `freezed` = '1' WHERE `trc20`.`id` = $wallet_id ";
    $result = mysqli_query($conn, $update_freeze);
     
    #3
    $qr = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl='.$address.'&choe=UTF-8';
    
    #4
    $insert_invoice_erc20 = "INSERT INTO `trc20_invoices` (`id`, `wallet`, `amount`, `timestamp`, `expired`) VALUES (NULL, '$address', '$amount', CURRENT_TIMESTAMP, '0');";
    $result = mysqli_query($conn, $insert_invoice_erc20);
    $last_id = $conn->insert_id;
    
    $response_obj->amount = $amount;
    $response_obj->wallet = $address;
    $response_obj->qr = $qr;
    $response_obj->id = $last_id;
    
    $response_obj = json_encode($response_obj);
    if($contract==1){
    echo $response_obj;
    
    
    $url = 'https://usdt.tiendabtc.com/check_trc20_transaction.php';
    $data = ['blockchain' => $blockchain, 'amount' => $amount, 'addres'=>$address, 'id'=>$last_id];
    
    // use key 'http' even if you send the request to https://...
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
            'timeout' => 2
        ],
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === false) {
    }
    else{
    
     }
    }
    
    
    $conn->close();
    //set post
    }

?>