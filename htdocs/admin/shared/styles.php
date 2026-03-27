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
    
    @media (max-width: 576px) {
        .stat-card {
            padding: 1rem 0.5rem;
            margin-bottom: 0.5rem;
        }
        .stat-number { font-size: 1.5rem; }
        .stat-icon { width: 40px; height: 40px; font-size: 1.25rem; }
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
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(99, 102, 241, 0.3);
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
    }
    
    .btn-flex {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        white-space: nowrap !important;
    }
    
    .btn-flex i, .btn i {
        font-size: 18px !important;
        transition: transform 0.2s ease;
        line-height: 1 !important;
        display: inline-block !important;
    }
    
    .btn-flex:hover i.fa-chevron-left { transform: translateX(-3px); }
    .btn-flex:hover i.fa-chevron-right { transform: translateX(3px); }
    .btn-flex:hover i.fa-home { transform: translateY(-2px); }
    .btn-flex:hover i.fa-save { transform: scale(1.1); }
    
    /* Icons in headers should be controlled */
    .form-container h1 i, .form-container h2 i, .details-section h5 i {
        font-size: 24px !important;
        vertical-align: middle;
        margin-left: 10px;
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
        .table-responsive-stack table thead,
        .table-responsive table.stack-on-mobile thead {
            display: none; /* إخفاء الهيدر في الجوال */
        }
        .table-responsive-stack table, .table-responsive-stack tbody, .table-responsive-stack tr, .table-responsive-stack td,
        .table-responsive table.stack-on-mobile, .table-responsive table.stack-on-mobile tbody, .table-responsive table.stack-on-mobile tr, .table-responsive table.stack-on-mobile td {
            display: block;
            width: 100%;
        }
        .table-responsive-stack tr,
        .table-responsive table.stack-on-mobile tr {
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--card-bg);
            padding: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        }
        .table-responsive-stack td,
        .table-responsive table.stack-on-mobile td {
            text-align: right;
            padding: 10px 5px !important;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed rgba(0,0,0,0.05);
            font-size: 0.85rem;
        }
        .table-responsive-stack td::before,
        .table-responsive table.stack-on-mobile td::before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--primary-accent);
            margin-left: 10px;
            font-size: 0.8rem;
            opacity: 0.8;
        }
        .table-responsive-stack td:last-child,
        .table-responsive table.stack-on-mobile td:last-child {
            border-bottom: none;
            justify-content: center;
            gap: 10px;
            padding-top: 15px !important;
            background: rgba(99, 102, 241, 0.03);
            border-radius: 0 0 10px 10px;
        }
    }
</style>
