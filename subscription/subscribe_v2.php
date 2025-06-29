<?php
// Start session to handle CSRF token
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'blogs');
if (!$conn) {
    echo "❌ Failed to connect to the database.";
    exit;
}

// CSRF token validation (added for security)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "❌ Invalid CSRF token.";
        exit;
    }
}

// Validate the email
if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
    
    // Check if the email is in a valid format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "❌ Please enter a valid email address.";
        exit;
    }

    // Safely escape email to prevent SQL injection
    $email_safe = mysqli_real_escape_string($conn, $email);

    // Check if the email is already in the database
    $check_sql = "SELECT is_active FROM subscribers WHERE email = '$email_safe'";
    $result = mysqli_query($conn, $check_sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        // Email already exists
        $subscriber = mysqli_fetch_assoc($result);
        if ($subscriber['is_active'] == 1) {
            // If already active, inform the user
            echo "✅ You're already subscribed!";
        } else {
            // If the user was unsubscribed, reactivate the subscription
            $update_sql = "UPDATE subscribers SET is_active = 1, unsubscribed_at = NULL WHERE email = '$email_safe'";
            if (mysqli_query($conn, $update_sql)) {
                echo "👋 Welcome back! Your subscription has been reactivated.";
            } else {
                // Error while updating
                echo "❌ Something went wrong. Please try again.";
            }
        }
    } else {
        // New subscriber
        $token = bin2hex(random_bytes(16));  // Generate a secure random unsubscribe token
        $insert_sql = "INSERT INTO subscribers (email, unsubscribe_token, is_active, subscribed_at)
                       VALUES ('$email_safe', '$token', 1, NOW())";

        if (mysqli_query($conn, $insert_sql)) {
            // Send confirmation email (optional)
            $unsubscribe_url = "https://yourwebsite.com/unsubscribe.php?email=$email_safe&token=$token";
            // Email headers
            $subject = "Thanks for Subscribing!";
            $message = "Hello, thanks for subscribing to our newsletter!\n\n";
            $message .= "To unsubscribe, click the following link:\n$unsubscribe_url";
            mail($email_safe, $subject, $message);

            echo "✅ Thanks for subscribing! A confirmation email has been sent.";
        } else {
            // Error while inserting into the database
            echo "❌ Error saving your subscription. Please try again.";
        }
    }
} else {
    echo "❌ Invalid request.";
}

// Close the database connection
mysqli_close($conn);
?>
