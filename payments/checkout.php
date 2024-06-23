<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../users/config.php');

session_start();


require '../vendor/autoload.php';

//Stripe Api KEY
$stripe_secret_key = "sk_test_51PTzKORscaWhBZMnxpovQVq0LyA0K0LnVcNSFVWp2jKztOpiusF7SKC68EoZMACqmeqEyfKaZ2OapVuscUzr3fsP00IAG2IMdr";

// Stripe namespace
\Stripe\Stripe::setApiKey($stripe_secret_key);

if (isset($_POST['price'])) {
    $price = htmlspecialchars($_POST['price']) ;
    $description = htmlspecialchars($_POST['description']);
    $recipient_email = htmlspecialchars($_POST['remail']);
    $sender_email = htmlspecialchars($_POST['semail']);
    $created_at = date('Y-m-d H:i:s');
    $dbprice = $price / 100;

    // Validate price (ensure it's an integer)
    if (!is_numeric($price) || (int)$price <= 0) {
        die("Invalid price value");
    }

    // Create Checkout session
    try {
        $stmt = $conn->prepare("INSERT INTO `billing` (`user_id`, `patient_id`, `billing_date`, `billing_type`,'amount') VALUES (?, ?, ?, ?.)");
        $stmt->bind_param("siss", $sender_email, $dbprice, $recipient_email, $created_at);
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
                            "unit_amount" => 5,
                            "product_data" => [
                                "name" => "paracetamol"
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