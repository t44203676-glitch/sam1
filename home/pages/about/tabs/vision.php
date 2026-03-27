<td valign="top" style="padding-right: 10px;">
<div class="mid_content">
    <div style="direction: rtl; text-align: right; padding: 10px 0;">
        
        <!-- Action Buttons -->
        <div class="action-buttons mb-3" style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 10px 0; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; justify-content: flex-start; margin-bottom: 25px;">
            <a href="javascript:window.print()" style="text-decoration: none; color: #00ab67; font-size: 13px;">طباعة <i class="fas fa-print"></i></a>
            <a href="#" style="text-decoration: none; color: #00ab67; font-size: 13px;">إرسال <i class="fas fa-envelope"></i></a>
            <a href="#" style="text-decoration: none; color: #00ab67; font-size: 13px;">مشاركة <i class="fas fa-share-alt"></i></a>
        </div>

        <!-- Horizontal Tabs -->
        <div class="vision-tabs" style="display: flex; gap: 2px; margin-bottom: 20px;">
            <div id="tab-vision" onclick="showVisionTab('vision')" style="background: #a5a5a5; color: #fff; padding: 8px 15px; cursor: pointer; font-weight: bold; font-size: 14px;">الرؤية</div>
            <div id="tab-message" onclick="showVisionTab('message')" style="background: #00ab67; color: #fff; padding: 8px 15px; cursor: pointer; font-weight: bold; font-size: 14px;">الرسالة</div>
            <div id="tab-goals" onclick="showVisionTab('goals')" style="background: #00ab67; color: #fff; padding: 8px 15px; cursor: pointer; font-weight: bold; font-size: 14px;">الأهداف</div>
            <div id="tab-roles" onclick="showVisionTab('roles')" style="background: #00ab67; color: #fff; padding: 8px 15px; cursor: pointer; font-weight: bold; font-size: 14px;">الأدوار والمسؤوليات</div>
            <div id="tab-contact" onclick="showVisionTab('contact')" style="background: #00ab67; color: #fff; padding: 8px 15px; cursor: pointer; font-weight: bold; font-size: 14px;">تواصل معنا</div>
            <div id="tab-links" onclick="showVisionTab('links')" style="background: #00ab67; color: #fff; padding: 8px 15px; cursor: pointer; font-weight: bold; font-size: 14px;">روابط مهمة</div>
        </div>

        <!-- Tab Contents -->
        <div id="content-vision" class="vision-content-section" style="display: block;">
            <h3 style="color: #00ab67; font-size: 18px; font-weight: bold; margin-bottom: 15px;">رؤية المكتب</h3>
            <p style="color: #4C4C4C; font-size: 14px;">استدامة الدور الفاعل لوزارة الداخلية في تحقيق رؤية السعودية 2030.</p>
        </div>

        <div id="content-message" class="vision-content-section" style="display: none;">
            <h3 style="color: #00ab67; font-size: 18px; font-weight: bold; margin-bottom: 15px;">رسالة المكتب</h3>
            <p style="color: #4C4C4C; font-size: 14px;">تمكين دور وزارة الداخلية ضمن منظومة رؤية السعودية 2030 من خلال تطبيق أفضل الممارسات في إدارة المبادرات وتحقيق التكامل الفعال مع الجهات المنفذة والداعمة، والتوظيف الأمثل لكفاءات وموارد الوزارة بما يضمن تعزيز دور الوزارة لتحقيق أهداف رؤية السعودية 2030.</p>
        </div>

        <div id="content-goals" class="vision-content-section" style="display: none;">
            <h3 style="color: #00ab67; font-size: 18px; font-weight: bold; margin-bottom: 15px;">الأهداف الاستراتيجية</h3>
            <ul style="color: #4C4C4C; font-size: 14px; padding-right: 20px; line-height: 1.8;">
                <li>تحقيق المواءمة الاستراتيجية للمبادرات المستحدثة مع أهداف برامج الرؤية.</li>
                <li>تعزيز منظومة اتخاذ القرار.</li>
                <li>تعزيز الشراكات مع الجهات المعنية في تنفيذ المبادرات المرتبطة ببرامج الرؤية.</li>
                <li>رفع مستوى أداء المبادرات والمشاريع المرتبطة ببرامج الرؤية.</li>
                <li>تنمية وبناء قدرات منسوبي المكتب.</li>
            </ul>
        </div>

        <div id="content-roles" class="vision-content-section" style="display: none;">
            <h3 style="color: #00ab67; font-size: 18px; font-weight: bold; margin-bottom: 15px;">الأدوار والمسؤوليات</h3>
            <p style="color: #4C4C4C; font-size: 14px;">قريباً...</p>
        </div>

        <div id="content-contact" class="vision-content-section" style="display: none;">
            <h3 style="color: #00ab67; font-size: 18px; font-weight: bold; margin-bottom: 15px;">تواصل معنا</h3>
            <p style="color: #4C4C4C; font-size: 14px;">قريباً...</p>
        </div>

        <div id="content-links" class="vision-content-section" style="display: none;">
            <h3 style="color: #00ab67; font-size: 18px; font-weight: bold; margin-bottom: 15px;">روابط مهمة</h3>
            <p style="color: #4C4C4C; font-size: 14px;">قريباً...</p>
        </div>

    </div>
</div>

<script>
function showVisionTab(tabId) {
    // Hide all sections
    const sections = document.querySelectorAll('.vision-content-section');
    sections.forEach(section => section.style.display = 'none');
    
    // Reset all tab backgrounds
    const tabs = document.querySelectorAll('.vision-tabs div');
    tabs.forEach(tab => tab.style.background = '#00ab67');
    
    // Show active section
    document.getElementById('content-' + tabId).style.display = 'block';
    
    // Set active tab background
    document.getElementById('tab-' + tabId).style.background = '#a5a5a5';
}
</script>
</td>
