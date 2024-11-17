<?php
session_start();
include "header.php";


// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id']) && !isset($_SESSION['driver_id'])) {
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

$trips = array(); // تهيئة المصفوفة

if ($user_type === 'driver') {
    $driver_id = $_SESSION['driver_id'];
    // جلب الطلبات التي قبلها السائق
    $stmt = $conn->prepare("SELECT id, title, price, notes, days, startTime, endTime, user_id FROM new_trips WHERE accepted_driver_id = ?");
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    $stmt->bind_result($id, $title, $price, $notes, $days, $startTime, $endTime, $user_id);
    while ($stmt->fetch()) {
        $trips[] = array(
            'id' => $id,
            'title' => $title,
            'price' => $price,
            'notes' => $notes,
            'days' => $days,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'user_id' => $user_id
        );
    }
    $stmt->close();
} elseif ($user_type === 'user') {
    $user_id = $_SESSION['user_id'];
    // جلب الطلبات التي أنشأها المستخدم وتم قبولها
    $stmt = $conn->prepare("SELECT id, title, price, notes, days, startTime, endTime, accepted_driver_id FROM new_trips WHERE user_id = ? AND status = 'accepted'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($id, $title, $price, $notes, $days, $startTime, $endTime, $accepted_driver_id);
    while ($stmt->fetch()) {
        $trips[] = array(
            'id' => $id,
            'title' => $title,
            'price' => $price,
            'notes' => $notes,
            'days' => $days,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'accepted_driver_id' => $accepted_driver_id
        );
    }
    $stmt->close();
} else {
    // header('Location: index.php');
    // exit();
}

$conn->close();
?>

<!-- عرض الطلبات -->
<div class="container mt-5">
    <style>
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
<h2 class="mb-4 text-center large-header" style="color: #7A52B3;">طلباتي</h2>
<div class="accordion" id="myTripsAccordion">
    <?php
    if (!empty($trips)) {
        $count = 0;
        foreach ($trips as $trip) {
            $count++;
            $tripId = $trip['id'];
            $accordionId = "collapse" . $count;
            $headingId = "heading" . $count;
            $title = $trip['title'];
            $price = $trip['price'];
            $notes = $trip['notes'];
            $days = $trip['days'];
            $startTime = date("g:i A", strtotime($trip['startTime']));
            $endTime = date("g:i A", strtotime($trip['endTime']));

            echo '
            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="'.$headingId.'">
                    <button class="accordion-button collapsed d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#'.$accordionId.'" aria-expanded="false" aria-controls="'.$accordionId.'">
                        <i class="bi bi-pin-map-fill me-3" style="color: #7A52B3; font-size: 1.5rem;"></i>
                        '.$title.'
                    </button>
                </h2>
                <div id="'.$accordionId.'" class="accordion-collapse collapse" aria-labelledby="'.$headingId.'" data-bs-parent="#myTripsAccordion">
                    <div class="accordion-body" style="background-color: #fff;">
                        <ul class="list-unstyled mb-3">
                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar-week me-2" style="color: #7A52B3;"></i>
                                <span>أيام الرحلة: '.$days.'</span>
                            </li>
                            <li class="d-flex align-items-center mb-2">
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

            // إضافة زر "الدردشة"
            echo '
            <a href="chat.php?trip_id='.$tripId.'" class="btn btn-primary btn-custom">الدردشة</a>
            ';

            echo '
                    </div>
                </div>
            </div>
            ';
        }
    } else {
        echo '<p class="text-center">لا توجد طلبات متاحة حاليًا.</p>';
    }
    ?>

    </div>
</div>

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

<?php include "fotter.php"; ?>
