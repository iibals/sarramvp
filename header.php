<?php 

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/sarra.png" />
    <title>Sarra</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/main.css"/>

    <style>
    </style>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-D8T2N6ZW5J"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-D8T2N6ZW5J');
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="images/sarra.png" alt="Sarra Logo"></a>
                <div style="display:flex;margin-right:auto;">
                    <?php 
                        // phones
                        session_start();
                        // بدء الجلسة
                        if(isset($_SESSION['user_id'])) {
                            // المستخدم مسجل الدخول
                            echo "
                                <a class='btn btn-outline-primary me-2              btn-phone' href='corder.php'>قدم عرض </a>
                                <a class='btn btn-primary                           btn-phone' href='orders.php'> العروض</a>
                                <a href='logout.php' class='btn btn-outline-primary btn-phone'><i class='bi bi-box-arrow-left'></i></a>
                            ";
                        } else if(isset($_SESSION['driver_id'])) {
                            echo "
                                <a class='btn btn-primary                           btn-phone' href='orders.php'> العروض</a>
                                <a href='logout.php' class='btn btn-outline-primary btn-phone'><i class='bi bi-box-arrow-left'></i></a>
                            ";
                            // المستخدم غير مسجل الدخول
                        } else {
                                                        echo "
                                <a class='btn btn-outline-primary me-2              btn-phone' href='login.php'>تسجيل الدخول</a>
                                <a class='btn btn-primary                           btn-phone' href='register.php'>ابدأ الآن</a>
                            ";
                        }
                    ?>
                    
                    <?php 
                    
                        // if(isset($_SESSION['driver_id']))
                        // {
                        //     header("Location: https://google.com");
                        // }
                    ?>
                 </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">خدماتنا</a></li>
         <?php  if(isset($_SESSION['user_id']) || isset($_SESSION['driver_id']))
                    echo " <li class='nav-item'><a class='nav-link' href='my_orders.php'>طلباتي</a></li>" 
                    
                    ?>
                    <li class="nav-item"><a class="nav-link" href="#about">من نحن</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contactus">اتصل بنا</a></li>
                </ul>
                
                <div class="mr-auto" style="margin-right:auto;">
                    <?php 
                        // بدء الجلسة
                        if(isset($_SESSION['user_id'])) {
                            // المستخدم مسجل الدخول
                            echo "
                                <a class='btn btn-outline-primary me-2              btn-phone-hide' href='corder.php'>قدم عرض </a>
                                <a class='btn btn-primary                           btn-phone-hide' href='orders.php'> العروض</a>
                                <a href='logout.php' class='btn btn-outline-primary btn-phone-hide'><i class='bi bi-box-arrow-left'></i></a>
                            ";
                        } else if(isset($_SESSION['driver_id'])) {
                            echo "
                                <a class='btn btn-primary                           btn-phone-hide' href='orders.php'> العروض</a>
                                <a href='logout.php' class='btn btn-outline-primary btn-phone-hide'><i class='bi bi-box-arrow-left'></i></a>
                            ";
                            // المستخدم غير مسجل الدخول
                        } else {
                                                        echo "
                                <a class='btn btn-outline-primary me-2              btn-phone-hide' href='login.php'>تسجيل الدخول</a>
                                <a class='btn btn-primary                           btn-phone-hide' href='register.php'>ابدأ الآن</a>
                            ";
                        }
                    ?>


                
                </div>
            </div>
        </div>
    </nav>
<div class="classCarry">