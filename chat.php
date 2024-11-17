<?php
// chat.php
session_start();
include "header.php";

// تفعيل عرض الأخطاء (لأغراض التطوير فقط)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// التحقق من تسجيل الدخول ووجود trip_id
if (!isset($_SESSION['user_type']) || !isset($_GET['trip_id'])) {
    header('Location: index.php');
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

// جلب معلومات الرحلة
$stmt = $conn->prepare("SELECT user_id, accepted_driver_id, title, days, startTime, endTime, price FROM new_trips WHERE id = ?");
$stmt->bind_param("i", $trip_id);
$stmt->execute();
$stmt->bind_result($user_id, $driver_id, $trip_title, $trip_days, $startTime, $endTime, $trip_price);
if (!$stmt->fetch()) {
    echo "الطلب غير موجود.";
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

// التحقق من صلاحية الوصول
if ($user_type === 'driver') {
    if (!isset($_SESSION['driver_id']) || $_SESSION['driver_id'] != $driver_id) {
        echo "ليس لديك صلاحية الوصول إلى هذه المحادثة.";
        $conn->close();
        exit();
    }
    $sender_id = $_SESSION['driver_id'];
    $sender_type = 'driver';
    $receiver_id = $user_id;
    $receiver_type = 'user';
    
    // جلب معلومات المستخدم
    $stmt = $conn->prepare("SELECT name, phone FROM users WHERE id = ?");
    $stmt->bind_param("i", $receiver_id);
    $stmt->execute();
    $stmt->bind_result($receiver_name, $receiver_phone);
    $stmt->fetch();
    $stmt->close();
    
} elseif ($user_type === 'user') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $user_id) {
        echo "ليس لديك صلاحية الوصول إلى هذه المحادثة.";
        $conn->close();
        exit();
    }
    $sender_id = $_SESSION['user_id'];
    $sender_type = 'user';
    $receiver_id = $driver_id;
    $receiver_type = 'driver';
    
    // جلب معلومات السائق
    $stmt = $conn->prepare("SELECT name, phone FROM drivers WHERE id = ?");
    $stmt->bind_param("i", $receiver_id);
    $stmt->execute();
    $stmt->bind_result($receiver_name, $receiver_phone);
    $stmt->fetch();
    $stmt->close();
    
} else {
    echo "نوع المستخدم غير معروف.";
    $conn->close();
    exit();
}

// معالجة إرسال الرسائل
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && !empty(trim($_POST['message']))) {
    $message = trim($_POST['message']);
    
    // إدراج الرسالة في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO messages (trip_id, sender_id, sender_type, receiver_id, receiver_type, message, timestamp) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iissss", $trip_id, $sender_id, $sender_type, $receiver_id, $receiver_type, $message);
    if ($stmt->execute()) {
        // تم إرسال الرسالة بنجاح
        $stmt->close();
        // إعادة التوجيه لتجنب إعادة إرسال النموذج عند التحديث
        header("Location: chat.php?trip_id=" . $trip_id);
        exit();
    } else {
        echo "حدث خطأ أثناء إرسال الرسالة.";
    }
}

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
$stmt->bind_result($msg_sender_type, $msg_text, $msg_timestamp, $msg_sender_name);
while ($stmt->fetch()) {
    $messages[] = array(
        'sender_type' => $msg_sender_type,
        'message' => $msg_text,
        'timestamp' => $msg_timestamp,
        'sender_name' => $msg_sender_name
    );
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الدردشة - <?php echo htmlspecialchars($trip_title); ?></title>
    <!-- تضمين Font Awesome للرموز -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* تنسيقات الصفحة */
        body {
            background-color: #f8f9fa;
        }
        #chat-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 80vh;
        }
        #chat-header {
            background-color: #7A52B3;
            color: #ffffff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #chat-messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f1f1f1;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 20px;
            max-width: 70%;
            position: relative;
            word-wrap: break-word;
        }
        .sent {
            background-color: #DCF8C6;
            align-self: flex-end;
            margin-left: auto;
        }
        .received {
            background-color: #ffffff;
            align-self: flex-start;
            margin-right: auto;
        }
        .timestamp {
            display: block;
            font-size: 0.8em;
            color: #666;
            margin-top: 5px;
            text-align: left;
        }
        #chat-input {
            padding: 15px;
            border-top: 1px solid #ddd;
            display: flex;
            align-items: center;
            background-color: #ffffff;
        }
        #chat-input input {
            flex-grow: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            margin-left: 10px;
        }
        #chat-input button {
            background-color: #7A52B3;
            border: none;
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 50%;
            cursor: pointer;
        }
        #call-button {
            background: none;
            border: none;
            color: #ffffff;
            font-size: 24px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="chat-container">
        <div id="chat-header">
            <div>
                <h4 class="mb-0"><?php echo htmlspecialchars($trip_title); ?></h4>
                <small><?php echo htmlspecialchars($trip_days); ?>|
                <i class="bi bi-calendar-check"></i> <?php echo htmlspecialchars(date("g:i A", strtotime($startTime))); ?> -
                <?php echo htmlspecialchars(date("g:i A", strtotime($endTime))); ?> |
                <i class="bi bi-cash"></i> <?php echo htmlspecialchars($trip_price); ?> </small>
            </div>
            <button id="call-button" title="اتصال"><i class="fas fa-phone"></i></button>
        </div>
        <div id="chat-messages">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message <?php echo ($msg['sender_type'] === $user_type) ? 'sent' : 'received'; ?>">
                        <strong><?php echo htmlspecialchars($msg['sender_name']); ?>:</strong>
                        <span><?php echo nl2br(htmlspecialchars($msg['message'])); ?></span>
                        <span class="timestamp"><?php echo htmlspecialchars(date("d-m-Y H:i", strtotime($msg['timestamp']))); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted">لا توجد رسائل بعد.</p>
            <?php endif; ?>
        </div>
        <div id="chat-input">
            <form method="POST" action="chat.php?trip_id=<?php echo $trip_id; ?>" style="width: 100%; display: flex;">
                <input type="text" name="message" placeholder="اكتب رسالتك هنا..." required>
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>

    <!-- تضمين مكتبة jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- تضمين Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // وظيفة لتحميل الرسائل بشكل دوري
        function loadMessages() {
            $.ajax({
                url: 'load_messages.php',
                method: 'GET',
                data: { trip_id: <?php echo $trip_id; ?> },
                success: function(data) {
                    $('#chat-messages').html(data);
                    // التمرير إلى أسفل الرسائل
                    $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
                },
                error: function() {
                    console.error("حدث خطأ أثناء تحميل الرسائل.");
                }
            });
        }

        // تحميل الرسائل عند تحميل الصفحة وتحديثها كل 5 ثوانٍ
        $(document).ready(function() {
            loadMessages();
            setInterval(loadMessages, 5000);
        });

        // وظيفة لعرض رقم الجوال عند النقر على زر الاتصال
        document.getElementById('call-button').addEventListener('click', function() {
            var phoneNumber = '<?php echo htmlspecialchars($receiver_phone); ?>';
            alert('رقم الجوال: ' + phoneNumber);
        });
    </script>
</body>
</html>

<?php include "fotter.php"; ?>
