<?php
// views/admin_inquiry_form.php
// This is the inquiry form for the admin/employee panel.
?>
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">استعلام عن معاملة</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo (strpos($message, 'alert-danger') !== false || strpos($message, 'alert-warning') !== false) ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="index.php?admin=1&section=inquiry">
            <input type="hidden" name="action" value="admin_inquiry">
            <div class="mb-3">
                <label for="idNum" class="form-label">رقم الهوية</label>
                <input id="idNum" name="idNum" type="tel" inputmode="numeric" class="form-control" placeholder="أدخل رقم الهوية" required>
            </div>
            <div class="mb-3">
                <label for="issueNum" class="form-label">رقم الصادر</label>
                <input id="issueNum" name="issueNum" type="text" class="form-control" placeholder="أدخل الرقم الصادر" required>
            </div>
            <button class="btn btn-primary" type="submit">بحث</button>
        </form>
    </div>
</div>