<?php include "header.php" ?>
<?php session_start();

//   if(isset($_SESSION["user_id"])) {
//     header('location:index.php');
//   }


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

                    <!-- New fields for car registration -->
                    <div class="form-group">
                        <label for="identity">رقم الهوية:</label>
                        <input type="text" class="form-control" id="identity" name="identity" placeholder="أدخل رقم الهوية هنا" required>
                    </div>
                    <div class="form-group">
                        <label for="car-brand">ماركة السيارة:</label>
                        <select class="form-control" id="car-brand" name="car-brand">
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
                            <!-- Additional car brands can be added here -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="identity">نوع السيارة:</label>
                        <input type="text" class="form-control" id="car-type" name="car-type" placeholder="مازدا 6 " required>
                    </div>
                    <div class="form-group">
                        <label for="passenger-capacity">عدد الركاب المتاح:</label>
                        <select class="form-control" id="passenger-capacity" name="passenger-capacity">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="region">المنطقة داخل الرياض:</label>
                        <select class="form-control" id="region" name="region">
                            <option value="west-riyadh">غرب الرياض</option>
                            <option value="east-riyadh">شرق الرياض</option>
                            <option value="south-riyadh">جنوب الرياض</option>
                            <option value="north-riyadh">شمال الرياض</option>
                        </select>
                    </div>

                    <div class="text-center mt-3"><button type="submit" class="btn btn-primary">تسجيل</button></div>
                </form>
            </div>
        </div>
    </div>
</div>





<?php include "fotter.php"?>
