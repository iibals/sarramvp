<?php include "header.php"; ?>
<?php
// تمكين عرض الأخطاء للتصحيح (يُفضل تعطيله في بيئة الإنتاج)
    // بدء الجلسة
    session_start();
    if(!isset($_SESSION['user_id'])) {
        // المستخدم مسجل الدخول
          header('Location:index.php');
    }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // استلام البيانات والتحقق منها
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
    $days = isset($_POST['days']) ? $_POST['days'] : [];
    $startTime = isset($_POST['startTime']) ? $_POST['startTime'] : '';
    $endTime = isset($_POST['endTime']) ? $_POST['endTime'] : '';
    $userCoords = isset($_POST['userCoords']) ? $_POST['userCoords'] : '';
    $destCoords = isset($_POST['destCoords']) ? $_POST['destCoords'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';

    // تنظيف المدخلات
    $title = htmlspecialchars($title);
    $notes = htmlspecialchars($notes);
    $startTime = htmlspecialchars($startTime);
    $endTime = htmlspecialchars($endTime);
    $price = htmlspecialchars($price);

    // تحقق من أن العنوان مدخل
    if (empty($title)) {
        die("يرجى إدخال عنوان الطلب.");
    }

    // تحقق من أن السعر رقم صالح وفي النطاق المطلوب
    if (!is_numeric($price) || $price < 500 || $price > 1000) {
        die("يرجى إدخال سعر صالح بين 500 و1000.");
    }

    // تحقق من أن الإحداثيات غير فارغة
    if (empty($userCoords) || empty($destCoords)) {
        die("إحداثيات غير صالحة.");
    }

    // التحقق من أن الأوقات مدخلة
    if (empty($startTime) || empty($endTime)) {
        die("يرجى إدخال وقت الانطلاق والعودة.");
    }

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

    // تحويل مصفوفة الأيام إلى نص
    $daysStr = implode(',', $days);

    try {
        // تحضير الاستعلام
        $stmt = $conn->prepare("INSERT INTO trips (title, notes, days, startTime, endTime, userCoords, destCoords, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssd", $title, $notes, $daysStr, $startTime, $endTime, $userCoords, $destCoords, $price);

        // تنفيذ الاستعلام
        if ($stmt->execute()) {
            // إعادة التوجيه إلى صفحة أخرى أو عرض رسالة نجاح
            header("Location: orders.php");
            exit();
        } else {
            echo "خطأ في تنفيذ الاستعلام: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo "حدث خطأ أثناء تنفيذ الاستعلام: " . $e->getMessage();
    }
}
?>
    <!-- تضمين مكتبة جوجل ماب -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAoEIYgSq4JljZkVYRvkdx1jC6mSCi_p6g&callback=initMap" async defer></script>

    <style>
        /* تنسيق الخريطتين */
        #userMap, #destMap {
            height: 400px;
            width: 100%;
        }
        .map-container {
            margin-bottom: 20px;
        }
    </style>
    <div class="container mt-5">
        <!-- عنوان الطلب -->
        <h2 class="mb-4 text-center large-header" style="color: #7A52B3;">عنوان الطلب</h2>

        <form method="POST" action="corder.php" onsubmit="return validateForm()">
            <!-- حقل عنوان الطلب -->
            <div class="form-group">
                <input type="text" class="form-control" id="title" name="title" placeholder="مثال: رحلة الجامعة الأسبوعية" required>
            </div>

            <!-- خريطة موقع المستخدم -->
            <div class="map-container">
                <h4>موقعك الحالي</h4>
                <div id="userMap"></div>
            </div>
            <!-- خريطة الوجهة -->
            <div class="map-container">
                <h4>الوجهة</h4>
                <div id="destMap"></div>
            </div>

            <div class="form-group mt-3">
                <label>أيام الرحلة:</label><br>
                <!-- عناصر اختيار الأيام -->
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="sunday" name="days[]" value="Sunday">
                    <label class="form-check-label" for="sunday">الأحد</label>
                </div>
                <!-- اليوم الاثنين -->
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="monday" name="days[]" value="Monday">
                    <label class="form-check-label" for="monday">الاثنين</label>
                </div>
                <!-- اليوم الثلاثاء -->
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="tuesday" name="days[]" value="Tuesday">
                    <label class="form-check-label" for="tuesday">الثلاثاء</label>
                </div>
                <!-- اليوم الأربعاء -->
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="wednesday" name="days[]" value="Wednesday">
                    <label class="form-check-label" for="wednesday">الأربعاء</label>
                </div>
                <!-- اليوم الخميس -->
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="thursday" name="days[]" value="Thursday">
                    <label class="form-check-label" for="thursday">الخميس</label>
                </div>
            </div>
            <div class="form-group">
                <label for="startTime">وقت الانطلاق:</label>
                <input type="time" class="form-control" id="startTime" name="startTime" required>
            </div>
            <div class="form-group">
                <label for="endTime">وقت العودة:</label>
                <input type="time" class="form-control" id="endTime" name="endTime" required>
            </div>
            <div class="form-group">
                <label for="price">السعر للعرض الشهري (بين 500 و1000):</label>
                <input type="number" class="form-control" id="price" name="price" min="500" max="1000" required>
            </div>
            <!-- حقل الملاحظات -->
            <div class="form-group">
                <label for="notes">الملاحظات:</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="أدخل أي ملاحظات إضافية هنا..."></textarea>
            </div>
            <!-- مدخلات مخفية لتخزين إحداثيات العلامات -->
            <input type="hidden" id="userCoords" name="userCoords">
            <input type="hidden" id="destCoords" name="destCoords">
            <div class="text-center mt-3"> <button type="submit" class="btn btn-primary">تقديم العرض</button></div>
        </form>
    </div>

    <script>
