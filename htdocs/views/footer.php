<?php
// views/footer.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$version = time(); // Cache busting
?>

    
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

    <!-- Convert ALL Eastern Arabic digits to Western digits -->
    <script>
    (function() {
        function toWestern(str) {
            return str.replace(/[\u0660-\u0669\u06F0-\u06F9]/g, function(d) {
                var code = d.charCodeAt(0);
                if (code >= 0x0660 && code <= 0x0669) return String(code - 0x0660);
                if (code >= 0x06F0 && code <= 0x06F9) return String(code - 0x06F0);
                return d;
            });
        }
        function walkNodes(node) {
            if (node.nodeType === 3) {
                var t = node.textContent;
                var n = toWestern(t);
                if (t !== n) node.textContent = n;
            } else {
                for (var i = 0; i < node.childNodes.length; i++) {
                    walkNodes(node.childNodes[i]);
                }
            }
        }
        function convertInputs() {
            var inputs = document.querySelectorAll('input, textarea');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].value) {
                    inputs[i].value = toWestern(inputs[i].value);
                }
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() { walkNodes(document.body); convertInputs(); });
        } else {
            walkNodes(document.body);
            convertInputs();
        }
    })();
    </script>
</body>
</html>