<?php include "header.php"; ?>
<?php
// بدء الجلسة إذا كنت تستخدمها
// session_start();
// if(isset($_SESSION['user_id'])) {
//     header('Location:index.php');
// }

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
    $identity = trim($_POST['identity']);
    $car_brand = trim($_POST['car-brand']);
    $car_type = trim($_POST['car-type']);
    $passenger_capacity = intval($_POST['passenger-capacity']);
    $region = trim($_POST['region']);

    // التحقق من صحة المدخلات
    if (empty($name) || empty($email) || empty($phone) || empty($password_input) || empty($identity) || empty($car_brand) || empty($car_type) || empty($passenger_capacity) || empty($region)) {
        $error = "جميع الحقول مطلوبة.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "البريد الإلكتروني غير صالح.";
    } elseif (!preg_match('/^\+?\d{10,20}$/', $phone)) {
        $error = "رقم الجوال غير صالح.";
    } else {
        // تشفير كلمة المرور
        $password_hashed = password_hash($password_input, PASSWORD_BCRYPT);

        // إدخال البيانات باستخدام استعلام محضر
        $stmt = $conn->prepare("INSERT INTO drivers (name, email, phone, password, identity, car_brand, car_type, passenger_capacity, region) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            // ربط المعاملات
            $stmt->bind_param(
                "sssssssis",
                htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                $email,
                $phone,
                $password_hashed,
                $identity,
                $car_brand,
                $car_type,
                $passenger_capacity,
                $region
            );

            // تنفيذ الاستعلام
            if ($stmt->execute()) {
                $success = "تم التسجيل بنجاح!";
                // إعادة التوجيه إلى صفحة تسجيل الدخول
                header("Location: driver-login.php");
                exit();
            } else {
                if ($stmt->errno == 1062) {
                    if (strpos($stmt->error, 'email') !== false) {
                        $error = "البريد الإلكتروني مستخدم بالفعل.";
                    } elseif (strpos($stmt->error, 'phone') !== false) {
                        $error = "رقم الجوال مستخدم بالفعل.";
                    } elseif (strpos($stmt->error, 'identity') !== false) {
                        $error = "رقم الهوية مستخدم بالفعل.";
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
                <h2 class="mb-3 large-header">تسجيل الكابتن</h2>

                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="driver-register.php">
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
                    </div>

                    <!-- الحقول الجديدة لتسجيل السيارة -->
                    <div class="form-group">
                        <label for="identity">رقم الهوية</label>
                        <input type="text" class="form-control" id="identity" name="identity" placeholder="أدخل رقم الهوية هنا" required>
                    </div>
                    <div class="form-group">
                        <label for="car-brand">ماركة السيارة</label>
                        <select class="form-control" id="car-brand" name="car-brand" required>
                            <option value="" disabled selected>اختر ماركة السيارة</option>
                            <option value="toyota">تويوتا</option>
                            <option value="hyundai">هيونداي</option>
                            <option value="nissan">نيسان</option>
                            <option value="ford">فورد</option>
                            <option value="chevrolet">شيفروليه</option>
                            <option value="kia">كيا</option>
                            <option value="honda">هوندا</option>
                            <option value="mazda">مازدا</option>
                            <option value="bmw">بي إم دبليو</option>
                            <option value="mercedes">مرسيدس</option>
                            <option value="audi">أودي</option>
                            <option value="lexus">لكزس</option>
                            <option value="volkswagen">فولكس فاجن</option>
                            <option value="mitsubishi">ميتسوبيشي</option>
                            <option value="subaru">سوبارو</option>
                            <option value="volvo">فولفو</option>
                            <option value="suzuki">سوزوكي</option>
                            <option value="land_rover">لاند روفر</option>
                            <option value="jaguar">جاكوار</option>
                            <option value="infiniti">إنفينيتي</option>
                            <option value="tesla">تسلا</option>
                            <option value="mini">ميني</option>
                            <option value="porsche">بورش</option>
                            <option value="fiat">فيات</option>
                            <option value="jeep">جيب</option>
                            <option value="cadillac">كاديلاك</option>
                            <option value="peugeot">بيجو</option>
                            <option value="renault">رينو</option>
                            <option value="seat">سيات</option>
                            <option value="skoda">سكودا</option>
                            <option value="citroen">ستروين</option>
                            <option value="opel">أوبل</option>
                            <option value="dodge">دودج</option>
                            <option value="chrysler">كرايسلر</option>
                            <option value="alfa_romeo">ألفا روميو</option>
                            <option value="lancia">لانشيا</option>
                            <option value="lincoln">لينكولن</option>
                            <option value="isuzu">إيسوزو</option>
                            <option value="dacia">داسيا</option>
                            <option value="gmc">جي إم سي</option>
                            <option value="hummer">هامر</option>
                            <option value="mg">إم جي</option>
                            <!-- يمكنك إضافة المزيد من الماركات هنا -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="car-type">نوع السيارة</label>
                        <input type="text" class="form-control" id="car-type" name="car-type" placeholder="مثال: مازدا 6" required>
                    </div>
                    <div class="form-group">
                        <label for="passenger-capacity">عدد الركاب المتاح</label>
                        <select class="form-control" id="passenger-capacity" name="passenger-capacity" required>
                            <option value="" disabled selected>اختر عدد الركاب</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="region">المنطقة داخل الرياض</label>
                        <select class="form-control" id="region" name="region" required>
                            <option value="" disabled selected>اختر المنطقة</option>
                            <option value="west-riyadh">غرب الرياض</option>
                            <option value="east-riyadh">شرق الرياض</option>
                            <option value="south-riyadh">جنوب الرياض</option>
                            <option value="north-riyadh">شمال الرياض</option>
                        </select>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">تسجيل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "fotter.php"; ?>
