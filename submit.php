<?php
// Collect form data
$name = $_POST['name'];
$phone = $_POST['phone'];
$pickup = $_POST['pickup'];
$drop = $_POST['drop'];
$datetime = $_POST['datetime'];
$trip_type = $_POST['trip_type'];
$vehicle = $_POST['vehicle'];

// 1️⃣ Save to MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nextdrivr_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO bookings (name, phone, pickup, drop_location, datetime, trip_type, vehicle)
VALUES ('$name', '$phone', '$pickup', '$drop', '$datetime', '$trip_type', '$vehicle')";
$conn->query($sql);
$conn->close();

// 2️⃣ Send Email
$to = "your-email@example.com";
$subject = "New Booking - NextDrivr Bihar";
$message = "
Name: $name\n
Phone: $phone\n
Pickup: $pickup\n
Drop: $drop\n
Date & Time: $datetime\n
Trip Type: $trip_type\n
Vehicle: $vehicle
";
$headers = "From: booking@nextdrivr.in";
mail($to, $subject, $message, $headers);

// 3️⃣ Send to Google Sheets
$sheet_url = "YOUR_GOOGLE_SHEET_WEBHOOK_URL_HERE";

$data = json_encode([
  "name" => $name,
  "phone" => $phone,
  "pickup" => $pickup,
  "drop" => $drop,
  "datetime" => $datetime,
  "trip_type" => $trip_type,
  "vehicle" => $vehicle
]);

$options = [
  'http' => [
    'method'  => 'POST',
    'header'  => "Content-type: application/json",
    'content' => $data
  ]
];
$context = stream_context_create($options);
file_get_contents($sheet_url, false, $context);

// 4️⃣ Redirect
echo "<script>alert('Booking Successful!'); window.location.href='thankyou.html';</script>";
?>
