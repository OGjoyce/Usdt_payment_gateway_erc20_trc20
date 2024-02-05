<?php
if(isset($_GET)){
    require_once("./db/conn.php");
    $id = $_GET['id'];
    $sql = "SELECT * FROM `erc20_transaction_list` WHERE `id_invoice` = $id";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
    // output data of each row
        $obj->status_obj = "false";
      while($row = $result->fetch_assoc()) {
          $obj->status_obj = "true";
          $obj->address = $row['wallet'];
          $obj->hash = $row['hash'];
          $obj->amount = $row['amount'];
          
      }
      echo json_encode($obj);
    } else {
      $obj->status_obj="false";
      $obj->status="400";
      echo json_encode($obj);
    }
    $count = false;
}

?>