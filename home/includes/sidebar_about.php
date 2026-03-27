<div id="navleft_sections" class="navleft_sections" style="width: 100%; margin-bottom: 25px;">
    <!-- Breadcrumb-style title -->
    <div style="width: 100%; border-top: 5px solid #999; border-bottom: 1px solid #ddd; background: #fff;">
        <div style="display: flex; flex-direction: column; width: 100%; direction: rtl;">
            <?php
            $menu_items = [
                ['الرئيسية', 'pages/about/about2.php?tab=index', 'index.php'],
                ['عن الوزارة', 'pages/about/about2.php?tab=about_info', 'about_info.php'],
                ['الأخبار', 'pages/about/about2.php?tab=news', 'news.php'],
                ['بيانات المتحدث الأمني', 'pages/about/about2.php?tab=statements', 'statements.php'],
                ['البلاغات الأمنية', 'pages/about/about2.php?tab=security_reports', 'security_reports.php'],
                ['النماذج الإلكترونية', 'pages/about/about2.php?tab=forms', 'forms.php'],
                ['مناقصات و إعلانات', 'pages/about/about2.php?tab=tenders', 'tenders.php'],
                ['أنظمة وتعليمات', 'pages/about/about2.php?tab=regulations', 'regulations.php'],
                ['مكتب تحقيق الرؤية', 'pages/about/about2.php?tab=vision', 'vision.php'],
                ['برنامج تطوير الوزارة', 'pages/about/about2.php?tab=transformation', 'transformation.php'],
                ['الأسئلة الشائعة', 'pages/about/about2.php?tab=faqs', 'faqs.php'],
                ['اتصل بنا', 'pages/about/about2.php?tab=contact', 'contact.php']
            ];

            // Improved active tab detection
            $current_script = basename($_SERVER['PHP_SELF']);
            $current_tab_param = isset($_GET['tab']) ? $_GET['tab'] : '';
            
            foreach ($menu_items as $item):
                $title = $item[0];
                $link = $item[1];
                $standalone_file = $item[2];
                
                $target_tab = '';
                if (strpos($link, 'tab=') !== false) {
                    $target_tab = explode('tab=', $link)[1];
                }
                
                // Highlight if on centralization hub with matching tab OR if on the standalone file
                $is_active = false;
                if ($current_script == 'about2.php') {
                    $is_active = ($current_tab_param == $target_tab || ($current_tab_param == '' && $target_tab == 'index'));
                } else {
                    $is_active = ($current_script == $standalone_file);
                }
                
                $bg = $is_active ? '#00ab67' : 'transparent';
                $color = $is_active ? '#fff' : '#4C4C4C';
                $full_link = BASE_URL . $link;
            ?>
                <div style="width: 100%; border-bottom: 1px solid #eee;">
                    <a href="<?php echo $full_link; ?>" style="display: block; padding: 10px 15px; text-decoration: none; color: <?php echo $color; ?>; background: <?php echo $bg; ?>; font-weight: bold; font-size: 15px; text-align: right; transition: all 0.2s;">
                        <?php echo $title; ?>
                    </a>
                </div>
                <?php if ($title == 'الأخبار' && $is_active): ?>
                    <div style="width: 100%; border-bottom: 1px solid #eee; background: #f9f9f9; border-right: 4px solid #00ab67;">
                        <a href="<?php echo BASE_URL; ?>pages/about/about2.php?tab=news_archive" style="display: block; padding: 8px 30px 8px 15px; text-decoration: none; color: #4C4C4C; font-weight: bold; font-size: 14px; text-align: right;">
                            أرشيف الأخبار
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="left_content" style="margin-top: 15px; text-align: center;">
    <a href="<?php echo BASE_URL; ?>pages/about/webmail.php" style="text-decoration:none">
        <img src="<?php echo BASE_URL; ?>images/145-e-mail-Ar_ver3.jpg" alt="البريد الإلكتروني" style="max-width: 100%; border: 1px solid #ddd;">
    </a>
</div>
