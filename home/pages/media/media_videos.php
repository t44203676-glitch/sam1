<?php
$page_title = "الفيديو - المركز الإعلامي";
$active_page = "media";
include '../../includes/header.php';
include '../../includes/navigation.php';

// Gallery videos - placeholder videos for demonstration
$gallery = [
    [
        'src'   => 'https://www.youtube.com/embed/dQw4w9WgXcQ', // Placeholder video
        'label' => 'فيديو 1'
    ],
    [
        'src'   => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'label' => 'فيديو 2'
    ],
    [
        'src'   => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'label' => 'فيديو 3'
    ]
];
?>

<!-- Breadcrumbs -->
<div style="max-width: 1200px; margin: 15px auto 10px; padding: 0 15px; text-align: right; direction: rtl; font-size: 14px; font-family: 'Droid Arabic Kufi', Tahoma;">
    <a href="index.php" style="color: #00ab67; text-decoration: none;">المركز الإعلامي</a>
    <span style="color: #aaa; margin: 0 6px;">&gt;</span>
    <strong style="color: #333;">الفيديو</strong>
</div>

<div style="max-width: 1200px; margin: 0 auto; direction: rtl; padding: 0 15px;">
    <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- Sidebar -->
            <td valign="top" style="width: 230px; padding-left: 20px;">
                <?php include '../../includes/sidebar_media.php'; ?>
            </td>

            <!-- Main Content -->
            <td valign="top" style="width: 100%;">
                <!-- Page Title -->
                <h1 style="font-size: 26px; color: #333; font-family: 'Droid Arabic Kufi', Tahoma !important; font-weight: bold; margin: 0 0 15px 0; text-align: right;">الفيديو</h1>

                <!-- ====== VIDEO GALLERY ====== -->
                <style>
                    .video-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                        gap: 20px;
                        margin-top: 20px;
                    }
                    .video-item {
                        background: #f9f9f9;
                        border: 1px solid #eee;
                        border-radius: 8px;
                        overflow: hidden;
                        transition: transform 0.2s;
                    }
                    .video-item:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                    }
                    .video-wrapper {
                        position: relative;
                        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
                        height: 0;
                        overflow: hidden;
                    }
                    .video-wrapper iframe {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        border: 0;
                    }
                    .video-label {
                        padding: 12px;
                        text-align: right;
                        font-family: 'Droid Arabic Kufi', Tahoma;
                        font-size: 14px;
                        color: #555;
                    }
                </style>

                <div class="video-grid">
                    <?php if (empty($gallery)): ?>
                        <p style="text-align: center; color: #777;">لا توجد مقاطع فيديو مضافة حالياً.</p>
                    <?php else: ?>
                        <?php foreach ($gallery as $video): ?>
                        <div class="video-item">
                            <div class="video-wrapper">
                                <iframe src="<?php echo htmlspecialchars($video['src']); ?>" allowfullscreen></iframe>
                            </div>
                            <div class="video-label"><?php echo htmlspecialchars($video['label']); ?></div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </td>
        </tr>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
