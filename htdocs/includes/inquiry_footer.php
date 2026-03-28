<?php
// includes/inquiry_footer.php
?>
    <!-- Footer Component -->
    <footer class="mt-6 text-[#444444]">
      <div class="container mx-auto px-4 max-w-6xl bg-[#fcfcfc] pb-2 border-t border-gray-200">
        
        <!-- Top Footer Content -->
        <div class="flex flex-col md:flex-row items-center mb-2 gap-2 relative">
            
            <!-- Right: Logo Area -->
            <div class="flex items-center gap-3 order-1">
               <div class="w-77 h-23 flex items-center justify-center">
                  <img src="<?php echo $assetsUrl; ?>images/ministry_of_interior.jpg" alt="Ministry of Interior" class="w-full h-full object-contain mix-blend-multiply">
               </div>
            </div>

            <!-- Center: Links -->
            <div class="flex flex-wrap justify-center gap-x-8 gap-y-1 text-sm font-bold text-[#555555] order-2 flex-grow no-print">
                <a href="#" class="hover:text-[#009672] transition-colors">الأسئلة الشائعة</a>
                <a href="#" class="hover:text-[#009672] transition-colors">الأخبار</a>
                <a href="#" class="hover:text-[#009672] transition-colors">خريطة الموقع</a>
                <a href="#" class="hover:text-[#009672] transition-colors">شروط الإستخدام</a>
                <a href="#" class="hover:text-[#009672] transition-colors">سياسة الخصوصية</a>
            </div>
        </div>

        <!-- Middle: Disclaimer -->
        <div class="text-center text-[15px] text-[#777777] mb-2 px-4 leading-relaxed font-medium">
            الوصلات الخارجية الموجودة في البوابة هي لأغراض مرجعية، وزارة الداخلية ليست مسؤولة عن محتويات المواقع الخارجية.
            <br />
            جميع الحقوق محفوظة لوزارة الداخلية، المملكة العربية السعودية © 1445هـ - 2025م
            <div class="flex items-center gap-3 order-2 md:order-1 justify-start mt-2 no-print">
                <span class="text-[#444444] text-sm">تحميل تطبيق أبشر</span>
                <a href="#" class="hover:opacity-80 transition-opacity">
                    <img src="<?php echo $assetsUrl; ?>images/ios.png" alt="App Store - أبشر" class="h-9 object-contain" loading="lazy">
                </a>
                <a href="#" class="hover:opacity-80 transition-opacity">
                    <img src="<?php echo $assetsUrl; ?>images/android.png" alt="Google Play - أبشر" class="h-9 object-contain" loading="lazy">
                </a>
            </div>
        </div>

        <div class="border-t-2 border-[#009672] w-full my-2"></div>

        <!-- Bottom Bar: NIC & Social -->
        <div class="relative flex items-center h-12 w-full mt-2">
            
            <!-- Center: NIC -->
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center gap-2 text-gray-600 text-xs font-semibold whitespace-nowrap">
                 <div class="w-8 h-8 flex items-center justify-center">
                    <img src="<?php echo $assetsUrl; ?>images/icon.jpeg" alt="NIC" class="w-full h-full object-contain rounded-full" loading="lazy">
                 </div>
                  <span>تطوير وتشغيل مركز المعلومات الوطني</span>
             </div>

            <!-- Left: Social & Complaints -->
            <div class="w-full flex justify-end items-center gap-2 no-print">
                  <div class="flex gap-2">
                    <a href="#" class="w-7 h-7 bg-[#ff0000] text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current text-white"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                     </a>
                    <a href="#" class="w-7 h-7 bg-[#3b5998] text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current text-white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                     </a>
                    <a href="#" class="w-7 h-7 bg-black text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current text-white"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932L18.901 1.153zM17.61 20.644h2.039L6.486 3.24H4.298l13.312 17.403z"/></svg>
                     </a>
                  </div>
            </div>
        </div>

        <!-- Print Info Footer (Only visible in Print) -->
        <div class="hidden print:flex flex-row justify-between items-center mt-4 border-t border-gray-100 pt-2 text-[10px] text-gray-400 font-ge-light">
            <div>نظام الخدمات الإلكترونية - وزارة الداخلية</div>
            <div>تاريخ الطباعة: <?php echo date('Y-m-d H:i:s'); ?></div>
        </div>

        </div>
      </div>
    </footer>
    
    <script>
      lucide.createIcons();
    </script>
