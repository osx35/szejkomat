<?php
// Database connection
$servername = "database-1.cvms4moeaxgt.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "shakeomat";
$dbname = "aliExpressApp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $link = $_POST['link'];

    setcookie('name', $name, time() + (7 * 24 * 60 * 60), "/"); // 7 days
    setcookie('link', $link, time() + (7 * 24 * 60 * 60), "/"); // 7 days

    $name = $conn->real_escape_string($name);
    $link = $conn->real_escape_string($link);

    $given_shakes = 1;

    $check_sql = "SELECT * FROM users WHERE name = '$name' AND link = '$link'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        header("Location: give.php");
    } else {
        // No duplicate, insert the new record
        $given_shakes = 0; // Or another value if you want to set it dynamically

        // Prepare the SQL query to insert the data into the table
        $sql = "INSERT INTO users (name, link, given_shakes) VALUES ('$name', '$link', '$given_shakes')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
$conn->close();
header("Location: give.php");
?>
