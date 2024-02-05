# Usdt_payment_gateway_erc20_trc20
This code will helo you to create your own payment gateway or for a company, please enjoy >:)
To start is very simple:
1. Install PHP
2. Instal Mysql (I used phpmyadmin) for this example
3. Create a wallet and save derivation path (You will add the wallets you create in erc20 table) at least add 20 https://tiendabtc.com/wgeneratortiendabtc.html
4. Create a database
5. Create a table with the schema call it erc20
  1 id Primary	int(11)			No	None		AUTO_INCREMENT	Change Change	Drop Drop	
	2	address	varchar(200)	utf8_unicode_ci		No	None			Change Change	Drop Drop	
	3	freezed	tinyint(1)			No	None			Change Change	Drop Drop	
	4	api_key	varchar(255)	utf8_unicode_ci		No	None			Change Change	Drop Drop	
	5	sum_transaction	float			No	None			Change Change	Drop Drop
6. Create a table with the schema call it erc20_invoices
  1	id Primary	int(11)			No	None		AUTO_INCREMENT	Change Change	Drop Drop	
	2	wallet	varchar(200)	utf8_unicode_ci		No	None			Change Change	Drop Drop	
	3	amount	float			No	None			Change Change	Drop Drop	
	4	timestamp	timestamp		on update CURRENT_TIMESTAMP	No	CURRENT_TIMESTAMP		ON UPDATE CURRENT_TIMESTAMP	Change Change	Drop Drop	
	5	expired	tinyint(1)			No	None			Change Change	Drop Drop
7. Create a table with the schema, call it erc20_transaction_list
  1	id Primary	int(11)			No	None		AUTO_INCREMENT	Change Change	Drop Drop	
	2	hash	varchar(200)	utf8_unicode_ci		No	None			Change Change	Drop Drop	
	3	amount	float			No	None			Change Change	Drop Drop	
	4	wallet	varchar(200)	utf8_unicode_ci		No	None			Change Change	Drop Drop	
	5	id_invoice	int(11)			No	None			Change Change	Drop Drop

8. Go to file ./db/conn.php and change the database settings
9. After database is set properly you can go to index.php and make your first test, check that code it will help you go through all the endpoints

Dedication:
For all the developers learning this hard stuff, the market isnt paying enough, I am getting very poor, I have debts, please donate here: 
TJkBZmizQf1PSCLHyfV8SxrTe8MYRRunYM - TRC20

if you need help just talk to me at ownstrpk4@gmail.com

