<?php
$conn = mysqli_connect('localhost', 'root', '', 'blogs');

if (!$conn) {
    echo "❌ Failed to connect to the database.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "❌ Please enter a valid email address.";
        exit;
    }

    $email_safe = mysqli_real_escape_string($conn, $email);
    $check_sql = "SELECT is_active FROM subscribers WHERE email = '$email_safe'";
    $result = mysqli_query($conn, $check_sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $subscriber = mysqli_fetch_assoc($result);
        if ($subscriber['is_active'] == 1) {
            echo "✅ You're already subscribed!";
        } else {
            // Reactivate subscription
            $update_sql = "UPDATE subscribers SET is_active = 1, unsubscribed_at = NULL WHERE email = '$email_safe'";
            if (mysqli_query($conn, $update_sql)) {
                echo "👋 Welcome back! Your subscription has been reactivated.";
            } else {
                echo "❌ Something went wrong. Please try again.";
            }
        }
    } else {
        // New subscriber
        $token = bin2hex(random_bytes(16));
        $insert_sql = "INSERT INTO subscribers (email, unsubscribe_token, is_active, subscribed_at)
                       VALUES ('$email_safe', '$token', 1, NOW())";

        if (mysqli_query($conn, $insert_sql)) {
            echo "✅ Thanks for subscribing!";
        } else {
            echo "❌ Error saving your subscription.";
        }
    }
} else {
    echo "❌ Invalid request.";
}

mysqli_close($conn);
?>
