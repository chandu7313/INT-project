<?php
// Allow cross-origin for local testing
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

// Read incoming JSON
$data = json_decode(file_get_contents("php://input"));

if (isset($data->name) && isset($data->email) && isset($data->date) && isset($data->classType)) {
    $name = htmlspecialchars($data->name);
    $email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    $date = htmlspecialchars($data->date);
    $classType = htmlspecialchars($data->classType);

    // Email settings
    $to = $email;
    $subject = "Class Booking Confirmation";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Fitness Center <yourgmail@gmail.com>\r\n";

    // HTML Message
    $message = "
        <html>
        <body>
            <h2>Hi $name!</h2>
            <p>Your booking for <strong>$classType</strong> on <strong>$date</strong> is confirmed.</p>
            <p>Get ready to sweat 💪</p>
        </body>
        </html>
    ";

    // Attempt to send
    if (mail($to, $subject, $message, $headers)) {
        echo "✅ Booking confirmed and email sent to $email!";
    } else {
        http_response_code(500);
        echo "❌ Booking failed. Could not send email.";
    }
} else {
    http_response_code(400);
    echo "❌ Invalid input. All fields are required.";
}
