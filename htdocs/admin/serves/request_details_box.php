<?php
// views/serves/request_details_box.php

if ($request_details):
    $allowedPhotoUploadTables = [
        'civil_affairs' => 'civil_affairs_requests',
        'business_visit' => 'business_visits',
        'tourism' => 'tourism_visits'
    ];
    $showPhotoUpload = isset($allowedPhotoUploadTables[$formType]);
?>
<div class="details-section mb-4">
    <div class="row p-3">
        <!-- Applicant Information -->
        <div class="col-md-7">
            <h5 class="details-title">معلومات مقدم الطلب</h5>
            <p class="mb-2"><strong>اسم مقدم الطلب:</strong> <?php echo htmlspecialchars($request_details['applicant_name'] ?? $request_details['establishment_name'] ?? '---'); ?></p>
            <p class="mb-2"><strong>منشئ الطلب:</strong> <?php echo htmlspecialchars($request_details['creator_name'] ?? 'غير معروف'); ?></p>
            <p class="mb-2"><strong>وقت إنشاء الطلب:</strong> <?php echo function_exists('format_time_ago_arabic') && !empty($request_details['created_at']) ? format_time_ago_arabic($request_details['created_at']) : ($request_details['created_at'] ?? '---'); ?></p>
            
            <?php if ($showPhotoUpload): ?>
            <!-- قسم رفع الصورة الشخصية -->
            <div class="mt-3">
                <form id="uploadPhotoForm" enctype="multipart/form-data">
                    <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($_GET['request_id'] ?? ''); ?>">
                    <input type="hidden" name="formType" value="<?php echo htmlspecialchars($formType); ?>">
                    <input type="hidden" name="action" value="upload_profile_photo">
                    <div class="d-flex align-items-center">
                        <div id="photo-preview" class="photo-preview-circle me-3" style="background-image: url('<?php echo getProfilePhotoUrl($request_details['profile_photo_path'] ?? '', (defined('BASE_URL') ? BASE_URL : '') . 'public/images/default-avatar.png'); ?>');"></div>
                        <label for="profile_photo" class="btn btn-sm btn-outline-primary">اختر صورة...</label>
                        <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*">
                        <button type="submit" id="save-photo-btn" class="btn btn-sm btn-success me-2" style="display: none;">حفظ الصورة</button>
                    </div>
                </form>
            </div>
            <?php
    endif; ?>
        </div>

        <!-- Official Records -->
        <div class="col-md-5 text-md-start mt-4 mt-md-0">
            <h5 class="details-title">معلومات السجل والوثائق</h5>
            <p class="mb-2"><strong>رقم السجل:</strong> <?php echo htmlspecialchars(toWesternDigits(!empty($request_details['record_number']) ? $request_details['record_number'] : ($request_details['national_id'] ?? '---'))); ?></p>
            <?php if (!in_array($_SESSION['user_type'], ['موظف', 'Employee'])): ?>
            <p class="mb-2"><strong>رقم الصادر:</strong> <?php echo htmlspecialchars(toWesternDigits($request_details['export_number'] ?? '---')); ?></p>
            <p class="mb-2"><strong>الرقم التسلسلي:</strong> <?php echo htmlspecialchars(toWesternDigits($request_details['serial_number'] ?? '---')); ?></p>
            <?php endif; ?>
            <p class="mb-2"><strong>تاريخ الإصدار:</strong> <?php echo htmlspecialchars(!empty($request_details['approval_date']) ? $request_details['approval_date'] : ($request_details['issue_date'] ?? '---')); ?></p>
            <button class="btn btn-sm mt-2" style="background-color: #fff3cd; border-color: #ffeeba; color: #664d03;">
                <i class="fas fa-receipt me-2"></i>إيصالات السداد والبنوك
            </button>
        </div>
    </div>
</div>
<?php
endif; ?>
