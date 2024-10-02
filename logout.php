<?php
// logout.php

// بدء الجلسة
session_start();

// إزالة جميع المتغيرات في الجلسة
$_SESSION = array();

// إذا كنت ترغب في تدمير الجلسة بالكامل، قم بحذف كوكيز الجلسة أيضًا
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// تدمير الجلسة
session_destroy();

// إعادة التوجيه إلى الصفحة الرئيسية أو صفحة تسجيل الدخول
header("Location: index.php");
exit();
?>
