<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./assets/abstractos.js" ></script>
	<title>Usdt ERC20 Payment Processor</title>
</head>
<body>
	<div class="container">
		
		
	<form class="row row-cols-lg-auto g-3 align-items-center" >
  <div class="col-6">
    <label class="visually-hidden" for="usdt_value">Cantidad en USDT</label>
    <div class="input-group">
      <div class="input-group-text"><img src="./assets/usdtlogo.png" class="rounded mx-auto d-block" alt="..." style="width: 20px; height: 20;"></div>
      <input type="number" class="form-control" id="usdt_value" placeholder="100.00" />
    </div>
  </div>

  <div class="col-3">
    <label class="visually-hidden" for="inlineFormSelectPref">Blockchain</label>
    <select class="select" id="blockchain_selector">
      <option value="erc20">ERC20</option>
      <option value="trc20">TRC20</option>
      <option value="5">Polygon</option>
      <option value="6">Cardano</option>
      <option value="7">Solana</option>
      <option value="8">BEP20</option>
    </select>
  </div>

  <div class="col-2">
    <button type="button" class="btn btn-primary" onclick="createERC20(this);" >Pay</button>
  </div>
</form>


	</div>
	<div class="container fluid" id="qr_loader">
	    
	    <img src='' id='qr_image'/>
	    <p><img hidden src='./assets/200w.gif' id='loader' style='height: 50px; width:50px;'/><img hidden src='./assets/greencheck.png' id='check' style='height: 50px; width:50px;'/>Send exactly <label id="label_amount">0.00</label> to this address <label id="label_address"></label> using blockchain selected</p>
        
        <p>Transaction hash: <label id="hash"></label></p>
    </div>





</body>	
<script type="text/javascript">
    function createERC20(form){
        var usdt_value = document.getElementById('usdt_value').value;
        var blockchain = document.getElementById('blockchain_selector').value;
        
        //create_erc20_invoice.php
        const xhr = new XMLHttpRequest();
        var link;
        if(blockchain == "erc20"){
            link = "https://usdt.tiendabtc.com/create_erc20_invoice.php"
        }
        if(blockchain == "trc20"){
            link = "https://usdt.tiendabtc.com/create_trc20_invoice.php"
        }
        xhr.open("POST", link, true);
        
        // Send the proper header information along with the request
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        xhr.onreadystatechange = () => {
          // Call a function when the state changes.
          if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            document.getElementById("loader").hidden = false;
            var response_obj = JSON.parse(xhr.response);
            document.getElementById('label_amount').innerHTML = response_obj.amount;
            document.getElementById('label_address').innerHTML = response_obj.wallet;
            var id = response_obj.id;
            var downloadingImage = new Image();
            downloadingImage.onload = function(){
                document.getElementById('qr_image').src = response_obj.qr; 
                
            };
            downloadingImage.src = response_obj.qr;
            //callscript (10secs)
            
              //function that post and calls the recursiveness function, this function checks if theres any transaction done and 
            //returns true; (function should be worked on paralell)
            
            let myPromise = new Promise(function(myResolve, myReject) {
            // "Producing Code" (May take some time)
            
              myResolve(); // when successful
              myReject();  // when error
            });
            
            // "Consuming Code" (Must wait for a fulfilled Promise)
            myPromise.then(
              function(value) { /*checkTxBlockchain();*/ },
              function(error) { console.log("error"); }
            );
            
            function checkTxBlockchain(){
                        //create_erc20_invoice.php
                const xhr_post_blockchain = new XMLHttpRequest();
                if(blockchain == "erc20"){
                    xhr_post_blockchain.open("POST", "https://usdt.tiendabtc.com/check_erc20_transaction.php", true);
                
                }
                if (blockchain == "trc20"){
                    xhr_post_blockchain.open("POST", "https://usdt.tiendabtc.com/check_trc20_transaction.php", true);
                 
                }
                
                // Send the proper header information along with the request
                xhr_post_blockchain.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                
                xhr_post_blockchain.onreadystatechange = () => {
                  // Call a function when the state changes.
                  if (xhr_post_blockchain.readyState === XMLHttpRequest.DONE && xhr_post_blockchain.status === 200) {
                    var response_obj_tx = JSON.parse(xhr_post_blockchain.response);
                    if(response_obj_tx.status == "false"){
                        console.log("transaction not sent");
                    }
                    else{
                        console.log("transaction sent");
                    }
                    
                    
                  }
                };
                //blockchain, amount, addres, id
                xhr_post_blockchain.send("amount="+usdt_value+"&blockchain="+blockchain+"&api=test&addres="+response_obj.wallet+"&id="+response_obj.id);
            }
            
            checkTx();
            var counter = 0;
            
            
          
            
            
            
            //Function that is able to check if transaction is set.
            function checkTx(){
                var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                   // Typical action to be performed when the document is ready:
                   let id_response = JSON.parse(xhttp.responseText);
                   if(id_response.status_obj == "true"){
                       console.log("transaction sent");
                       document.getElementById("loader").hidden = true;
                       document.getElementById("check").hidden = false;
                       document.getElementById("hash").innerHTML =id_response.hash;
                       
                   }
                   else{
         
                       function sleep (time) {
                          return new Promise((resolve) => setTimeout(resolve, time));
                        }
                        sleep(50000).then(() => {
                            // Do something after the sleep!
                            counter++;
                            if(counter>90){
                                alert('declined');
                            }
                            else{
                                checkTx();
                            }
                            
                        });

                   }
                   
                }
            };
            if(blockchain == "erc20"){
                xhttp.open("GET", "./check_erc20_invoice.php?id="+id, true);
                }
            if (blockchain == "trc20"){
                xhttp.open("GET", "./check_trc20_invoice.php?id="+id, true);
                }
        
            
            xhttp.send();
            }
            
            
            
          }
        };
        xhr.send("amount="+usdt_value+"&bc="+blockchain+"&api=test");
        
        
        
    }
</script>
</html>