<?php
// views/admin.php
$current_section = $_GET['section'] ?? 'dashboard'; // تحديد القسم النشط

// تم نقل منطق جلب الإحصائيات إلى ملف `index.php` ليتم تحميله مرة واحدة فقط.
// هذا يضمن أن المتغيرات مثل `$stats` و `$requests_by_status` متاحة دائمًا هنا.
?>
<div class="container-fluid">
    <div class="row">
        <!-- الشريط الجانبي -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block admin-sidebar sidebar collapse">
            <div class="position-sticky pt-3">
                <h4 class="text-center mb-4">لوحة التحكم</h4>
                <ul class="nav nav-pills flex-column">
                    <?php if ($_SESSION['user_type'] === 'مدير'): ?>
                    <li class="nav-item">
                        <a href="?admin=1" class="nav-link <?php echo ($current_section === 'dashboard') ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt me-2"></i>الإحصائيات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?admin=1&section=pending_requests" class="nav-link <?php echo ($current_section === 'pending_requests') ? 'active' : ''; ?>">
                            <i class="fas fa-hourglass-half me-2"></i>طلبات قيد المراجعة
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a href="?admin=1&section=rejected_cases" class="nav-link <?php echo ($current_section === 'rejected_cases') ? 'active' : ''; ?>">
                            <i class="fas fa-times-circle me-2"></i>الحالات المرفوضة
                        </a>
                    </li>

                    <?php if ($_SESSION['user_type'] === 'مدير'): ?>
                    <li class="nav-item">
                        <a href="?admin=1&section=requests" class="nav-link <?php echo ($current_section === 'requests') ? 'active' : ''; ?>">
                            <i class="fas fa-list me-2"></i>إدارة الطلبات
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a href="index.php?page=add_data" class="nav-link">
                            <i class="fas fa-plus me-2"></i>إضافة بيانات
                        </a>
                    </li>
                    <?php if ($_SESSION['user_type'] === 'مدير'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_section === 'manage_users') ? 'active' : ''; ?>" href="index.php?admin=1&section=manage_users">
                            <i class="fas fa-users-cog me-2"></i>
                            إدارة الموظفين
                        </a>
                    </li>

                    <?php endif; ?>
                    <li class="nav-item">
                        <a href="?logout=1" class="nav-link text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- المحتوى الرئيسي -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-3 admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div class="d-flex align-items-center" id="main-header">
                    <button class="btn btn-primary d-md-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>
                    <button class="btn btn-outline-secondary d-none d-md-block" id="sidebarToggleBtn" title="إخفاء/إظهار القائمة" type="button">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="h2-responsive ms-2 mb-0">لوحة تحكم المدير</h2>
                </div>
                <div class="text-muted d-none d-md-block">مرحباً, <?php echo $_SESSION['user_type']; ?></div>
            </div>
            
            <?php 
            $section = $_GET['section'] ?? 'dashboard';
            if ($section === 'inquiry'):
                // Check if we have a result to show, otherwise show the form.
                if (isset($show_inquiry_result) && $show_inquiry_result === true) {
                    require __DIR__ . '/inquiry_result.php';
                } else {
                    require 'admin_inquiry_form.php';
                }
            elseif ($section === 'pending_requests'):
                // تضمين ملف عرض الطلبات قيد المراجعة
                require 'admin_pending_requests.php';
            elseif ($section === 'rejected_cases'):
                // تضمين ملف عرض الحالات المرفوضة
                require 'admin_rejected_cases.php';

            elseif ($section === 'dashboard'): 
            ?>
                <!-- الإحصائيات -->
                <div class="row mb-4 justify-content-center px-2">
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-primary"><i class="fas fa-plus-circle"></i></div>
                            <div class="stat-number"><?php echo $stats['new_requests'] ?? 0; ?></div>
                            <div class="stat-label">طلبات جديدة</div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-warning"><i class="fas fa-hourglass-half"></i></div>
                            <div class="stat-number"><?php echo $stats['pending_approval'] ?? 0; ?></div>
                            <div class="stat-label">قيد المراجعة</div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-info"><i class="fas fa-calendar-day"></i></div>
                            <div class="stat-number"><?php echo $stats['today_requests'] ?? 0; ?></div>
                            <div class="stat-label">طلبات اليوم</div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-success"><i class="fas fa-check-circle"></i></div>
                            <div class="stat-number"><?php echo $stats['total_requests'] ?? 0; ?></div>
                            <div class="stat-label">إجمالي الطلبات</div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-danger"><i class="fas fa-times-circle"></i></div>
                            <div class="stat-number"><?php echo $stats['rejected_requests'] ?? 0; ?></div>
                            <div class="stat-label">الحالات المرفوضة</div>
                        </div>
                    </div>
                </div>
                
                <!-- قسم المخططات البيانية -->
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">الطلبات الجديدة خلال آخر 7 أيام</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="weeklyRequestsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">توزيع حالات الطلبات</h5>
                            </div>
                            <div class="card-body"><canvas id="statusDistributionChart"></canvas></div>
                        </div>
                    </div>
                </div>

                <!-- تقرير حالات الطلبات (مدمج في لوحة التحكم) -->
                <?php
                if (!isset($requests_by_status)) $requests_by_status = [];
                ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">ملخص الطلبات حسب الحالة</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>الحالة</th>
                                                <th>عدد الطلبات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($requests_by_status)): ?>
                                                <?php foreach($requests_by_status as $status_report): ?>
                                                <tr>
                                                    <td data-label="الحالة"><?php echo $status_report['status']; ?></td>
                                                    <td data-label="عدد الطلبات"><?php echo $status_report['count']; ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="2" class="text-center text-muted">لا توجد بيانات</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php 
            elseif ($section === 'requests'): 
                // Define services for the filter dropdown
                $services = [
                    'marriage_permits' => 'تصاريح الزواج',
                    'family_visits' => 'الزيارات العائلية',
                    'business_visits' => 'زيارات الأعمال',
                    'tourist_visits' => 'الزيارات السياحية',
                    'recruitment' => 'الاستقدام',
                    'labor_office' => 'مكتب العمل',
                    'civil_affairs' => 'الأحوال المدنية',
                    'profession_change' => 'تغيير المهنة',
                    'followup' => 'التعقيب',
                ];
            ?>
                <!-- إدارة الطلبات -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h5 class="card-title mb-0">إدارة الطلبات</h5>
                        <div class="d-flex align-items-center flex-grow-1 flex-md-grow-0 gap-2 flex-wrap flex-md-nowrap">
                            <input type="text" id="searchInput" class="form-control" placeholder="بحث..." style="min-width: 120px; flex: 1;">
                            <select id="serviceFilter" class="form-select" style="min-width: 120px; flex: 1;">
                                <option value="">كل الخدمات</option>
                                <?php foreach ($services as $key => $name): ?>
                                    <option value="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($name); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="statusFilter" class="form-select" style="min-width: 120px; flex: 1;">
                                <option value="">جميع الحالات</option>
                                <?php foreach ($all_statuses as $status): ?>
                                    <option value="<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الصادر</th>
                                        <th>اسم مقدم الطلب</th>
                                        <th>رقم الهوية</th>
                                        <th>نوع الطلب</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody id="requestsTableBody">
                                    <!-- Populated by JS -->
                                    <tr><td colspan="7" class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php elseif ($section === 'manage_users'): ?>
                <?php require 'admin_manage_users.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* === نظام الألوان المتكيف - Premium Dark Theme === */
    :root {
        --admin-bg: #f1f5f9;
        --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        --sidebar-text: #cbd5e1;
        --card-bg: #ffffff;
        --text-color: #1e293b;
        --text-muted-color: #64748b;
        --border-color: #e2e8f0;
        --primary-accent: #6366f1;
        --primary-accent-hover: #4f46e5;
        --secondary-accent: #06b6d4;
        --sidebar-link-hover-bg: rgba(99, 102, 241, 0.1);
        --sidebar-active-bg: linear-gradient(135deg, #6366f1, #8b5cf6);
        --sidebar-active-text: #ffffff;
        --stat-shadow: 0 4px 15px rgba(0,0,0,0.06);
        --card-shadow: 0 1px 3px rgba(0,0,0,0.08);
        --card-hover-shadow: 0 8px 25px rgba(0,0,0,0.1);
        --gradient-1: linear-gradient(135deg, #6366f1, #8b5cf6);
        --gradient-2: linear-gradient(135deg, #f59e0b, #ef4444);
        --gradient-3: linear-gradient(135deg, #06b6d4, #3b82f6);
        --gradient-4: linear-gradient(135deg, #10b981, #059669);
        --gradient-5: linear-gradient(135deg, #ef4444, #dc2626);
    }

    [data-bs-theme="dark"] {
        --admin-bg: #0b1120;
        --sidebar-bg: linear-gradient(180deg, #1a1a3e 0%, #0d0d2b 100%);
        --sidebar-text: #a5b4c8;
        --card-bg: #151932;
        --text-color: #e2e8f0;
        --text-muted-color: #94a3b8;
        --border-color: rgba(99, 102, 241, 0.15);
        --primary-accent: #818cf8;
        --primary-accent-hover: #6366f1;
        --secondary-accent: #22d3ee;
        --sidebar-link-hover-bg: rgba(129, 140, 248, 0.12);
        --sidebar-active-bg: linear-gradient(135deg, #6366f1, #8b5cf6);
        --sidebar-active-text: #ffffff;
        --stat-shadow: 0 4px 15px rgba(0,0,0,0.25);
        --card-shadow: 0 2px 8px rgba(0,0,0,0.2);
        --card-hover-shadow: 0 8px 30px rgba(99, 102, 241, 0.15);
    }

    /* === الأنماط العامة === */
    .admin-content {
        background-color: var(--admin-bg);
        color: var(--text-color);
        min-height: 100vh;
        padding-top: 1.5rem;
    }

    .card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-color);
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .card:hover {
        box-shadow: var(--card-hover-shadow);
    }
    .card-header {
        background: transparent;
        border-bottom: 1px solid var(--border-color);
        border-radius: 12px 12px 0 0 !important;
        padding: 1rem 1.25rem;
    }
    .card-header h5 {
        font-weight: 700;
        font-size: 1rem;
    }

    /* === الشريط الجانبي - Gradient === */
    .admin-sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 0;
        background: var(--sidebar-bg);
        box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease-in-out;
        overflow-y: auto;
    }
    .admin-sidebar h4 {
        color: #fff;
        font-weight: 800;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }
    .admin-sidebar .nav-link {
        color: var(--sidebar-text);
        font-weight: 500;
        padding: 0.7rem 1rem;
        border-radius: 10px;
        margin: 0 0.5rem 4px;
        display: flex;
        align-items: center;
        transition: all 0.25s ease;
        font-size: 0.88rem;
    }
    .admin-sidebar .nav-link i {
        width: 22px;
        text-align: center;
        font-size: 0.95rem;
        transition: transform 0.2s ease;
    }
    .admin-sidebar .nav-link:hover {
        background-color: var(--sidebar-link-hover-bg);
        color: #fff;
        transform: translateX(-3px);
    }
    .admin-sidebar .nav-link:hover i {
        transform: scale(1.15);
    }
    .admin-sidebar .nav-link.active {
        background: var(--sidebar-active-bg);
        color: var(--sidebar-active-text);
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
    }
    .admin-sidebar .nav-link.text-danger {
        color: #f87171 !important;
    }
    .admin-sidebar .nav-link.text-danger:hover {
        background-color: rgba(239, 68, 68, 0.15);
        color: #fca5a5 !important;
    }

    /* === بطاقات الإحصائيات === */
    .stat-card {
        background-color: var(--card-bg);
        padding: 1.25rem 1rem;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        text-align: center;
        box-shadow: var(--stat-shadow);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        border-radius: 0 14px 14px 0;
    }
    .col-lg-2:nth-child(1) .stat-card::before { background: var(--gradient-1); }
    .col-lg-2:nth-child(2) .stat-card::before { background: var(--gradient-2); }
    .col-lg-2:nth-child(3) .stat-card::before { background: var(--gradient-3); }
    .col-lg-2:nth-child(4) .stat-card::before { background: var(--gradient-4); }
    .col-lg-2:nth-child(5) .stat-card::before { background: var(--gradient-5); }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--card-hover-shadow);
    }
    .stat-icon {
        font-size: 1.6rem;
        margin-bottom: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(99, 102, 241, 0.1);
    }
    .stat-icon.text-primary { background: rgba(99, 102, 241, 0.12); color: #6366f1; }
    .stat-icon.text-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
    .stat-icon.text-info { background: rgba(6, 182, 212, 0.12); color: #06b6d4; }
    .stat-icon.text-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
    .stat-icon.text-danger { background: rgba(239, 68, 68, 0.12); color: #ef4444; }

    [data-bs-theme="dark"] .stat-icon.text-primary { background: rgba(129, 140, 248, 0.15); color: #a5b4fc; }
    [data-bs-theme="dark"] .stat-icon.text-warning { background: rgba(251, 191, 36, 0.15); color: #fbbf24; }
    [data-bs-theme="dark"] .stat-icon.text-info { background: rgba(34, 211, 238, 0.15); color: #22d3ee; }
    [data-bs-theme="dark"] .stat-icon.text-success { background: rgba(52, 211, 153, 0.15); color: #34d399; }
    [data-bs-theme="dark"] .stat-icon.text-danger { background: rgba(248, 113, 113, 0.15); color: #f87171; }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text-color);
        line-height: 1.2;
    }
    .stat-label {
        font-size: 0.82rem;
        color: var(--text-muted-color);
        font-weight: 500;
        margin-top: 2px;
    }

    /* === المخططات البيانية - ارتفاع ثابت === */
    #weeklyRequestsChart { height: 300px !important; max-height: 300px; }
    #statusDistributionChart { height: 300px !important; max-height: 300px; }

    /* === الجداول === */
    .table {
        font-size: 0.88rem;
    }
    .table thead th {
        font-weight: 700;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: var(--text-muted-color);
        border-bottom-width: 2px;
    }
    .table tbody tr {
        transition: background-color 0.15s ease;
    }

    /* === أزرار === */
    .btn-primary {
        background: var(--gradient-1);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.25s ease;
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
    }

    /* === عنوان الصفحة === */
    .h2-responsive {
        font-size: 1.4rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* === حركات دخول === */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .stat-card { animation: fadeInUp 0.4s ease both; }
    .col-lg-2:nth-child(1) .stat-card { animation-delay: 0.05s; }
    .col-lg-2:nth-child(2) .stat-card { animation-delay: 0.1s; }
    .col-lg-2:nth-child(3) .stat-card { animation-delay: 0.15s; }
    .col-lg-2:nth-child(4) .stat-card { animation-delay: 0.2s; }
    .col-lg-2:nth-child(5) .stat-card { animation-delay: 0.25s; }
    .card { animation: fadeInUp 0.5s ease both; }

    /* === فورم فلاتر === */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid var(--border-color);
        font-size: 0.88rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-accent);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background: #1a1f3a;
        color: #e2e8f0;
        border-color: rgba(99, 102, 241, 0.2);
    }

    /* === Scrollbar === */
    .admin-sidebar::-webkit-scrollbar { width: 4px; }
    .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
    .admin-sidebar::-webkit-scrollbar-track { background: transparent; }

    /* === تجاوبية مع الجوال === */
    @media (max-width: 767.98px) {
        .admin-sidebar { top: 0; z-index: 1030; }
        .admin-content { margin-left: 0 !important; padding-right: 15px !important; padding-left: 15px !important; }
        .stat-number { font-size: 1.4rem; }
        .h2-responsive { font-size: 1.1rem; }
        
        /* جعل الفلاتر تظهر تحت بعضها في الجوال */
        .card-header .d-flex.align-items-center {
            flex-direction: column;
            width: 100%;
            gap: 10px !important;
        }
        .card-header .form-control, .card-header .form-select {
            width: 100% !important;
            min-width: 100% !important;
        }

        /* تحويل الجدول إلى نظام بطاقات في الجوال */
        .table-responsive table thead {
            display: none; /* إخفاء الهيدر في الجوال */
        }
        .table-responsive table, .table-responsive tbody, .table-responsive tr, .table-responsive td {
            display: block;
            width: 100%;
        }
        .table-responsive tr {
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--card-bg);
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .table-responsive td {
            text-align: right;
            padding: 8px 5px !important;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed rgba(0,0,0,0.05);
        }
        .table-responsive td::before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--primary-dark);
            margin-left: 10px;
        }
        .table-responsive td:last-child {
            border-bottom: none;
            justify-content: center;
            gap: 10px;
            padding-top: 15px !important;
        }
    }
</style>

<script src="public/js/chart.min.js"></script> <!-- مسار محلي -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // الكود الخاص بالتعديل والحذف والبحث موجود هنا ولم يتغير
    // ...

    // دعم التعديل السريع في جميع الجداول (إدارة الطلبات، الطلبات المعلقة، إلخ)
    document.addEventListener('click', function(e) {
        const editButton = e.target.closest('.btn-edit');
        const saveButton = e.target.closest('.btn-save');
        const deleteButton = e.target.closest('.btn-delete');
        
        if (editButton) {
            e.preventDefault();
            handleEdit(editButton);
        }

        if (saveButton) {
            e.preventDefault();
            handleSave(saveButton);
        }

        if (deleteButton) {
            e.preventDefault();
            handleDelete(deleteButton);
        }
    });

    function handleEdit(button) {
        const row = button.closest('tr') || button.closest('.details-section');
        const saveBtn = row.querySelector('.btn-save');
        const sourceTable = row.dataset.sourceTable || row.dataset.table;

        button.style.display = 'none';
        if (saveBtn) saveBtn.style.display = 'inline-block';
        row.classList.add('table-warning');

        // البحث عن جميع الحقول القابلة للتعديل في الصف
        const editableCells = row.querySelectorAll('[data-field]');
        editableCells.forEach(cell => {
            const field = cell.dataset.field;
            const originalValue = cell.textContent.trim();
            cell.dataset.originalValue = originalValue;

            if (field === 'status') {
                const allStatuses = <?php echo json_encode($all_statuses ?? ['بانتظار موافقة المدير', 'تمت الموافقة', 'قيد المراجعة', 'تم تعليق المعاملة', 'مرفوض', 'تمت المراجعة', 'معلق']); ?>;
                let optionsHTML = '';
                allStatuses.forEach(status => {
                    const isSelected = status === originalValue ? 'selected' : '';
                    optionsHTML += `<option value="${status}" ${isSelected}>${status}</option>`;
                });
                cell.innerHTML = `<select class="form-select form-select-sm border-warning border-2 bg-light shadow-sm fw-bold">${optionsHTML}</select>`;
            } else {
                cell.innerHTML = `<input type="text" class="form-control form-control-sm border-warning border-2 bg-light shadow-sm" style="width: 100%;" value="${originalValue}">`;
            }
        });
    }

    function handleSave(button) {
        const row = button.closest('tr') || button.closest('.details-section');
        const id = row.dataset.id || row.dataset.itemId;
        const sourceTable = row.dataset.sourceTable || row.dataset.table; 
        const editBtn = row.querySelector('.btn-edit');

        const dataToSave = {
            id: id,
            source_table: sourceTable,
        };

        const inputs = row.querySelectorAll('[data-field] input, [data-field] select');
        inputs.forEach(input => {
            const cell = input.closest('[data-field]');
            dataToSave[cell.dataset.field] = input.value;
        });

        // Determine which API to use.
        const apiUrl = (row.dataset.itemId) ? 'api/update_related_item.php' : 'api/update_request.php';

        fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dataToSave),
        })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message, data.success ? 'success' : 'danger');
            if (data.success) {
                inputs.forEach(input => {
                    const cell = input.closest('[data-field]');
                    const newValue = input.value;
                    if (input.tagName === 'SELECT') {
                        const statusClassName = `request-status status-${newValue.replace(/ /g, '-').toLowerCase()}`;
                        cell.innerHTML = `<span class="${statusClassName}">${newValue}</span>`;
                    } else {
                        cell.textContent = newValue;
                    }
                });
                row.classList.remove('table-warning');
                button.style.display = 'none';
                if (editBtn) editBtn.style.display = 'inline-block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('حدث خطأ في الشبكة.', 'danger');
        });
    }

    function handleDelete(button) {
        const row = button.closest('tr');
        const id = row.dataset.id;
        const sourceTable = row.dataset.sourceTable; // Get the source table
        const applicantName = row.querySelector('[data-field="applicant_name"]').textContent;
        
        // Use a more robust modal or a simple confirm for this example
        if (!confirm(`هل أنت متأكد من حذف طلب "${applicantName}"؟ لا يمكن التراجع عن هذا الإجراء.`)) {
            return;
        }

        const dataToDelete = {
            id: id,
            source_table: sourceTable // Add source_table to the payload
        };

        fetch('api/delete_request.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dataToDelete),
        })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message, data.success ? 'success' : 'danger');
            if (data.success) {
                row.remove();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('حدث خطأ في الشبكة.', 'danger');
        });
    }

    function showNotification(message, type = 'success') {
        // Map the type to what showToast expects ('danger' -> 'error')
        const toastType = type === 'danger' ? 'error' : type;
        if (typeof showToast === 'function') {
            showToast(message, toastType);
        } else {
            // Fallback for safety, though showToast should be globally available
            alert(message);
        }
    }

    // Dynamic Search
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const serviceFilter = document.getElementById('serviceFilter'); // The new service filter
    const tableBody = document.getElementById('requestsTableBody');
    let searchDebounceTimer;

    if (searchInput && statusFilter && serviceFilter && tableBody) {
        function performSearch() {
            const searchTerm = searchInput.value;
            const statusValue = statusFilter.value;
            const serviceValue = serviceFilter.value; // Get the selected service

            // Show a loading indicator
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

            // Update the fetch URL to include the service parameter
            fetch(`api/search_requests.php?search=${encodeURIComponent(searchTerm)}&status=${encodeURIComponent(statusValue)}&service=${encodeURIComponent(serviceValue)}`)
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html || '<tr><td colspan="7" class="text-center alert alert-info">لا توجد طلبات تطابق معايير البحث.</td></tr>';
                })
                .catch(error => {
                    console.error('Search Error:', error);
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center alert alert-danger">حدث خطأ أثناء البحث.</td></tr>';
                });
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(performSearch, 300); // Delay to avoid too many requests
        });

        statusFilter.addEventListener('change', performSearch);
        serviceFilter.addEventListener('change', performSearch); // Add event listener for the new filter

        // Perform initial search on page load for the 'requests' section
        if (new URLSearchParams(window.location.search).get('section') === 'requests') {
            performSearch();
        }
    }

    // Sidebar Toggle Logic
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebar = document.getElementById('sidebarMenu');
    const mainContent = document.querySelector('.admin-content');

    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed'); // Bootstrap's collapse class handles this on mobile
            // Optional: Change icon on toggle
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times'); // Change to a close icon
        });
    }

    // Chart.js Initialization
    const requestsByStatus = <?php echo json_encode($requests_by_status ?? []); ?>;
    const stats = <?php echo json_encode($stats ?? []); ?>;
    const weeklyStats = <?php echo json_encode($weekly_stats ?? ['labels' => [], 'data' => []]); ?>;

    // 1. Status Distribution Chart (Pie Chart)
    const statusCtx = document.getElementById('statusDistributionChart');
    if (statusCtx && requestsByStatus.length > 0) {
        const labels = requestsByStatus.map(item => item.status);
        const data = requestsByStatus.map(item => item.count);
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'عدد الطلبات',
                    data: data,
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.8)',   // Indigo
                        'rgba(245, 158, 11, 0.8)',   // Amber
                        'rgba(239, 68, 68, 0.8)',    // Red
                        'rgba(6, 182, 212, 0.8)',    // Cyan
                        'rgba(16, 185, 129, 0.8)',   // Emerald
                        'rgba(139, 92, 246, 0.8)'   // Purple
                    ],
                    borderColor: 'rgba(255,255,255,0.9)',
                    borderWidth: 2,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Tahoma', size: 12 },
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });
    } else if (statusCtx) {
        // إذا كان العنصر موجوداً ولكن لا توجد بيانات
        statusCtx.parentElement.innerHTML = "<div class='alert alert-info text-center'>لا توجد بيانات كافية لعرض توزيع الحالات.</div>";
    } 

    // 2. New Weekly Requests Chart (Line Chart)
    const weeklyCtx = document.getElementById('weeklyRequestsChart');
    if (weeklyCtx && weeklyStats.labels && weeklyStats.labels.length > 0) {
         new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: weeklyStats.labels,
                datasets: [{
                    label: 'عدد الطلبات',
                    data: weeklyStats.data,
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) return 'rgba(99, 102, 241, 0.1)';
                        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
                        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.02)');
                        return gradient;
                    },
                    borderColor: '#6366f1',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#818cf8'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { right: 10 } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.1)' },
                        ticks: {
                            color: '#94a3b8',
                            padding: 8,
                            font: { size: 12 },
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                }
            }
        });
    } else if (weeklyCtx) {
        // إذا كان العنصر موجوداً ولكن لا توجد بيانات
        weeklyCtx.parentElement.innerHTML = "<div class='alert alert-info text-center'>لا توجد بيانات كافية لعرض الطلبات الأسبوعية.</div>";
    }

    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<!-- --- تعديلات CSS إضافية لمربع البحث --- -->
<style>
.search-input {
    max-width: 200px; /* تحديد عرض أقصى لمربع البحث */
    margin: 0 10px; /* إضافة هوامش لإنشاء مساحة حول مربع البحث */
}
</style>