let userMap, destMap, userMarker, destMarker;

// تهيئة الخرائط عند تحميل الصفحة
function initMap() {
    // موقع افتراضي (مثلاً: الرياض)
    const defaultPos = { lat: 24.7136, lng: 46.6753 };

    // تهيئة خريطة موقع المستخدم
    userMap = new google.maps.Map(document.getElementById('userMap'), {
        center: defaultPos,
        zoom: 13
    });

    // محاولة الحصول على موقع المستخدم الحالي
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                userMap.setCenter(pos);
                placeUserMarker(pos);
            },
            (error) => {
                handleLocationError(true, userMap.getCenter());
                placeUserMarker(defaultPos); // استخدام الموقع الافتراضي في حالة الفشل
            }
        );
    } else {
        // المتصفح لا يدعم الجيولوكيشن
        handleLocationError(false, userMap.getCenter());
        placeUserMarker(defaultPos); // استخدام الموقع الافتراضي
    }

    // تهيئة خريطة الوجهة
    destMap = new google.maps.Map(document.getElementById('destMap'), {
        center: defaultPos,
        zoom: 13
    });

    // إضافة مستمع للنقر على خريطة الوجهة لتحديد الوجهة
    destMap.addListener('click', function(e) {
        placeDestMarker(e.latLng, destMap);
    });
}

// التعامل مع أخطاء الجيولوكيشن
function handleLocationError(browserHasGeolocation, pos) {
    alert(
        browserHasGeolocation
            ? 'خطأ: فشل في الحصول على الموقع.'
            : 'خطأ: متصفحك لا يدعم الجيولوكيشن.'
    );
}

// وضع أو تحديث علامة موقع المستخدم
function placeUserMarker(pos) {
    // إزالة العلامة السابقة إذا كانت موجودة
    if (userMarker) {
        userMarker.setMap(null);
    }

    // إنشاء علامة المستخدم قابلة للسحب
    userMarker = new google.maps.Marker({
        position: pos,
        map: userMap,
        label: "موقعك",
        draggable: true // جعل العلامة قابلة للسحب
    });

    // تحديث الإحداثيات في الحقل المخفي
    document.getElementById('userCoords').value = `${pos.lat},${pos.lng}`;

    // إضافة مستمع للأحداث عند انتهاء السحب
    userMarker.addListener('dragend', function(event) {
        const newPos = {
            lat: event.latLng.lat(),
            lng: event.latLng.lng()
        };
        document.getElementById('userCoords').value = `${newPos.lat},${newPos.lng}`;
    });

    // إضافة مستمع للنقر على خريطة المستخدم لتحريك العلامة
    userMap.addListener('click', function(e) {
        userMarker.setPosition(e.latLng);
        userMap.setCenter(e.latLng);
        document.getElementById('userCoords').value = `${e.latLng.lat()},${e.latLng.lng()}`;
    });
}

// وضع أو تحديث علامة الوجهة
function placeDestMarker(latLng, map) {
    // إزالة العلامة السابقة إذا كانت موجودة
    if (destMarker) {
        destMarker.setMap(null);
    }

    // إنشاء علامة الوجهة
    destMarker = new google.maps.Marker({
        position: latLng,
        map: map,
        label: "الوجهة"
    });

    // تحديث الإحداثيات في الحقل المخفي
    document.getElementById('destCoords').value = `${latLng.lat()},${latLng.lng()}`;
}

// التحقق من صحة النموذج قبل الإرسال
function validateForm() {
    const title = document.getElementById('title').value.trim();
    const days = document.querySelectorAll('input[name="days[]"]:checked');
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;
    const destCoords = document.getElementById('destCoords').value;
    const price = document.getElementById('price').value;

    if (title === '') {
        alert('يرجى إدخال عنوان الطلب.');
        return false;
    }

    if (days.length === 0) {
        alert('يرجى اختيار يوم واحد على الأقل للرحلة.');
        return false;
    }

    if (!startTime || !endTime) {
        alert('يرجى تحديد وقت الانطلاق والعودة.');
        return false;
    }

    if (!destCoords) {
        alert('يرجى تحديد الوجهة على الخريطة.');
        return false;
    }

    if (!price || price < 500 || price > 1000) {
        alert('يرجى إدخال سعر بين 500 و1000.');
        return false;
    }

    return true;
}

// تحميل الخرائط عند تحميل الصفحة
window.onload = initMap;

    </script>




<?php include "fotter.php"; ?>
