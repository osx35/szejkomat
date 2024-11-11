<?php
// Database connection
$servername = "database-1.cvms4moeaxgt.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "shakeomat";
$dbname = "aliExpressApp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in by checking the cookie
if (isset($_COOKIE['name']) && isset($_COOKIE['link'])) {
    $user_name = $_COOKIE['name'];
    $user_link = $_COOKIE['link'];

    $user_name = $conn->real_escape_string($user_name);

    $user_id_sql = "SELECT id FROM users WHERE name = '$user_name' and link = '$user_link' LIMIT 1";
    $user_result = $conn->query($user_id_sql);

    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $giver_id = $user_row['id'];


            // After updating the score, fetch a random link from the database
            $link_sql = "SELECT link, id FROM users WHERE id != $giver_id ORDER BY RAND() LIMIT 1";  // Fetch random link
            $link_result = $conn->query($link_sql);

            if ($link_result->num_rows > 0) {
                // Fetch the random link
                $link_row = $link_result->fetch_assoc();
                $receiver_link = $link_row['link'];
                $receiver_id = $link_row['id'];

                // Check if the combination of giver_id and receiver_id already exists in the given_shakes_log
                $check_sql = "SELECT 1 FROM given_shakes_log WHERE giver_id = $giver_id AND receiver_id = $receiver_id LIMIT 1";
                $check_result = $conn->query($check_sql);

                if ($check_result->num_rows == 0) {
                    // Insert into given_shakes_log (tracking who gave shake to whom)
                    $insert_given_sql = "INSERT INTO given_shakes_log (giver_id, receiver_id) VALUES ($giver_id, $receiver_id)";
                    $conn->query($insert_given_sql);

                    // Redirect to the fetched link
                    header("Location: $receiver_link");
                    exit();  // Ensure no further code is executed
                } else {
                    header("Location: error.php");
                }
            } else {
                echo "Błąd: " . $conn->error;
            }
    } else {
        echo "Nie jesteś zalogowany.";
    }
}
// Close the database connection
$conn->close();
?>
