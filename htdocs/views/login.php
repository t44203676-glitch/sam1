<?php
// views/login.php
// تصميم عصري بسيط واحترافي - خفيف وسريع
?>
<style>
    /* إخفاء النافبار وإلغاء padding-top */
    <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
    .navbar { display: none !important; }
    body { padding-top: 0 !important; margin: 0 !important; }
    <?php endif; ?>

    .login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #1a1a2e;
        padding: 20px;
        font-family: Tahoma, Arial, sans-serif;
    }

    .login-box {
        width: 100%;
        max-width: 380px;
        background: #16213e;
        border-radius: 16px;
        padding: 40px 32px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        animation: slideUp 0.5s ease;
    }

    .login-box .logo-area {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-box .logo-area .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0f3460, #533483);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .login-box .logo-area .icon-circle i {
        font-size: 28px;
        color: #e94560;
    }

    .login-box .logo-area h1 {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .login-box .logo-area p {
        color: #8892a4;
        font-size: 13px;
        margin: 0;
    }

    .login-box .field {
        margin-bottom: 18px;
    }

    .login-box .field label {
        display: block;
        color: #8892a4;
        font-size: 13px;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .login-box .field input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #2a3a5c;
        border-radius: 10px;
        background: #0f3460;
        color: #fff;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }

    .login-box .field input::placeholder {
        color: #5a6a8a;
    }

    .login-box .field input:focus {
        border-color: #e94560;
    }

    .login-box .btn-enter {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #e94560, #533483);
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
        margin-top: 8px;
    }

    .login-box .btn-enter:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .login-box .btn-enter:active {
        transform: translateY(0);
    }

    .login-box .field .password-wrapper .toggle-password {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #8892a4;
        font-size: 12.5px !important; /* حجم محدد وصغير */
        transition: color 0.15s;
        z-index: 10;
    }

    .login-box .field .password-wrapper .toggle-password:hover {
        color: #e94560;
    }

    .login-box .footer-text {
        text-align: center;
        margin-top: 24px;
        color: #5a6a8a;
        font-size: 11px;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 420px) {
        .login-box {
            padding: 30px 22px;
        }
        .login-box .logo-area h1 {
            font-size: 19px;
        }
    }
</style>

<div class="login-page">
    <div class="login-box">
        <div class="logo-area">
            <div class="icon-circle">
                <i class="fas fa-building"></i>
            </div>
            <h1>مكتب الخدمات</h1>
            <p>تسجيل دخول الموظفين</p>
        </div>

        <form method="POST" action="index.php">
            <input type="hidden" name="action" value="admin_login">

            <div class="field">
                <label for="username">البريد الإلكتروني (Email)</label>
                <input type="text" id="username" name="username" placeholder="example@domain.com" required autofocus>
            </div>

            <div class="field">
                <label for="password">كلمة المرور</label>
                <div class="password-wrapper" style="position: relative;">
                    <input type="password" id="password" name="password" placeholder="أدخل كلمة المرور" required style="padding-left: 45px;">
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <button type="submit" class="btn-enter">دخول</button>
        </form>

        <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
        </script>

        <div class="footer-text">
            &copy; <?php echo date('Y'); ?> جميع الحقوق محفوظة
        </div>
    </div>
</div>