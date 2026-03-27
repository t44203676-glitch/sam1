<?php
// views/home.php
?>

<!-- قسم الهيرو (محدث) -->
<section id="home" class="hero-section text-center py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-3 fw-bold mb-4 animate__animated animate__fadeInDown">
                    مكتب الخدمات المتكاملة: <span class="text-warning">حلول عصرية لخدماتك</span>
                </h1>
                <p class="lead mb-5 animate__animated animate__fadeInUp">
                    نقدم لكم خدماتنا الحكومية والرسمية بأسلوب رقمي متطور، يجمع بين الكفاءة والسرعة. فريقنا المتخصص يضمن لك إنجاز معاملاتك بدقة واحترافية عالية.
                    <br>
                    <strong class="text-warning">تجربة سلسة، نتائج مضمونة.</strong>
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap animate__animated animate__fadeInUp">
                    <a href="index.php?page=inquiry" class="btn btn-warning btn-lg shadow-sm">
                        <i class="fas fa-search me-2"></i> استعلام عن معاملة
                    </a>
                    <a href="index.php?page=services" class="btn btn-outline-light btn-lg shadow-sm">
                        <i class="fas fa-cogs me-2"></i> عرض الخدمات
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- قسم الخدمات (محدث) -->
<section id="services" class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-2 fw-bold text-primary">خدماتنا الرئيسية</h2>
        <p class="text-center text-muted mb-5">مجموعة واسعة من الخدمات الحكومية والرسمية بأعلى معايير الجودة والكفاءة.</p>
        
        <div class="row">
            <?php
// استخدام بيانات تجريبية إذا لم تكن متوفرة
// تم تحديث القائمة لتشمل 8 خدمات بالإضافة إلى بطاقة "إضافة بيانات" ليصبح المجموع 9
$mock_services = [
    ['service_name' => 'خدمة تصاريح زواج', 'service_description' => 'خدمة للحصول على تصاريح الزواج الرسمية.'],
    ['service_name' => 'خدمة إصدار تأشيرة عمالة', 'service_description' => 'خدمة لإصدار تأشيرة العمالة والتصاريح.'],
    ['service_name' => 'خدمة إصدار تأشيرة زيارة شخصية', 'service_description' => 'خدمة لإصدار تأشيرة الزيارة الشخصية.'],
    ['service_name' => 'خدمة إصدار تأشيرة الاستقدام', 'service_description' => 'خدمة لإصدار تأشيرة الاستقدام.'],
    ['service_name' => 'خدمة تعديل المهنة', 'service_description' => 'خدمة لتعديل المهنة في الوثائق.'],
    ['service_name' => 'خدمة نقل كفالة', 'service_description' => 'خدمة لنقل الكفالة بين الكفيلين.'],
    ['service_name' => 'خدمة التعقيب', 'service_description' => 'خدمة لمتابعة وإنهاء المعاملات الرسمية.']
];
$display_services = !empty($services) ? $services : $mock_services;

// مصفوفة أيقونات متنوعة للخدمات
$icons = ['fa-heart', 'fa-id-card', 'fa-briefcase', 'fa-plane-departure', 'fa-users', 'fa-user-edit', 'fa-exchange-alt', 'fa-user-slash'];
$icon_index = 0;

foreach ($display_services as $service):
    $icon = $icons[$icon_index % count($icons)]; // اختيار أيقونة بالتسلسل
    $icon_index++;
?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card service-card shadow-lg h-100 border-0">
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <div class="service-icon mb-3">
                            <i class="fas <?php echo $icon; ?> fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title fw-bold text-primary"><?php echo htmlspecialchars($service['service_name']); ?></h5>
                        <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars($service['service_description']); ?></p>
                        <a href="index.php?page=inquiry" class="btn btn-outline-primary mt-auto">المزيد</a>
                    </div>
                </div>
            </div>
            <?php
endforeach; ?>
            
        </div>
        <div class="text-center mt-5">
            <a href="index.php?page=services" class="btn btn-primary btn-lg shadow-sm">عرض جميع الخدمات</a>
        </div>
    </div>
</section>

<!-- قسم الموقع (جديد) -->
<section id="location" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-2 fw-bold text-primary">موقعنا</h2>
        <p class="text-center text-muted mb-5">يسرنا استقبالكم في مقرنا الرئيسي.</p>
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary"><i class="fas fa-map-marker-alt me-2"></i> العنوان</h5>
                        <p class="card-text">المملكة العربية السعودية، الرياض، حي العليا، شارع الأمير محمد بن عبدالعزيز (التحلية)، مبنى رقم 123.</p>
                        
                        <h5 class="card-title fw-bold text-primary mt-4"><i class="fas fa-phone me-2"></i> تواصل معنا</h5>
                        <p class="card-text">الهاتف: 966555123456+</p>
                        <p class="card-text">البريد الإلكتروني: info@example.com</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <!-- مكان لعرض الخريطة -->
                <div class="map-placeholder shadow-lg" style="height: 300px; background-color: #e9ecef; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 1.2rem;">
                    <i class="fas fa-map-marked-alt fa-2x me-2"></i>
                    مكان عرض الخريطة التفاعلية (Google Maps)
                </div>
            </div>
        </div>
    </div>
</section>

<!-- قسم الوصف (جديد) -->
<section id="about" class="py-5">
    <div class="container">
        <h2 class="text-center mb-2 fw-bold text-primary">من نحن</h2>
        <p class="text-center text-muted mb-5">قصتنا ورؤيتنا في تقديم أفضل الخدمات.</p>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <blockquote class="blockquote text-center p-4 bg-light rounded shadow-sm border-start border-primary border-5">
                    <p class="mb-0">
                        "نحن في مكتب الخدمات المتكاملة نؤمن بأن الوصول إلى الخدمات الحكومية يجب أن يكون حقاً سهلاً ومتاحاً للجميع. تأسس مكتبنا على مبدأ الشفافية والسرعة والاحترافية، لنكون الجسر الموثوق الذي يربطكم بالجهات الرسمية. هدفنا ليس مجرد إنجاز معاملة، بل بناء علاقة ثقة طويلة الأمد مع عملائنا."
                    </p>
                    <footer class="blockquote-footer mt-3">
                        فريق الإدارة في <cite title="Source Title">مكتب الخدمات المتكاملة</cite>
                    </footer>
                </blockquote>
            </div>
        </div>
    </div>
</section>

<!-- تذييل الصفحة (محدث) -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p>&copy; 2024 مكتب الخدمات المتكاملة. جميع الحقوق محفوظة.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p>تصميم وتطوير عصري لنظام إدارة المعاملات</p>
            </div>
        </div>
    </div>
</footer>
