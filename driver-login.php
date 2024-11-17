<?php
session_start();
include "header.php";

// تعيين إعدادات الكوكيز لجلسة تدوم لمدة أسبوع
$cookie_lifetime = 604800; // 7 أيام بالثواني

session_set_cookie_params([
    'lifetime' => $cookie_lifetime,
    'path' => '/',
    'domain' => '', // يمكن تحديد النطاق إذا لزم الأمر
    'secure' => isset($_SERVER['HTTPS']), // تأكد من استخدام HTTPS
    'httponly' => true,
    'samesite' => 'Lax' // يمكن أن تكون 'Strict' أو 'None' حسب الحاجة
]);

// إعداد متغيرات للرسائل
$success = '';
$error = '';

// التحقق من إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // إعداد بيانات الاتصال بقاعدة البيانات
    $servername = "localhost";
    $username = "bandsfrt_b";
    $password_db = "Aa11qq22ww33ee44rr";
    $dbname = "bandsfrt_sarra";

    // إنشاء اتصال باستخدام MySQLi
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // التحقق من الاتصال
    if ($conn->connect_error) {
        die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
    }

    // استلام وتطهير المدخلات
    $identifier = trim($_POST['identifier']); // يمكن أن يكون البريد الإلكتروني أو رقم الجوال
    $password_input = $_POST['password'];

    // التحقق من صحة المدخلات
    if (empty($identifier) || empty($password_input)) {
        $error = "جميع الحقول مطلوبة.";
    } else {
        // تحديد ما إذا كان الإدخال بريد إلكتروني أو رقم جوال
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $field = "email";
        } elseif (preg_match('/^\+?\d{10,20}$/', $identifier)) {
            $field = "phone";
        } else {
            $error = "البريد الإلكتروني أو رقم الجوال غير صالح.";
        }

        if (empty($error)) {
            // البحث عن السائق في قاعدة البيانات
            $stmt = $conn->prepare("SELECT id, name, email, phone, password FROM drivers WHERE $field = ?");
            if ($stmt) {
                // ربط المعاملات
                $stmt->bind_param("s", $identifier);

                // تنفيذ الاستعلام
                $stmt->execute();

                // الحصول على النتائج
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    // ربط النتائج إلى متغيرات
                    $stmt->bind_result($id, $name, $email_db, $phone_db, $hashed_password);
                    $stmt->fetch();

                    // التحقق من كلمة المرور
                    if (password_verify($password_input, $hashed_password)) {
                        // تسجيل الدخول بنجاح
                        $_SESSION['driver_id'] = $id;
                        $_SESSION['driver_name'] = $name;
                        $_SESSION['driver_email'] = $email_db;
                        $_SESSION['driver_phone'] = $phone_db;
                        $_SESSION['user_type'] = 'driver'; // تحديد نوع المستخدم كسائق
                        $success = "تم تسجيل الدخول بنجاح!";
                        
                        // إعادة التوجيه إلى صفحة محمية (اختياري)
                        header("Location: index.php");
                        exit();
                    } else {
                        $error = "كلمة المرور غير صحيحة.";
                    }
                } else {
                    $error = "لا يوجد سائق بهذا البريد الإلكتروني أو رقم الجوال.";
                }

                // إغلاق الاستعلام
                $stmt->close();
            } else {
                $error = "فشل في تحضير الاستعلام: " . $conn->error;
            }
        }
    }

    // إغلاق الاتصال
    $conn->close();
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-3 large-header">دخول الكابتن</h2>

            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="driver-login.php">
                <div class="form-group">
                    <label for="identifier">البريد الإلكتروني أو رقم الجوال</label>
                    <input type="text" class="form-control" id="identifier" name="identifier" placeholder="أدخل بريدك الإلكتروني أو رقم جوالك" required>
                </div>
                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="أدخل كلمة المرور" required>
                </div>
                <div class="text-center mt-3"><button type="submit" class="btn btn-primary">تسجيل الدخول</button></div>
            </form>
        </div>
    </div>
</div>

<?php include "fotter.php"?>
