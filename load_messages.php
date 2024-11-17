<?php
// load_messages.php
session_start();

if (!isset($_SESSION['user_type']) || !isset($_GET['trip_id'])) {
    exit();
}

$user_type = $_SESSION['user_type'];
$trip_id = intval($_GET['trip_id']);

// معلومات الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "bandsfrt_b";
$password_db = "Aa11qq22ww33ee44rr";
$dbname = "bandsfrt_sarra";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password_db, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// تعيين الترميز إلى utf8mb4
$conn->set_charset("utf8mb4");

// جلب جميع الرسائل الخاصة بالرحلة
$messages = array();
$stmt = $conn->prepare("
    SELECT m.sender_type, m.message, m.timestamp, 
        CASE 
            WHEN m.sender_type = 'user' THEN u.name 
            WHEN m.sender_type = 'driver' THEN d.name 
        END AS sender_name
    FROM messages m
    LEFT JOIN users u ON m.sender_id = u.id AND m.sender_type = 'user'
    LEFT JOIN drivers d ON m.sender_id = d.id AND m.sender_type = 'driver'
    WHERE m.trip_id = ?
    ORDER BY m.timestamp ASC
");
$stmt->bind_param("i", $trip_id);
$stmt->execute();
$stmt->bind_result($sender_type, $message_text, $timestamp, $sender_name);
while ($stmt->fetch()) {
    $messages[] = array(
        'sender_type' => $sender_type,
        'message' => $message_text,
        'timestamp' => $timestamp,
        'sender_name' => $sender_name
    );
}
$stmt->close();
$conn->close();

// عرض الرسائل
foreach ($messages as $msg) {
    $class = ($msg['sender_type'] === $user_type) ? 'sent' : 'received';
    echo "<div class='message $class'>
            <strong>" . htmlspecialchars($msg['sender_name']) . ":</strong> " . nl2br(htmlspecialchars($msg['message'])) . "
            <span class='timestamp'>" . htmlspecialchars(date("d-m-Y H:i", strtotime($msg['timestamp']))) . "</span>
          </div>";
}
?>
