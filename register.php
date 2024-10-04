<?php include "header.php"?>
<?php
    // بدء الجلسة
    session_start();
    if(isset($_SESSION['user_id'])) {
        // المستخدم مسجل الدخول
          header('Location:index.php');
    }
// بدء جلسة للعمل مع CSRF Token إذا رغبت
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
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password_input = $_POST['password'];

    // التحقق من صحة المدخلات
    if (empty($name) || empty($email) || empty($phone) || empty($password_input)) {
        $error = "جميع الحقول مطلوبة.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "البريد الإلكتروني غير صالح.";
    } elseif (!preg_match('/^\+?\d{10,20}$/', $phone)) { // تحقق من صحة رقم الجوال
        $error = "رقم الجوال غير صالح.";
    } else {
        // تشفير كلمة المرور
        $password_hashed = password_hash($password_input, PASSWORD_BCRYPT);

        // إدخال البيانات باستخدام استعلام محضر
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            // ربط المعاملات
            $stmt->bind_param("ssss", htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), $email, $phone, $password_hashed);

            // تنفيذ الاستعلام
            if ($stmt->execute()) {
                $success = "تم التسجيل بنجاح!";
                // إعادة التوجيه إلى صفحة محمية (اختياري)
                header("Location: login.php");
            } else {
                if ($stmt->errno == 1062) { // كود خطأ تكرار البريد الإلكتروني أو رقم الجوال
                    if (strpos($stmt->error, 'email') !== false) {
                        $error = "البريد الإلكتروني مستخدم بالفعل.";
                    } elseif (strpos($stmt->error, 'phone') !== false) {
                        $error = "رقم الجوال مستخدم بالفعل.";
                    } else {
                        $error = "حدث خطأ أثناء التسجيل: " . $stmt->error;
                    }
                } else {
                    $error = "حدث خطأ أثناء التسجيل: " . $stmt->error;
                }
            }

            // إغلاق الاستعلام
            $stmt->close();
        } else {
            $error = "فشل في تحضير الاستعلام: " . $conn->error;
        }
    }

    // إغلاق الاتصال
    $conn->close();
}
?>
<div class="classCarry">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-3 large-header">تسجيل</h2>

                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="register.php">
                    <div class="form-group">
                        <label for="name">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="اسمك" required>
                    </div>
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="البريد الإلكتروني" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">رقم الجوال</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="رقم الجوال" required>
                    </div>
                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="كلمة المرور" required>
                        <a class="to-driver-section" href="driver-login.php"> اضغط هنا لدخول الكابتن</a>
                    </div>
                    <div class="text-center mt-3"><button type="submit" class="btn btn-primary">تسجيل</button</div>
                </form>
            </div>
        </div>
    </div>
    </div>




<?php include "fotter.php"?>
