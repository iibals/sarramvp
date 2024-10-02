<?php include "header.php" ?>
<?php session_start();

  if(isset($_SESSION["user_id"])) {
    header('location:index.php');
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

                <form method="POST" action="driver.register.php">
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
                    <div class="text-center mt-3"><button type="submit" class="btn btn-primary">تسجيل</button</div>
                </form>
            </div>
        </div>
    </div>
    </div>




<?php include "fotter.php"?>
