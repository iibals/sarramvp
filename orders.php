<?php
session_start();
include "header.php";

// التحقق من تسجيل الدخول
if(!isset($_SESSION['user_id']) && !isset($_SESSION['driver_id'])) {
    // المستخدم غير مسجل الدخول
    header('Location: index.php');
    exit();
}

$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';

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

// استعلام لاسترجاع العروض التي حالتها 'pending' فقط من جدول الرحلات
$sql = "SELECT * FROM new_trips WHERE status = 'pending'";
$result = $conn->query($sql);
?>
    <style>
        /* التنسيقات (CSS) الخاصة بالصفحة */
        .btn-custom {
            background-color: #7A52B3;
            border-color: #7A52B3;
            transition: transform 0.3s, background-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #6a439d;
            transform: scale(1.05);
        }

        .accordion-button {
            background-color: #f9f9f9;
            color: #343a40;
            transition: background-color 0.3s, color 0.3s;
        }

        .accordion-button:hover {
            background-color: #e0d4f5;
            color: #7A52B3;
        }

        .accordion-button:focus {
            box-shadow: none;
            background-color: #e0d4f5;
            color: #7A52B3;
        }

        .accordion-button:not(.collapsed) {
            background-color: #e0d4f5;
            color: #7A52B3;
        }

        .accordion-body {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* تحسين حجم الأيقونات في العناوين */
        .accordion-button .bi {
            font-size: 1.5rem;
        }

        /* تخصيص لون خلفية العروض */
        .accordion-item {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: right;
        }

        .accordion-body ul li {
            font-size: 0.95rem;
        }
    </style>
    <div class="container mt-5">
        <!-- قسم العروض -->
        <h2 class="mb-4 text-center large-header" style="color: #7A52B3;">العروض المتاحة</h2>
        <div class="accordion" id="offersAccordion">
            <?php
            if ($result->num_rows > 0) {
                $count = 0;
                while($row = $result->fetch_assoc()) {
                    $count++;
                    $offerId = "collapse" . $count;
                    $headingId = "heading" . $count;
                    $title = $row['title'];
                    $price = $row['price'];
                    $notes = $row['notes'];
                    $days = $row['days'];
                    $startTime = date("g:i A", strtotime($row['startTime']));
                    $endTime = date("g:i A", strtotime($row['endTime']));
                    $userCoords = $row['userCoords'];
                    $destCoords = $row['destCoords'];
                    $trip_id = $row['id'];
                    $trip_user_id = $row['user_id']; // معرف المستخدم الذي أنشأ الرحلة

                    // يمكنك استخدام الإحداثيات لعرض الخريطة إذا أردت

                    echo '
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="'.$headingId.'">
                            <button class="accordion-button collapsed d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#'.$offerId.'" aria-expanded="false" aria-controls="'.$offerId.'">
                                <i class="bi bi-pin-map-fill me-3" style="color: #7A52B3; font-size: 1.5rem;"></i>
                                '.$title.'
                            </button>
                        </h2>
                        <div id="'.$offerId.'" class="accordion-collapse collapse" aria-labelledby="'.$headingId.'" data-bs-parent="#offersAccordion">
                            <div class="accordion-body" style="background-color: #fff;">
                                <ul class="list-unstyled mb-3">
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-week me-2" style="color: #7A52B3;"></i>
                                        <span>أيام الرحلة: '.$days.'</span>
                                    </li>
                                    <li class="d-flex alignمينs-center mb-2">
                                        <i class="bi bi-clock me-2" style="color: #7A52B3;"></i>
                                        <span>وقت الانطلاق: '.$startTime.' - وقت العودة: '.$endTime.'</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="bi bi-cash-stack me-2" style="color: #7A52B3;"></i>
                                        <span>السعر: '.$price.' ريال</span>
                                    </li>';
                    if (!empty($notes)) {
                        echo '
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="bi bi-chat-left-text me-2" style="color: #7A52B3;"></i>
                                        <span>ملاحظات: '.$notes.'</span>
                                    </li>';
                    }
                    echo '
                                </ul>';

                    // إضافة زر "ابدأ التوصيل" للسائقين فقط
                    if ($user_type === 'driver') {
                        echo '
                        <form method="POST" action="accepted.php">
                            <input type="hidden" name="trip_id" value="'.$trip_id.'">
                            <input type="hidden" name="user_id" value="'.$trip_user_id.'">
                            <button type="submit" class="btn btn-primary btn-custom">ابدأ التوصيل</button>
                        </form>
                        ';
                    }

                    echo '
                            </div>
                        </div>
                    </div>
                    ';
                }
            } else {
                echo '<p class="text-center">لا توجد عروض متاحة حاليًا.</p>';
            }
            $conn->close();
            ?>
        </div>
    </div>

    <!-- تضمين Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- إذا كنت تستخدم Bootstrap 5، يمكنك استخدام CDN التالي -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- إضافة تأثيرات الأنيميشن عند الضغط -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var accordionButtons = document.querySelectorAll('.accordion-button');

            accordionButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var target = document.querySelector(this.getAttribute('data-bs-target'));
                    if (target.classList.contains('show')) {
                        $(target).collapse('hide');
                    } else {
                        $(target).collapse('show');
                    }
                });
            });
        });
    </script>

<?php include "fotter.php"?>
