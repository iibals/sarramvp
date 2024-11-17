<?php
session_start();
include "header.php";

// تفعيل عرض الأخطاء
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit();
}

$user_type = $_SESSION['user_type'];

// الاتصال بقاعدة البيانات
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

// معالجة قبول الطلب
if ($user_type === 'driver') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trip_id']) && isset($_POST['user_id'])) {
        $driver_id = $_SESSION['driver_id'];
        $trip_id = intval($_POST['trip_id']);
        $user_id = intval($_POST['user_id']);

        // التأكد من أن الطلب ما زال "قيد الانتظار"
        $stmt = $conn->prepare("SELECT status FROM new_trips WHERE id = ? AND status = 'pending'");
        $stmt->bind_param("i", $trip_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->close();
            // تحديث حالة الطلب إلى "مقبول" وتحديد السائق
            $stmt = $conn->prepare("UPDATE new_trips SET status = 'accepted', accepted_driver_id = ? WHERE id = ?");
            $stmt->bind_param("ii", $driver_id, $trip_id);
            if ($stmt->execute()) {
                $stmt->close();
                // توجيه المستخدم إلى صفحة الدردشة الخاصة بالطلب
                header("Location: chat.php?trip_id=" . $trip_id);
                exit();
            } else {
                echo "حدث خطأ أثناء قبول الطلب.";
                exit();
            }
        } else {
            // الطلب لم يعد متاحًا
            echo "عذرًا، تم قبول هذا الطلب من قبل سائق آخر.";
            exit();
        }
    } else {
        echo "لم يتم تحديد الطلب أو المستخدم.";
        exit();
    }
} else {
    // للمستخدمين، لا يتم التعامل مع قبول الطلب هنا
    header('Location: my_orders.php');
    exit();
}

$conn->close();
?>

?>

<div class="container">
  <div id="header">
    <button id="call-button"><i class="fas fa-phone"></i></button>
    <h1>الدردشة</h1>
  </div>
  <div id="chat-area">
    <div class="message confirmation-message">
      <h2>تفاصيل الرحلة</h2>
        <!-- عرض تفاصيل الرحلة -->
        <p><strong>عنوان الرحلة:</strong> <?php echo htmlspecialchars($trip_title); ?></p>
        <p><strong>أيام الرحلة:</strong> <?php echo htmlspecialchars($trip_days); ?></p>
        <p><strong>وقت الانطلاق:</strong> <?php echo htmlspecialchars($trip_startTime); ?> - وقت العودة: <?php echo htmlspecialchars($trip_endTime); ?></p>
        <p><strong>التكلفة:</strong> <?php echo htmlspecialchars($trip_price); ?> ريال</p>
        <!-- عرض معلومات الطرف الآخر -->
        <?php if ($user_type === 'driver'): ?>
            <p><strong>اسم العميل:</strong> <?php echo htmlspecialchars($user_name); ?></p>
        <?php else: ?>
            <p><strong>اسم السائق:</strong> <?php echo htmlspecialchars($driver_name); ?></p>
        <?php endif; ?>
    </div>
    <!-- عرض الرسائل -->
    <div id="messages">
        <!-- سيتم تحميل الرسائل هنا بواسطة AJAX -->
    </div>
  </div>
  <div id="input-area">
    <form method="POST" action="accepted.php">
        <input type="text" id="message-input" name="message" placeholder="اكتب رسالتك هنا..." required>
        <button type="submit" class="icon-button"><i class="fas fa-paper-plane"></i></button>
    </form>
  </div>
</div>

<style>
  /* التنسيقات (CSS) */
  #header {
    background-color: #7A52B3;
    color: #FFFFFF;
    padding: 10px;
    text-align: center;
    position: relative;
  }
  #call-button {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #FFFFFF;
    font-size: 24px;
    cursor: pointer;
  }
  #chat-area {
    flex-grow: 1;
    overflow-y: auto;
    padding: 20px;
    padding-right: 5px;
    border: 1px solid #7A52B3;
    border-bottom: none;
  }
  .message {
    margin-bottom: 20px;
  }
  .message:before {
      content:"";
      position:absolute;
  }
  .confirmation-message {
    background-color: lavender;
    border: 2px solid #D3D3D3;
    padding: 20px;
    border-radius: 10px;
    width: 100%;
    text-align: center;
  }
  .confirmation-message h2 {
    color: #7A52B3;
    margin-top: 0;
  }
  .confirmation-message p {
    font-size: 16px;
    color: #333;
    margin: 10px 0;
  }
  #input-area {
    display: flex;
    padding: 10px;
    background-color: #FFFFFF;
    border-top: 1px solid #7A52B3;
  }
  #message-input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #7A52B3;
    border-radius: 4px;
    margin-right: 10px;
  }
  .icon-button {
    background: none;
    border: none;
    font-size: 20px;
    margin: 0 5px;
    cursor: pointer;
    color: #7A52B3;
  }
</style>

<!-- تضمين مكتبة jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- تحديث الرسائل تلقائيًا -->
<script>
    function loadMessages() {
        $.ajax({
            url: 'load_messages.php',
            method: 'GET',
            success: function(data) {
                $('#messages').html(data);
                // Scroll to bottom
                $('#messages').scrollTop($('#messages')[0].scrollHeight);
            }
        });
    }

    setInterval(loadMessages, 2000);

    $(document).ready(function() {
        loadMessages();
    });

    document.getElementById('call-button').addEventListener('click', function() {
        <?php if ($user_type === 'driver'): ?>
            var phoneNumber = '<?php echo $user_phone; ?>';
        <?php else: ?>
            var phoneNumber = '<?php echo $driver_phone; ?>';
        <?php endif; ?>
        alert('رقم الجوال: ' + phoneNumber);
    });
</script>

<?php include "fotter.php"; ?>
