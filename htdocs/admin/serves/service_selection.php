<?php if (!isset($formType) || !$formType): ?>
    <!-- صفحة اختيار الخدمة - بنظام القائمة المنسدلة -->
    <div class="row justify-content-center align-items-center" style="min-height: 50vh;">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg p-4 text-center" style="border-radius: 20px; background: linear-gradient(135deg, #ffffff 0%, #f1f3f5 100%);">
                <div class="mb-4">
                    <div class="icon-box bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 80px; height: 80px; border-radius: 50%;">
                        <i class="fas fa-layer-group fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-dark">بدء معاملة جديدة</h3>
                    <p class="text-muted">الرجاء تحديد نوع الخدمة للبدء في إدخال البيانات</p>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label fw-bold text-secondary mb-3"><i class="fas fa-mouse-pointer me-1"></i> انقر لاختيار خدمة من القائمة</iLLabel>
                    <div class="dropdown">
                        <button class="btn btn-white btn-lg w-100 border py-3 shadow-sm dropdown-toggle dropdown-custom-btn d-flex justify-content-between align-items-center" type="button" id="serviceDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 15px; background: #fff;">
                            <span><i class="fas fa-list-ul me-2 text-primary"></i> قائمة الخدمات المتاحة</span>
                        </button>
                        <ul class="dropdown-menu w-100 shadow-lg border-0 mt-2" aria-labelledby="serviceDropdown" style="border-radius: 15px; max-height: 300px; overflow-y: auto;">
                            <?php
                            if (isset($serviceConfig) && is_array($serviceConfig)) {
                                foreach ($serviceConfig as $key => $config) {
                                    echo "<li><a class='dropdown-item py-3 px-4 d-flex align-items-center border-bottom' href='?admin=1&section=add_data&form={$key}'>";
                                    echo "<i class='fas fa-chevron-left me-3 text-muted small'></i>";
                                    echo "<span class='fw-bold'>" . htmlspecialchars($config['title']) . "</span>";
                                    echo "</a></li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="pt-3 border-top">
                    <a href="index.php?admin=1" class="text-decoration-none text-muted small">
                        <i class="fas fa-arrow-right me-1"></i> العودة للقائمة الرئيسية
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
    .dropdown-custom-btn:after {
        border: none;
        content: "\f078";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-size: 0.8rem;
    }
    .dropdown-item:hover {
        background-color: var(--primary-accent);
        color: #fff !important;
    }
    .dropdown-item:hover i {
        color: #fff !important;
    }
    </style>
<?php endif; ?>