<?php

/* The <dc> part of the URL corresponds to the data center for your account. 
For example, if the last part of your MailChimp API key is us6, all API 
endpoints for your account are available at https://us6.api.mailchimp.com/3.0/. */
$dc = 'us12';

/* Your list */
$list_id = 'cca4c41731';

/* Your API Key */
$apikey = 'a1f7593aff17cb5a6a9bfd385a43a177-us12';

$email = $_POST['email'];

// handle error
if(is_null($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
	http_response_code(400);
	echo 'invalid';
	exit;
}

function subscribe($apikey, $email, $dc, $list_id){

	$data = array(
		'apikey'        => $apikey,
		'email_address' => $email,
		'status'        => 'subscribed',
	);
	$json_data = json_encode($data);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://$dc.api.mailchimp.com/3.0/lists/$list_id/members/");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
	curl_setopt($ch, CURLOPT_USERPWD, "user:$apikey"); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                                                                                                  

	$result = json_decode(curl_exec($ch));

	if($result->title == "Member Exists" || isset($result->id)){
	?>

		<h1>Thank you</h1>
		<a href="a.pdf" class="btn">Download File</a>
		<style>
		.btn{
			display: inline-block;
			padding: 10px;
			border: 1px solid #ccc;
			background-color: #efefef;
			text-decoration: none;
		}
		</style>

	<?php
	}
}

subscribe($apikey, $email, $dc, $list_id);
