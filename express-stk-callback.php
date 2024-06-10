<?php

include './includes/config.php';

echo '<a href="index.php">Home<br /></a>';

$content = file_get_contents('php://input'); //Receives the JSON Result from safaricom
$data = json_decode($content, true); //Convert the json to an array

$content .= PHP_EOL;
file_put_contents('transaction_log', $content, FILE_APPEND); //Logs the results to our log file

// Extract relevant data
$merchantRequestID = $data['Body']['stkCallback']['MerchantRequestID'];
$checkoutRequestID = $data['Body']['stkCallback']['CheckoutRequestID'];
$resultCode = $data['Body']['stkCallback']['ResultCode'];
$resultDesc = $data['Body']['stkCallback']['ResultDesc'];
$items = $data['Body']['stkCallback']['CallbackMetadata']['Item'];
$amount = null;
$mpesaReceiptNumber = null;
$balance = null;
$transactionDate = null;
$phoneNumber = null;

if($resultCode==0){
    foreach ($items as $item) {
        switch ($item['Name']) {
            case 'Amount':
                $amount = $item['Value'];
                break;
            case 'MpesaReceiptNumber':
                $mpesaReceiptNumber = $item['Value'];
                break;
            case 'Balance':
                $balance = $item['Value'];
                break;
            case 'TransactionDate':
                $transactionDate = $item['Value'];
                break;
            case 'PhoneNumber':
                $phoneNumber = $item['Value'];
                break;
            default:
                // Handle other item names if needed
                break;
        }
    }
    
    $sql2 = 'UPDATE `premium`
            SET `expires_at`=DATE_ADD(NOW(), INTERVAL `validity` DAY)
                WHERE `phone`=?';
    if ($stmt2 = $mysqli->prepare($sql2)) {
        $stmt2->bind_param("s", $phoneNumber);
        $stmt2->execute();
        $stmt2->close();
    }
}

$sql3 = 'UPDATE `mpesa`
            SET `ResultDesc`=?,`MpesaReceiptNumber`=?, `TransactionDate`=?
                WHERE `MerchantRequestID`=?';
    if ($stmt3 = $mysqli->prepare($sql3)) {
        $stmt3->bind_param("ssss", $resultDesc, $mpesaReceiptNumber, $transactionDate, $merchantRequestID);
        $stmt3->execute();
        $stmt3->close();
    }
?>