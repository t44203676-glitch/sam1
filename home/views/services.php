<?php
// views/services.php
?>
<main class="container py-5">
    <section id="services">
        <h2 class="text-center mb-2 fw-bold text-primary">خدماتنا المتكاملة</h2>
        <p class="text-center text-muted mb-5">نقدم مجموعة واسعة من الخدمات الحكومية والرسمية بأعلى معايير الجودة والكفاءة.</p>

        <!-- شريط أدوات البحث والتصفية -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <label for="services-show-entries" class="form-label me-2 mb-0">عرض:</label>
                <select id="services-show-entries" class="form-select form-select-sm" style="width: auto;">
                    <option value="9" selected>9</option>
                    <option value="18">18</option>
                    <option value="27">27</option>
                </select>
            </div>
            <input type="text" id="services-search-input" class="form-control form-control-sm" placeholder="ابحث عن خدمة..." style="max-width: 300px;">
        </div>
        <div class="row" id="services-list">
            <!-- البطاقات المضافة حديثاً -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <div class="service-icon">
                            <i class="fas fa-users fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title mt-3">نموذج استقدام</h5>
                        <p class="card-text text-muted flex-grow-1">خدمة لتقديم طلبات الاستقدام إلكترونياً أو الاستعلام عنها.</p>
                        <a href="index.php?page=inquiry" class="btn btn-primary mt-auto">استعلام</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <div class="service-icon">
                            <i class="fas fa-passport fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title mt-3">نموذج زيارة سياحية</h5>
                        <p class="card-text text-muted flex-grow-1">خدمة لتقديم طلبات الزيارة السياحية أو الاستعلام عنها.</p>
                        <a href="index.php?page=inquiry" class="btn btn-primary mt-auto">استعلام</a>
                    </div>
                </div>
            </div>
            <!-- نهاية البطاقات المضافة -->
            
            <!-- البطاقات المضافة حديثاً (زيارة تجارية) -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <div class="service-icon">
                            <i class="fas fa-handshake fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title mt-3">نموذج زيارة تجارية</h5>
                        <p class="card-text text-muted flex-grow-1">خدمة لتقديم طلبات الزيارة التجارية أو الاستعلام عنها.</p>
                        <a href="index.php?page=inquiry" class="btn btn-primary mt-auto">استعلام</a>
                    </div>
                </div>
            </div>

            <!-- البطاقات المضافة حديثاً (تعقيب إلغاء بلاغ هروب) -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <div class="service-icon">
                            <i class="fas fa-user-times fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title mt-3">نموذج تعقيب إلغاء بلاغ هروب</h5>
                        <p class="card-text text-muted flex-grow-1">خدمة لتعقيب وإلغاء بلاغات الهروب أو الاستعلام عنها.</p>
                        <a href="index.php?page=inquiry" class="btn btn-primary mt-auto">استعلام</a>
                    </div>
                </div>
            </div>

            <!-- البطاقات المضافة حديثاً (تغيير المهنة) -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <div class="service-icon">
                            <i class="fas fa-user-edit fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title mt-3">نموذج تغيير المهنة</h5>
                        <p class="card-text text-muted flex-grow-1">خدمة لتغيير المهنة في الوثائق الرسمية أو الاستعلام عنها.</p>
                        <a href="index.php?page=inquiry" class="btn btn-primary mt-auto">استعلام</a>
                    </div>
                </div>
            </div>

            <!-- البطاقات المضافة حديثاً (الأحوال المدنية) -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <div class="service-icon">
                            <i class="fas fa-id-card-alt fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title mt-3">نموذج الأحوال المدنية</h5>
                        <p class="card-text text-muted flex-grow-1">خدمات متعلقة بالأحوال المدنية أو الاستعلام عنها.</p>
                        <a href="index.php?page=inquiry" class="btn btn-primary mt-auto">استعلام</a>
                    </div>
                </div>
            </div>

            <!-- البطاقات المضافة حديثاً (زيارة عائلية) -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <div class="service-icon">
                            <i class="fas fa-user-friends fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title mt-3">نموذج زيارة عائلية</h5>
                        <p class="card-text text-muted flex-grow-1">خدمة لتقديم طلبات الزيارة العائلية أو الاستعلام عنها.</p>
                        <a href="index.php?page=inquiry" class="btn btn-primary mt-auto">استعلام</a>
                    </div>
                </div>
            </div>

            <!-- البطاقة المضافة حديثاً (نموذج عمالة) -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <div class="service-icon">
                            <i class="fas fa-briefcase fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title mt-3">نموذج عمالة</h5>
                        <p class="card-text text-muted flex-grow-1">خدمة لتقديم طلبات العمالة أو الاستعلام عنها.</p>
                        <a href="index.php?page=inquiry" class="btn btn-primary mt-auto">استعلام</a>
                    </div>
                </div>
            </div>

            <?php 
            // استخدام نفس البيانات التجريبية المحدثة من الصفحة الرئيسية لضمان التناسق
            $mock_services = [
                ['service_name' => 'خدمة تصاريح زواج', 'service_description' => 'خدمة للحصول على تصاريح الزواج الرسمية.'],
                ['service_name' => 'خدمة إصدار تأشيرة عمالة', 'service_description' => 'خدمة لإصدار تأشيرة العمالة والتصاريح.'],
                ['service_name' => 'خدمة إصدار تأشيرة زيارة شخصية', 'service_description' => 'خدمة لإصدار تأشيرة الزيارة الشخصية.'],
                ['service_name' => 'خدمة إصدار تأشيرة الاستقدام', 'service_description' => 'خدمة لإصدار تأشيرة الاستقدام.'],
                ['service_name' => 'خدمة تعديل المهنة', 'service_description' => 'خدمة لتعديل المهنة في الوثائق.'],
                ['service_name' => 'خدمة نقل كفالة', 'service_description' => 'خدمة لنقل الكفالة بين الكفيلين.'],
                ['service_name' => 'خدمة إلغاء بلاغ هروب', 'service_description' => 'خدمة لإلغاء بلاغ الهروب الرسمي.']
            ];
            $display_services = !empty($services) ? array_slice($services, 0, 8) : $mock_services;

            // استخدام نفس مصفوفة الأيقونات لضمان التناسق
            $icons = ['fa-heart', 'fa-id-card', 'fa-briefcase', 'fa-plane-departure', 'fa-users', 'fa-user-edit', 'fa-exchange-alt', 'fa-user-slash'];
            $icon_index = 0;

            foreach ($display_services as $service): 
                $icon = $icons[$icon_index % count($icons)];
                $icon_index++;
            ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card service-card shadow-sm h-100">
                        <div class="card-body text-center p-4 d-flex flex-column">
                            <div class="service-icon">
                                <i class="fas <?php echo $icon; ?> fa-2x text-secondary"></i>
                            </div>
                            <h5 class="card-title mt-3"><?php echo htmlspecialchars($service['service_name']); ?></h5>
                            <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars($service['service_description']); ?></p>
                            <a href="index.php?page=inquiry" class="btn btn-primary mt-auto">استعلام</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
        <!-- عناصر التحكم في الترقيم -->
        <div id="services-pagination-controls" class="d-flex justify-content-center align-items-center mt-4">
        </div>
    </section> 
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const servicesList = document.getElementById('services-list');
    const searchInput = document.getElementById('services-search-input');
    const showEntriesSelect = document.getElementById('services-show-entries');
    const paginationControls = document.getElementById('services-pagination-controls');

    if (!servicesList || !searchInput || !showEntriesSelect || !paginationControls) return;

    let currentPage = 1;
    let entriesPerPage = parseInt(showEntriesSelect.value, 10);
    const originalCards = Array.from(servicesList.children);

    function renderServices() {
        const filter = searchInput.value.toLowerCase();
        const filteredCards = originalCards.filter(card => {
            const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
            const text = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
            return title.includes(filter) || text.includes(filter);
        });

        servicesList.innerHTML = '';
        const startIndex = (currentPage - 1) * entriesPerPage;
        const endIndex = startIndex + entriesPerPage;
        const paginatedCards = filteredCards.slice(startIndex, endIndex);

        paginatedCards.forEach(card => servicesList.appendChild(card));

        renderPagination(filteredCards.length);
    }

    function renderPagination(totalCards) {
        const totalPages = Math.ceil(totalCards / entriesPerPage);
        paginationControls.innerHTML = '';

        if (totalPages <= 1) return;

        const pagination = document.createElement('ul');
        pagination.className = 'pagination';

        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', (e) => { e.preventDefault(); currentPage = i; renderServices(); });
            pagination.appendChild(pageItem);
        }
        paginationControls.appendChild(pagination);
    }

    searchInput.addEventListener('input', () => { currentPage = 1; renderServices(); });
    showEntriesSelect.addEventListener('change', () => { currentPage = 1; entriesPerPage = parseInt(showEntriesSelect.value, 10); renderServices(); });

    renderServices();
});
</script>