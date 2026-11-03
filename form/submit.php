<?php

$host = "localhost";
$dbname = "web_project";
$username = "formuser";
$password = "root";

try {

    // Connect to MySQL
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validate form
    if ($name === '' || $email === '' || $message === '') {
        throw new Exception("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    // Insert data into database
    $sql = "INSERT INTO contacts (name, email, message)
            VALUES (:name, :email, :message)";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message
    ]);

    echo "<h2>Form submitted successfully!</h2>";
    echo "<p>Your data has been saved in MySQL.</p>";
    echo '<a href="index.html">Go Back</a>';

} catch (Exception $e) {

    http_response_code(400);
    echo "Error: " . htmlspecialchars($e->getMessage());

} catch (PDOException $e) {

    http_response_code(500);
    echo "Database connection failed.";
}

?>