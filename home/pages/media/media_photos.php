<?php
$page_title = "الصور - المركز الإعلامي";
$active_page = "media";
include '../../includes/header.php';
include '../../includes/navigation.php';

// Gallery images - paths relative to project root
$gallery = [
    [
        'src'   => '../../images/1icon.png',
        'label' => 'صورة 1'
    ],
    [
        'src'   => '../../images/10.jpg',
        'label' => 'صورة 2'
    ],
    [
        'src'   => '../../images/11.jpg',
        'label' => 'صورة 3'
    ],
    [
        'src'   => '../../images/12.jpg',
        'label' => 'صورة 4'
    ],
    [
        'src'   => '../../images/13.jpg',
        'label' => 'صورة 5'
    ],
    [
        'src'   => '../../images/14.jpg',
        'label' => 'صورة 6'
    ],
    [
        'src'   => '../../images/15.jpg',
        'label' => 'صورة 7'
    ],
    [
        'src'   => '../../images/16.jpg',
        'label' => 'صورة 8'
    ],
    [
        'src'   => '../../images/17.jpg',
        'label' => 'صورة 9'
    ],
    [
        'src'   => '../../images/18.jpg',
        'label' => 'صورة 10'
    ],
    [
        'src'   => '../../images/19.jpg',
        'label' => 'صورة 11'
    ],
    [
        'src'   => '../../images/1.jpg',
        'label' => 'صورة 12'
    ],
    [
        'src'   => '../../images/20.jpg',
        'label' => 'صورة 13'
    ],
    [
        'src'   => '../../images/21.jpg',
        'label' => 'صورة 14'
    ],
    [
        'src'   => '../../images/22 (1).jpg',
        'label' => 'صورة 15'
    ],
    [
        'src'   => '../../images/23.jpg',
        'label' => 'صورة 16'
    ],
    [
        'src'   => '../../images/24.jpg',
        'label' => 'صورة 17'
    ],
    [
        'src'   => '../../images/25.jpg',
        'label' => 'صورة 18'
    ],
    [
        'src'   => '../../images/26.jpg',
        'label' => 'صورة 19'
    ],
    [
        'src'   => '../../images/27.jpg',
        'label' => 'صورة 20'
    ],
    [
        'src'   => '../../images/1 (1).jpg',
        'label' => 'صورة 21'
    ],
    [
        'src'   => '../../images/2.jpg',
        'label' => 'صورة 22'
    ],
    [
        'src'   => '../../images/2 (1).jpg',
        'label' => 'صورة 23'
    ],
    [
        'src'   => '../../images/3.jpg',
        'label' => 'صورة 24'
    ],
    [
        'src'   => '../../images/4.jpg',
        'label' => 'صورة 25'
    ],
    [
        'src'   => '../../images/5 (1).jpg',
        'label' => 'صورة 26'
    ],
    [
        'src'   => '../../images/6.jpg',
        'label' => 'صورة 27'
    ],
    [
        'src'   => '../../images/7.jpg',
        'label' => 'صورة 28'
    ],
    [
        'src'   => '../../images/8.jpg',
        'label' => 'صورة 29'
    ],
    [
        'src'   => '../../images/9.jpg',
        'label' => 'صورة 30'
    ],
    [
        'src'   => '../../images/10 (2).jpg',
        'label' => 'صورة 31'
    ],
    [
        'src'   => '../../images/11 (2).jpg',
        'label' => 'صورة 32'
    ],
    [
        'src'   => '../../images/12 (2).jpg',
        'label' => 'صورة 33'
    ],
    [
        'src'   => '../../images/3 (2).jpg',
        'label' => 'صورة 34'
    ],
    [
        'src'   => '../../images/13 (2).jpg',
        'label' => 'صورة 35'
    ],
    [
        'src'   => '../../images/14 (2).jpg',
        'label' => 'صورة 36'
    ],
    [
        'src'   => '../../images/15 (3).jpg',
        'label' => 'صورة 37'
    ],
    [
        'src'   => '../../images/16 (2).jpg',
        'label' => 'صورة 38'
    ],
    [
        'src'   => '../../images/17 (1).jpg',
        'label' => 'صورة 39'
    ],
    [
        'src'   => '../../images/18 (1).jpg',
        'label' => 'صورة 40'
    ],
    [
        'src'   => '../../images/19 (1).jpg',
        'label' => 'صورة 41'
    ],
    [
        'src'   => '../../images/20 (1).jpg',
        'label' => 'صورة 42'
    ],
    [
        'src'   => '../../images/21 (1).jpg',
        'label' => 'صورة 43'
    ],
    [
        'src'   => '../../images/22 (2).jpg',
        'label' => 'صورة 44'
    ],
    [
        'src'   => '../../images/4 (1).jpg',
        'label' => 'صورة 45'
    ],
    [
        'src'   => '../../images/23 (1).jpg',
        'label' => 'صورة 46'
    ],
    [
        'src'   => '../../images/24 (1).jpg',
        'label' => 'صورة 47'
    ],
    [
        'src'   => '../../images/26 (1).jpg',
        'label' => 'صورة 48'
    ],
    [
        'src'   => '../../images/27 (1).jpg',
        'label' => 'صورة 49'
    ],
    [
        'src'   => '../../images/28.jpg',
        'label' => 'صورة 50'
    ],
    [
        'src'   => '../../images/29.jpg',
        'label' => 'صورة 51'
    ],
    [
        'src'   => '../../images/30.jpg',
        'label' => 'صورة 52'
    ],
    [
        'src'   => '../../images/31.jpg',
        'label' => 'صورة 53'
    ],
    [
        'src'   => '../../images/32.jpg',
        'label' => 'صورة 54'
    ],
    [
        'src'   => '../../images/5 (2).jpg',
        'label' => 'صورة 55'
    ],
    [
        'src'   => '../../images/33.jpg',
        'label' => 'صورة 56'
    ],
    [
        'src'   => '../../images/34.jpg',
        'label' => 'صورة 57'
    ],
    [
        'src'   => '../../images/35.jpg',
        'label' => 'صورة 58'
    ],
    [
        'src'   => '../../images/37.jpg',
        'label' => 'صورة 59'
    ],
    [
        'src'   => '../../images/38.jpg',
        'label' => 'صورة 60'
    ],
    [
        'src'   => '../../images/6 (1).jpg',
        'label' => 'صورة 61'
    ],
    [
        'src'   => '../../images/7 (1).jpg',
        'label' => 'صورة 62'
    ],
    [
        'src'   => '../../images/8 (1).jpg',
        'label' => 'صورة 63'
    ],
    [
        'src'   => '../../images/9 (1).jpg',
        'label' => 'صورة 64'
    ],
];
?>

