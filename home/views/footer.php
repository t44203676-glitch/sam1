<?php
// views/footer.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$version = time(); // Cache busting
?>
    <!-- أزرار التواصل الجانبية -->
    <div class="contact-fab-group">
        <a href="https://wa.me/966500000000" class="contact-fab whatsapp" title="تواصل واتساب" target="_blank">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="mailto:info@office.com" class="contact-fab email" title="راسلنا بريدياً">
            <i class="fas fa-envelope"></i>
        </a>
    </div>

    
    <!-- تهيئة التقويم الهجري لجميع الحقول -->
    <script>
    // Ensure hijri pickers are initialized after page load
    window.addEventListener('load', function() {
        console.log('Window loaded - Re-checking hijri pickers');
        if (typeof createHijriPicker !== 'undefined') {
            document.querySelectorAll('.hijri-picker').forEach(function(picker) {
                if (picker.id && !picker.classList.contains('hijri-initialized')) {
                    console.log('Late initialization for:', picker.id);
                    createHijriPicker(picker.id);
                    picker.classList.add('hijri-initialized');
                }
            });
        }
    });
    </script>

    <style>
        .contact-fab-group {
            position: fixed;
            bottom: 25px;
            left: 25px;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .contact-fab {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            color: white;
            font-size: 28px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: transform 0.2s ease-in-out;
        }
        .contact-fab:hover {
            transform: scale(1.1);
        }
        .contact-fab.whatsapp { background-color: #25D366; }
        .contact-fab.email { background-color: #c71610; }
    </style>

    <!-- ================================================== -->
    <!-- ======== نظام الإشعارات المنبثقة (Custom) ======== -->
    <!-- ================================================== -->

    <!-- تضمين النوافذ المنبثقة للتأكيد -->
    <?php 
    $modals_file = __DIR__ . '/modals/confirmation-modals.php';
    if (file_exists($modals_file)) {
        include $modals_file;
    }
    ?>
    
    <!-- Container for PHP Flash Messages -->
    <?php if (isset($_SESSION['flash_message']) && !empty($_SESSION['flash_message'])): ?>
        <div id="notification-container" 
             data-flash-message="<?php echo htmlspecialchars($_SESSION['flash_message']); ?>"
             data-flash-type="<?php echo htmlspecialchars($_SESSION['flash_type'] ?? 'info'); ?>"
             data-flash-position="<?php echo htmlspecialchars($_SESSION['flash_position'] ?? 'top-left'); ?>"
             style="display: none;"></div>
        <?php
            // Clear flash message after rendering
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            unset($_SESSION['flash_position']);
        ?>
    <?php endif; ?>
</body>
</html>