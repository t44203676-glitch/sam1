<?php

include_once __DIR__ . '/config.php';
$footer_data = include __DIR__ . '/data_footer.php';

?>
<div class="footer row">
    <div class="row">
        <div class="foot-logo pull-left">
            <img src="<?php echo BASE_URL; ?>images/moi_logo_rtl.png" alt="MOI Logo" style="height: 55px;">
        </div>
        <ul class="foot-links pull-left">
            <?php foreach ($footer_data['links'] as $link):
	$full_link = (strpos($link['link'], 'http') === 0) ? $link['link'] : BASE_URL . $link['link'];
?>
                <li><a href="<?php echo $full_link; ?>"><?php echo $link['title']; ?></a></li>
            <?php
endforeach; ?>
        </ul>

        <p class="foot-note pull-left">الوصلات الخارجية الموجودة في البوابة هي لأغراض مرجعية، وزارة الداخلية
            ليست مسؤولة عن محتويات المواقع الخارجية.
            جميع الحقوق محفوظة لوزارة الداخلية، المملكة العربية السعودية © <span id="footercurrentyearhijri">1447</span>هـ - <span id="footercurrentyear">2026</span>م</p>
        
        <p class="foot-note nic-footer pull-left"><span>تحميل تطبيق أبشر</span>
            <?php foreach ($footer_data['apps'] as $app): ?>
                <a href="<?php echo $app['link']; ?>" target="_blank"><img src="<?php echo BASE_URL . $app['img']; ?>" class="<?php echo $app['class'] ?? ''; ?>"></a>
            <?php
endforeach; ?>
        </p>

        <div class="footer-sendfeedback">
            <a href="#" class="feedback pull-right">للشكاوى والإقتراحات</a>
            <div class="social-block pull-right">
                <ul>
                    <?php foreach ($footer_data['social'] as $social): ?>
                        <li class="pull-left"><a target="_blank" href="<?php echo $social['link']; ?>"><i class="homesprite <?php echo $social['icon']; ?>"></i></a></li>
                    <?php
endforeach; ?>
                </ul>
            </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    <div class="footer-nic" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px;">
				<p class="foot-note nic-footer nic-footer-right"><img src="<?php echo BASE_URL; ?>images/nic_footer_logo.png">تطوير وتشغيل مركز
					المعلومات الوطني</p>
				<p class="foot-note nic-footer nic-footer-left">للاستفسار عن الخدمات الالكترونية "ابشر" اتصل على الرقم
					<em>920020405</em>
				</p>
			</div>
			<script type="text/javascript">
				$(document).ready(function () {
					$("#footercurrentyear").html(copyrightYear.gregorian());
					$("#footercurrentyearhijri").html(copyrightYear.hijri());
				});
			</script>
    <div class="clearfix"></div>
</div>

		<script type="text/javascript" src="<?php echo BASE_URL; ?>js/newsslider.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>js/custom-expand.js"></script>

	</div>

	<div id="dialogoverlay"></div>
	<div id="jsconfirm">
		<table>
			<tbody>
				<tr>
					<td id="jsconfirmtitle"></td>
				</tr>
				<tr>
					<td id="jsconfirmcontent"></td>
				</tr>
				<tr>
					<td id="jsconfirmbuttons"><input id="jsconfirmleft" type="button" onclick="" value=""
							onfocus="if(this.blur)this.blur()">&nbsp;&nbsp;<input id="jsconfirmright" type="button"
							value="" onclick="rightJsConfirm()" onfocus="if(this.blur)this.blur()"></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="dialogbox">
		<div>
			<div id="dialogboxhead" style="display:none;"></div>
			<div id="dialogboxheadar"></div>
			<div id="dialogboxbody"></div>
			<div style="display:none;" id="dialogboxfoot"></div>
			<div id="dialogboxfootar"></div>
		</div>
	</div>
</body>

</html>