<!-- Breadcrumbs -->
<div style="max-width: 1200px; margin: 15px auto 10px; padding: 0 15px; text-align: right; direction: rtl; font-size: 14px; font-family: 'Droid Arabic Kufi', Tahoma;">
    <a href="index.php" style="color: #00ab67; text-decoration: none;">المركز الإعلامي</a>
    <span style="color: #aaa; margin: 0 6px;">&gt;</span>
    <strong style="color: #333;">الصور</strong>
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
                <h1 style="font-size: 26px; color: #333; font-family: 'Droid Arabic Kufi', Tahoma !important; font-weight: bold; margin: 0 0 15px 0; text-align: right;">الصور</h1>

                <!-- ====== CUSTOM SLIDER ====== -->
                <style>
                    .photo-slider-wrap { position: relative; width: 100%; max-width: 680px; background: #111; overflow: hidden; }
                    .photo-slider-main { position: relative; width: 100%; height: 300px; overflow: hidden; }
                    .photo-slide { display: none; width: 100%; height: 100%; }
                    .photo-slide.active { display: block; }
                    .photo-slide img { width: 100%; height: 300px; object-fit: cover; display: block; }

                    /* Arrows */
                    .slide-arrow {
                        position: absolute; top: 50%; transform: translateY(-50%);
                        width: 42px; height: 42px; background: rgba(0,0,0,0.55);
                        color: #fff; font-size: 28px; line-height: 42px; text-align: center;
                        cursor: pointer; z-index: 10; border-radius: 4px;
                        user-select: none; transition: background 0.2s;
                    }
                    .slide-arrow:hover { background: rgba(0,0,0,0.85); }
                    .slide-arrow.prev { right: 12px; }
                    .slide-arrow.next { left: 12px; }

                    /* Thumbnail strip */
                    .thumb-strip { display: flex; background: rgba(0,0,0,0.7); padding: 6px 6px; gap: 4px; overflow-x: auto; }
                    .thumb-item { flex: 0 0 auto; width: 100px; height: 65px; cursor: pointer; opacity: 0.5; transition: opacity 0.2s; border: 2px solid transparent; box-sizing: border-box; }
                    .thumb-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
                    .thumb-item.active { opacity: 1; border-color: #00ab67; }
                    .thumb-item:hover { opacity: 0.9; }
                    .thumb-strip::-webkit-scrollbar { height: 4px; }
                    .thumb-strip::-webkit-scrollbar-thumb { background: #00ab67; border-radius: 2px; }
                </style>

                <div class="photo-slider-wrap">
                    <!-- Main Slides -->
                    <div class="photo-slider-main">
                        <?php foreach ($gallery as $i => $img): ?>
                        <div class="photo-slide <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo $i; ?>">
                            <img src="<?php echo htmlspecialchars($img['src']); ?>" alt="<?php echo htmlspecialchars($img['label']); ?>" />
                        </div>
                        <?php endforeach; ?>

                        <!-- Prev / Next arrows -->
                        <div class="slide-arrow prev" onclick="changeSlide(-1)">&#8250;</div>
                        <div class="slide-arrow next" onclick="changeSlide(1)">&#8249;</div>
                    </div>

                    <!-- Thumbnail Strip -->
                    <div class="thumb-strip" id="thumbStrip">
                        <?php foreach ($gallery as $i => $img): ?>
                        <div class="thumb-item <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo $i; ?>" onclick="goToSlide(<?php echo $i; ?>)">
                            <img src="<?php echo htmlspecialchars($img['src']); ?>" alt="<?php echo htmlspecialchars($img['label']); ?>" />
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <script>
                    var currentSlide = 0;
                    var slides      = document.querySelectorAll('.photo-slide');
                    var thumbs      = document.querySelectorAll('.thumb-item');
                    var autoTimer;

                    function goToSlide(n) {
                        slides[currentSlide].classList.remove('active');
                        thumbs[currentSlide].classList.remove('active');
                        currentSlide = (n + slides.length) % slides.length;
                        slides[currentSlide].classList.add('active');
                        thumbs[currentSlide].classList.add('active');
                        // Auto-scroll the thumbnail strip so the active thumb is in view
                        var strip = document.getElementById('thumbStrip');
                        var activeThumb = thumbs[currentSlide];
                        var thumbLeft = activeThumb.offsetLeft;
                        var thumbWidth = activeThumb.offsetWidth;
                        var stripWidth = strip.offsetWidth;
                        var scrollTo = thumbLeft - (stripWidth / 2) + (thumbWidth / 2);
                        strip.scrollTo({ left: scrollTo, behavior: 'smooth' });
                        resetTimer();
                    }

                    function changeSlide(dir) {
                        goToSlide(currentSlide + dir);
                    }

                    function resetTimer() {
                        clearInterval(autoTimer);
                        autoTimer = setInterval(function() { changeSlide(1); }, 4000);
                    }

                    // Start auto-play
                    resetTimer();
                </script>

            </td>
        </tr>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
