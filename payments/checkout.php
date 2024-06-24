<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../users/config.php');

session_start();


require '../vendor/autoload.php';

//Stripe Api KEY
$stripe_secret_key = "sk_test_51PTzKORscaWhBZMnxpovQVq0LyA0K0LnVcNSFVWp2jKztOpiusF7SKC68EoZMACqmeqEyfKaZ2OapVuscUzr3fsP00IAG2IMdr";

// User ID
$user_id = $_SESSION['user_id'];

// Stripe namespace
\Stripe\Stripe::setApiKey($stripe_secret_key);

if (isset($_POST['patient_id'])) {
    $patient_id = htmlspecialchars($_POST['patient_id']);
    $amount = htmlspecialchars($_POST['amount']) ;
    $billing_type = htmlspecialchars($_POST['billing_type']);
    $created_at = date('Y-m-d H:i:s');
    $dbprice = $amount / 100;

    // Validate price (ensure it's an integer)
    if (!is_numeric($amount) || (int)$amount <= 0) {
        die("Invalid price value");
    }

    // Create Checkout session
    try {
        $stmt = $mysqli->prepare("INSERT INTO billing (user_id, patient_id, billing_type, amount, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisis",$user_id, $patient_id, $billing_type, $dbprice, $created_at);
        if ($stmt->execute()) {
            $checkout_session = \Stripe\Checkout\Session::create([
                "mode" => "payment",
                "success_url" => "http://localhost/infirmary/payments/success.php",
                "cancel_url" => "http://localhost/infirmary/payments/fail.php", 
                "line_items" => [
                    [
                        "quantity" => 1,
                        "price_data" => [
                            "currency" => "kes",
                            "unit_amount" => $amount,
                            "product_data" => [
                                "name" => 'Medication'
                            ]
                        ]
                    ]
                ]
            ]);
            http_response_code(303);
            header("Location: " . $checkout_session->url);
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo "Price and description are required.";
}