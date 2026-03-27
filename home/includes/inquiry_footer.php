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
            <div class="flex flex-wrap justify-center gap-x-8 gap-y-1 text-sm font-bold text-[#555555] order-2 flex-grow">
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
            <div class="flex items-center gap-3 order-2 md:order-1 justify-center mt-2">
                <span class="text-[#444444] text-sm">تحميل تطبيق أبشر</span>
                <a href="#" class="text-[#009672] hover:opacity-80 transition-opacity">
                    <!-- Apple Icon -->
                    <svg viewBox="0 0 384 512" class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 46.9 101.4 82.7 100.8 24.3-.4 36.1-18.7 67.8-18.7 35.5 0 49.9 18.5 67.8 18.5 26 0 62.6-67.9 83.3-98.3 5.3-7.7 20-33.1 23-41-35.1-10.4-55.5-30.8-55.5-56.1l.1-1.1Zm-85.4-125c19-22.7 32-54.6 27-84.9-24.3 0-54.7 17.6-70.3 39-16.8 21.6-30.8 54-26.6 84.4 27.2 2 54.2-16.1 70-38.5Z"/></svg>
                </a>
                <a href="#" class="text-[#009672] hover:opacity-80 transition-opacity">
                    <!-- Android Icon -->
                    <svg viewBox="0 0 576 512" class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M420.55,301.93a24,24,0,1,1,24-24,24,24,0,0,1-24,24m-265.1,0a24,24,0,1,1,24-24,24,24,0,0,1-24,24m273.7-144.48,47.94-83a10,10,0,1,0-17.27-10h0l-48.54,84.07a301.25,301.25,0,0,0-246.56,0L116.18,64.45a10,10,0,1,0-17.27,10h0l47.94,83C64.53,202.22,8.24,285.55,0,384H576c-8.24-98.45-64.54-181.78-146.85-226.55"/></svg>
                </a>
            </div>
        </div>

        <div class="border-t-2 border-[#009672] w-full my-2"></div>

        <!-- Bottom Bar: NIC & Social -->
        <div class="relative flex items-center h-12 w-full mt-2">
            
            <!-- Center: NIC -->
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center gap-2 text-gray-600 text-xs font-semibold whitespace-nowrap">
                 <div class="w-8 h-8 flex items-center justify-center">
                    <img src="<?php echo $assetsUrl; ?>images/icon.jpeg" alt="NIC" class="w-full h-full object-contain rounded-full">
                 </div>
                 <span>تطوير وتشغيل مركز المعلومات الوطني</span>
            </div>

            <!-- Left: Social & Complaints -->
            <div class="w-full flex justify-end items-center gap-2">
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

        </div>
      </div>
    </footer>
    
    <script>
      lucide.createIcons();
    </script>
