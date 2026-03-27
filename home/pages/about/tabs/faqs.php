<?php
$faqs = [
    [
        'q' => 'ماهو موقع وزارة الداخلية الإلكتروني؟', 
        'a' => 'يعتبر موقع وزارة الداخلية بوابة إعلامية للتواصل والتفاعل مع المستفيدين من خلال تقديم معلومات عن دور الوزارة وقطاعاتها وجهودها. كما يساهم في تيسير وصولهم لخدمات الوزارة.'
    ],
    [
        'q' => 'كيف أستطيع الوصول لبوابة خدمات وزارة الداخلية الإلكترونية (أبشر)؟', 
        'a' => 'من خلال الرابط التالي: <a href="https://www.absher.sa" target="_blank" style="color:#00ab67; text-decoration:none;">www.absher.sa</a>'
    ],
    [
        'q' => 'كيف أستطيع الوصول لموقع وزارة الداخلية باللغة الإنجليزية؟', 
        'a' => 'من خلال الرابط التالي: <a href="#" style="color:#00ab67; text-decoration:none;">اضغط هنا</a>'
    ],
    [
        'q' => 'كيف أعرف آخر أخبار الوزارة؟', 
        'a' => 'من صفحة المركز الإعلامي (صفحة الأخبار حالياً)، أو من خلال حسابات الوزارة في وسائل التواصل الاجتماعي.'
    ],
    [
        'q' => 'كيف أستطيع الوصول لخدمات المرور؟', 
        'a' => 'من خلال الدخول على بوابة خدمات وزارة الداخلية الإلكترونية (أبشر) <a href="https://www.absher.sa" target="_blank" style="color:#00ab67; text-decoration:none;">www.absher.sa</a>'
    ],
    [
        'q' => 'كيف أستطيع الوصول لخدمات الأحوال المدنية؟', 
        'a' => 'من خلال الدخول على بوابة خدمات وزارة الداخلية الإلكترونية (أبشر) <a href="https://www.absher.sa" target="_blank" style="color:#00ab67; text-decoration:none;">www.absher.sa</a>'
    ],
    [
        'q' => 'كيف يمكنني الحصول على وسائل التواصل الخاصة بقطاعات الوزارة؟', 
        'a' => 'من خلال الدخول على صفحة القطاع.'
    ],
    [
        'q' => 'كيف يمكنني الحصول على وسائل التواصل الخاصة بإمارات المناطق؟', 
        'a' => 'من خلال الدخول على صفحة الإمارة.'
    ],
    [
        'q' => 'لم أجد سؤالي', 
        'a' => 'يمكنك التواصل معنا من خلال (<a href="contact.php" style="color:#00ab67; text-decoration:none;">اتصل بنا</a>).'
    ],
];
?>
<td valign="top" style="padding-right: 10px;">
<div class="mid_content">
    <div style="direction: rtl; text-align: right; padding: 10px 0;">
        <h2 style="color: #4C4C4C; font-size: 24px; font-weight: bold; margin-bottom: 20px;">الأسئلة الشائعة</h2>
        
        <!-- Action Buttons -->
        <div class="action-buttons mb-3" style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 10px 0; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; justify-content: flex-start; margin-bottom: 25px;">
            <a href="javascript:window.print()">طباعة <i class="fas fa-print"></i></a>
            <a href="#">إرسال <i class="fas fa-envelope"></i></a>
            <div class="share-container">
                <a href="#" class="share-btn">مشاركة <i class="fas fa-share-alt"></i> <i class="fas fa-chevron-down" style="font-size: 10px;"></i></a>
                <div class="share-dropdown">
                    <a href="https://www.facebook.com/sharer/sharer.php" target="_blank">Facebook <i class="fab fa-facebook-square"></i></a>
                    <a href="https://plus.google.com/share" target="_blank">Google <i class="fab fa-google-plus-square"></i></a>
                </div>
            </div>
        </div>

        <div class="faq-container" style="margin-top: 25px;">
            <?php foreach ($faqs as $i => $faq): ?>
            <div class="faq-item" style="background: #f8f9fa; margin-bottom: 12px; border: 1px solid #f0f0f0; border-radius: 4px; overflow: hidden;">
                <div class="faq-question" id="faq-q-<?php echo $i; ?>" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 15px; padding: 15px 20px; transition: background 0.2s;" onclick="toggleFaq(<?php echo $i; ?>)">
                    <span style="font-weight: bold; color: #4C4C4C; font-size: 15px; flex: 1;"><?php echo htmlspecialchars($faq['q']); ?></span>
                    <i class="fas fa-plus" id="faq-icon-<?php echo $i; ?>" style="color: #00ab67; font-size: 14px; transition: transform 0.3s;"></i>
                </div>
                <div class="faq-answer" id="faq-a-<?php echo $i; ?>" style="display: none; padding: 0 20px 20px; color: #666; font-size: 14px; line-height: 1.8; border-top: 1px solid #f0f0f0; padding-top: 15px; background: #fff;">
                    <?php echo $faq['a']; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</td>

<script>
function toggleFaq(index) {
    const answer = document.getElementById('faq-a-' + index);
    const icon = document.getElementById('faq-icon-' + index);
    
    if (answer.style.display === 'none' || answer.style.display === '') {
        answer.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>
