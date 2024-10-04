<?php include "header.php" ?>
<?php session_start();

//   if(isset($_SESSION["user_id"])) {
//     header('location:index.php');
//   }


?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-3 large-header"> دخول الكابتن</h2>

            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="driver.login.php">
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
