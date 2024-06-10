<?php 
$errors  = array();
$errmsg  = '';

$config = array(
    "env"              => "live",
    "BusinessShortCode"=> "411526",
    "BusinessTill" => "411527",
    "key"              => "i32ieRVA8x23NCkgRwuwEAQiRAYhbByR", //Enter your consumer key here
    "secret"           => "JqVjjYLufZLT9zCu", //Enter your consumer secret here
    "username"         => "dennis",
    "TransactionType"  => "CustomerBuyGoodsOnline",
    "passkey"          => "34fc37eca0eef31eaeb8c0dfd22b5bd2602b092e1db895ab4ad9e3ca5686100c", //Enter your passkey here
    "CallBackURL"      => "https://tipspesa.uk/express-stk-callback", //When using localhost, Use Ngrok to forward the response to your Localhost
    "AccountReference" => "CompanyXLTD",
    "TransactionDesc"  => "Payment of X" ,
);

$access_token = ($config['env']  == "live") ? "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" : "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"; 
$credentials = base64_encode($config['key'] . ':' . $config['secret']); 

$ch = curl_init($access_token);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response); 
$token = isset($result->{'access_token'}) ? $result->{'access_token'} : "N/A";

$timestamp = date("YmdHis");
$password  = base64_encode($config['BusinessShortCode'] . "" . $config['passkey'] ."". $timestamp);

$curl_post_data = array( 
    "BusinessShortCode" => $config['BusinessShortCode'],
    "Password" => $password,
    "Timestamp" => $timestamp,
    "TransactionType" => $config['TransactionType'],
    "Amount" => $amount,
    "PartyA" => $phone,
    "PartyB" => $config['BusinessTill'],
    "PhoneNumber" => $phone,
    "CallBackURL" => $config['CallBackURL'],
    "AccountReference" => $config['AccountReference'],
    "TransactionDesc" => $config['TransactionDesc'],
); 

$data_string = json_encode($curl_post_data);

$endpoint = ($config['env'] == "live") ? "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest" : "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest"; 

$ch = curl_init($endpoint );
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer '.$token,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response     = curl_exec($ch);
curl_close($ch);

$result = json_decode(json_encode(json_decode($response)), true);

if(!preg_match('/^[0-9]{10}+$/', $phone) && array_key_exists('errorMessage', $result)){
    $errors['phone'] = $result["errorMessage"];
}

if($result['ResponseCode'] === "0"){         //STK Push request successful

    $MerchantRequestID = $result['MerchantRequestID'];
    $CheckoutRequestID = $result['CheckoutRequestID'];   

    $sql = 'INSERT INTO `mpesa`
            (`phone`,`MerchantRequestID`,`CheckoutRequestID`,`Amount`) 
            VALUES(?,?,?,?)';
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sssi", $phone, $MerchantRequestID, $CheckoutRequestID, $amount);
        $stmt->execute();
        $stmt->close();
    }
    
}else{
    $errors['mpesastk'] = $result['errorMessage'];
    foreach($errors as $error) {
        $errmsg .= $error . '<br />';
    }
}

?>