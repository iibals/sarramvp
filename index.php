<?php include "header.php" ?>

    <header class="hero">
        <div class="container text-center">
            <h1 class="mb-4">تنقل آمن وموثوق للطلاب</h1>
                <p class="mb-5">نوفر خدمة نقل يومية سريعة وآمنة لطلاب الجامعات في جميع أنحاء المدينة</p>            
                    <?php 
                        // بدء الجلسة
                        session_start();
                        if(!isset($_SESSION['user_id'])) {
                            
                            // المستخدم مسجل الدخول
                            echo "<a href='register.php' class='btn btn-hero'>سجل الآن </a>";
                            
                        } else {
                            // المستخدم غير مسجل الدخول
                            echo "<a href='corder.php' class='btn btn-hero'>قدم عرضك الآن </a>";
                        }
                    ?>
        </div>
    </header>

    <section class="features-section">
        <div class="container">
            <h2 class="large-header">
                      لماذا تختار سرى ؟
                  </h2>
            <div class="row">
                <div class="col-md-3 text-center mb-4 what-ghzaly-can-do-body-skills">
                    <i class="fas fa-shield-alt feature-icon"></i>
                    <h3 class="feature-title">أمان مضمون</h3>
                    <p>نضمن سلامة ركابنا بسائقين موثوقين ومركبات آمنة</p>
                </div>
                <div class="col-md-3 text-center mb-4 what-ghzaly-can-do-body-skills">
                    <i class="fas fa-clock feature-icon"></i>
                    <h3 class="feature-title">دقة في المواعيد</h3>
                    <p>نلتزم بجداول زمنية دقيقة لضمان وصولك في الوقت المحدد</p>
                </div>
                <div class="col-md-3 text-center mb-4 what-ghzaly-can-do-body-skills">
                    <i class="fas fa-mobile-alt feature-icon"></i>
                    <h3 class="feature-title">سهولة الاستخدام</h3>
                    <p>تطبيق بسيط وسهل الاستخدام لحجز رحلاتك ومتابعتها</p>
                </div>
            </div>

        </div>
    </section>
    <?php
        // بدء الجلسة
         session_start();
    if(!isset($_SESSION['user_id']) && !isset($_SESSION['driver_id'])) { 
            // المستخدم مسجل الدخول
            echo "
            <div class='container-fluid login-now-section'>
              <div class='container'>
                          <h4 class='login-now-header p-1'>
                              سجل الان وقدم عرضك لتوصيل آمن ومريح !
                          </h4>
                          <a href='register.php' class='btn btn-primary'>سجل اﻵن</a>
                      </div>
                </div>";
        }
    ?>
    <div class="container" id="contactus">
        <h3 class="large-header mt-3" style="
            padding: 2%;
            margin-bottom: 0;
        ">
                        لاتحرمنا من رأيك
                    </h3>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <form action="index.php" method="POST">
                    <div class="mb-3 text-left">
                        <label for="name" class="form-label">إسمك</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="مثال :الاستاذ محمد">
                    </div>
                    <div class="mb-3 text-left">
                        <label for="email" class="form-label">الايميل الخاص بك</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="your@email.com">
                    </div>
                    <div class="mb-3 text-left">
                        <label for="message" class="form-label">الرسالة</label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="رسالتك"></textarea>
                    </div>
                    <div style='text-align:center;'>
                        <button type="submit" class="btn btn-primary">إرسال الرسالة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // الحصول على البيانات من النموذج مع الحماية من HTML
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $message = htmlspecialchars($_POST['message']);
        
            $to = 'b@bandar.dev'; // البريد الإلكتروني المستلم
            $subject = 'رسالة من Sarramvp'; // موضوع البريد الإلكتروني
        
            // ترميز الموضوع بـ UTF-8 باستخدام base64
            $encoded_subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        
            // جسم الرسالة
            $message_body = "لقد استلمت رسالة جديدة من المستخدم $name.\n".
                            "إليكم محتوى الرسالة:\n$message";
        
            // ترميز جسم الرسالة بـ base64
            $encoded_message = chunk_split(base64_encode($message_body));
        
            // إعداد الرؤوس مع تحديد الترميز
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: base64\r\n";
            $headers .= 'From: ' . $email . "\r\n" .
                        'Reply-To: ' . $email . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
        
            // إرسال البريد الإلكتروني
            if (mail($to, $encoded_subject, $encoded_message, $headers)) {
                echo "<script>alert('الرسالة أُرسلت بنجاح!');</script>";
            } else {
                echo "<script>alert('فشل في إرسال الرسالة.');</script>";
            }
        }
    ?>


<?php include "fotter.php" ?>

<!-- الكود الخاص بالنافذة المنبثقة -->
<?php
if(!isset($_SESSION['user_id']) && !isset($_SESSION['driver_id'])) { 
  echo "
  
  <div class='modal fade' id='loginModal' tabindex='-1' aria-labelledby='loginModalLabel' aria-hidden='true'>
    <div class='modal-dialog' role='document'>
      <div class='modal-content'>
        <div class='modal-header'>
          <!-- زر الإغلاق 'X' في الجهة اليسرى العلوية -->
          <button type='button' class='btn-close position-absolute' data-bs-dismiss='modal' aria-label='Close' style='left: 15px;'></button>
          <h5 class='modal-title mx-auto' id='loginModalLabel'>سجل دخولك الآن!</h5>
        </div>
        <div class='modal-body text-center'>
          <a href='login.php' class='btn btn-outline-primary me-2'>تسجيل الدخول</a>
          <a href='register.php' class='btn btn-primary'>أبدا الآن</a>
        </div>
        <div class='modal-footer' style='border:none;'>
          <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>إغلاق</button>
        </div>
      </div>
    </div>
  </div>

  ";
}
?>


<!-- جافا سكريبت لفتح النافذة تلقائيًا عند تحميل الصفحة -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();
  });
</script>

</body>
</html>
