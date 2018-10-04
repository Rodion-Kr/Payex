<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="https://checkout.payex.com/js/payex-checkout.min.js"></script>
	<script type="text/javascript" src="script.js"></script>
</head>
<body>
	
<div style="width:100%;">
	<form action="" method="post">
		<input type="text">
		<input type="submit" class="btn" name="send" value="Купити" />
	</form>
</div>	
	

	<?php
	if(isset($_GET['id'])){?>
		<h1>Оплата прошла успешно!</h1>
		<p>ID Заказа: <?php echo $_GET['id']; ?></p>
	<?php }

	
	echo $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
	
	function get_reference($number){
		$symbol_for_reference = array('a','b','c','d','e','f',
			'g','h','i','j','k','l',
			'm','n','o','p','r','s',
			't','u','v','x','y','z',
			'A','B','C','D','E','F',
			'G','H','I','J','K','L',
			'M','N','O','P','R','S',
			'T','U','V','X','Y','Z',
			'1','2','3','4','5','6',
			'7','8','9','0');
		
		$unique_reference_string = "";
		for($i = 0; $i < $number; $i++){
			$index = rand(0, count($symbol_for_reference) - 1);
			$unique_reference_string .= $symbol_for_reference[$index];
		}
		$reference =  date("mdyHms");
		$reference = $reference . $unique_reference_string;
		return $reference;
	}

	function send_payment() {
	
		$payeeReference = get_reference(9);
		$order_id = rand(1, 150); // order id
		$order_cost = 10;


		$param = '{
					"payment": {
						"operation": "Purchase",
						"intent": "Authorization",
						"currency": "NOK",
						"prices": [
							{
								"type": "Visa",
								"amount": '.$order_cost.',
								"vatAmount": 0
							},
							{
								"type": "MasterCard",
								"amount": '.$order_cost.',
								"vatAmount": 0
							}
						],
						"description": "Kingdom of Escape Rooms",
						"payerReference": "AB540",
						"generatePaymentToken": false,
						"userAgent": "Mozilla/5.0...",
						"language": "nb-NO",
						"urls": {
							"completeUrl": "https://dev-rodion.larix.pp.ua/?id='.$order_id.'",
							"cancelUrl": "https://dev-rodion.larix.pp.ua",
							"callbackUrl": "https://example.com/payment-callback",
							"logoUrl": "https://kingdomrooms.no/wp-content/themes/secretorum/images/logo.png"
						},
						"payeeInfo": {
							"payeeId": "568e4a36-fdd7-40a9-ad2a-59093158ea47",
							"payeeReference":"'.$payeeReference.'",    
							"payeeName": "Merchant1",
							"productCategory": "A694"
						}
					},
					"creditCard": {
						"no3DSecure": false,
						"mailOrderTelephoneOrder": false,
						"rejectCardNot3DSecureEnrolled": false,
						"rejectCreditCards": false,
						"rejectDebitCards": false,
						"rejectConsumerCards": false,
						"rejectCorporateCards": false,
						"rejectAuthenticationStatusA": false,
						"rejectAuthenticationStatusU": false,
					}   
				}';

		$header = array();
		$header[] = 'Authorization : Bearer cd98d24f38c96edf7ac7f9af05c6e8962d322641358b2082bdc71c0e66962f3b';
		$header[] = 'Accept: application/json';
		$header[] = 'Content-type: application/json';

		if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, 'https://api.externalintegration.payex.com/psp/creditcard/payments');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
			$out = curl_exec($curl);
			curl_close($curl);
			$arr = explode(",", $out);
			$arrUrl = explode("://", $arr[22]);
			$ur = $arrUrl[1];
			$url = substr($ur, 0, -1);
			$url = substr($ur, 0, -1);
			$url = 'https://'.$url;	 
			header('Location:'. $url);
		}
	}


//send_payment();
?>
</body>
</html>