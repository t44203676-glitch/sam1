function createHijriPicker(targetInputId) {
  console.log('createHijriPicker called for ID:', targetInputId);
  const input = document.getElementById(targetInputId);
  if (!input) {
    console.error(`Hijri Picker: Input element with id "${targetInputId}" not found.`);
    return;
  }
  
  // Check if already initialized
  if (input.hasAttribute('data-hijri-initialized')) {
    console.log('Hijri picker already initialized for:', targetInputId);
    return;
  }
  input.setAttribute('data-hijri-initialized', 'true');

  // --- Helper to convert Arabic-Indic numerals to Western Arabic ---
  function convertArabicNumerals(str) {
      if(typeof str !== 'string') return str;
      return str.replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));
  }

  // --- 1. Create HTML Elements ---
  const wrap = document.createElement('div');
  wrap.className = 'hijri-picker-date-input';

  const calendarPanel = document.createElement('div');
  calendarPanel.className = 'hijri-picker-calendar';
  calendarPanel.setAttribute('role', 'dialog');

  const header = document.createElement('div');
  header.style.display = 'flex';
  header.style.justifyContent = 'space-between';
  header.style.alignItems = 'center';

  const prevBtn = document.createElement('button');
  prevBtn.setAttribute('type', 'button');
  prevBtn.className = 'hijri-picker-btn';
  prevBtn.textContent = '<<';
  const monthLabel = document.createElement('strong');
  const nextBtn = document.createElement('button');
  nextBtn.setAttribute('type', 'button');
  nextBtn.className = 'hijri-picker-btn';
  nextBtn.textContent = '>>';

  header.append(prevBtn, monthLabel, nextBtn);

  const weekdays = document.createElement('div');
  weekdays.style.display = 'grid';
  weekdays.style.gridTemplateColumns = 'repeat(7, 1fr)';
  weekdays.style.gap = '6px';
  weekdays.style.marginTop = '10px';
  weekdays.style.fontSize = '12px';
  weekdays.style.color = '#6b7a8a';
  ['أ', 'إ', 'ث', 'أ', 'خ', 'ج', 'س'].forEach(day => {
      const dayDiv = document.createElement('div');
      dayDiv.textContent = day;
      dayDiv.style.textAlign = 'center';
      weekdays.appendChild(dayDiv);
  });

  const daysGrid = document.createElement('div');
  daysGrid.className = 'hijri-picker-days';

  const footer = document.createElement('div');
  footer.style.display = 'flex';
  footer.style.gap = '8px';
  footer.style.justifyContent = 'flex-end';
  footer.style.marginTop = '10px';

  const todayBtn = document.createElement('button');
  todayBtn.setAttribute('type', 'button');
  todayBtn.className = 'hijri-picker-btn';
  todayBtn.textContent = 'اليوم';
  const closeBtn = document.createElement('button');
  closeBtn.setAttribute('type', 'button');
  closeBtn.className = 'hijri-picker-btn';
  closeBtn.textContent = 'إغلاق';
  
  footer.append(todayBtn, closeBtn);

  calendarPanel.append(header, weekdays, daysGrid, footer);

  input.parentNode.insertBefore(wrap, input);
  wrap.appendChild(input);
  wrap.appendChild(calendarPanel);

  // --- Logic ---
  const hijriMonths = ['محرم', 'صفر', 'ربيع الأول', 'ربيع الآخر', 'جمادى الأولى', 
                      'جمادى الآخرة', 'رجب', 'شعبان', 'رمضان', 'شوال', 'ذو القعدة', 'ذو الحجة'];

  let currentHijriYear, currentHijriMonth;
  let selectedDate = null;

  function toHijri(date) {
    let formatter = new Intl.DateTimeFormat('ar-SA-u-ca-islamic', {year: 'numeric', month: 'numeric', day: 'numeric'});
    let parts = formatter.formatToParts(date);
    let yearStr = (parts.find(p => p.type === 'year') || {}).value || '1445';
    let monthStr = (parts.find(p => p.type === 'month') || {}).value || '1';
    let dayStr = (parts.find(p => p.type === 'day') || {}).value || '1';

    let year = parseInt(convertArabicNumerals(yearStr));
    let month = parseInt(convertArabicNumerals(monthStr)) - 1;
    let day = parseInt(convertArabicNumerals(dayStr));
    
    return { year, month, day };
  }

  function formatHijri(date) {
    return `${date.year}-${String(date.month + 1).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`;
  }

  function getDaysInHijriMonth(year, month) {
      if (month === 11 && isHijriLeapYear(year)) return 30;
      if ([0, 2, 4, 6, 8, 10].includes(month)) return 30;
      return 29;
  }

  function isHijriLeapYear(year) {
      return (year * 11 + 14) % 30 < 11;
  }

  function render() {
    monthLabel.textContent = hijriMonths[currentHijriMonth] + ' ' + currentHijriYear;
    daysGrid.innerHTML = '';
    
    const daysInMonth = getDaysInHijriMonth(currentHijriYear, currentHijriMonth);
    // Approximation for first day of week
    const firstDayEpoch = new Date(1970, 0, 4); // A known Sunday
    const daysSinceEpoch = Math.floor((new Date(currentHijriYear, currentHijriMonth, 1) - firstDayEpoch) / (1000 * 60 * 60 * 24));
    const firstDayOfWeek = (daysSinceEpoch % 7 + 7) % 7;

    for (let i = 0; i < firstDayOfWeek; i++) {
        daysGrid.appendChild(document.createElement('div'));
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const cell = document.createElement('div');
      cell.className = 'hijri-picker-day';
      cell.textContent = day;
      
      if (selectedDate && selectedDate.year === currentHijriYear && selectedDate.month === currentHijriMonth && selectedDate.day === day) {
        cell.classList.add('selected');
      }
      
      cell.addEventListener('click', () => pickHijriDate(day, currentHijriMonth, currentHijriYear));
      daysGrid.appendChild(cell);
    }
  }

  function pickHijriDate(day, month, year) {
    selectedDate = { day, month, year };
    input.value = formatHijri(selectedDate);
    calendarPanel.classList.remove('show');
  }

  // --- Event Listeners ---
  input.addEventListener('focus', () => {
    console.log('Hijri picker focused:', targetInputId);
    const today = new Date();
    const hijriToday = toHijri(today);
    currentHijriYear = hijriToday.year;
    currentHijriMonth = hijriToday.month;
    render();
    calendarPanel.classList.add('show');
  });
  
  input.addEventListener('click', () => {
    console.log('Hijri picker clicked:', targetInputId);
    const today = new Date();
    const hijriToday = toHijri(today);
    currentHijriYear = hijriToday.year;
    currentHijriMonth = hijriToday.month;
    render();
    calendarPanel.classList.add('show');
  });

  document.addEventListener('click', (e) => {
    if (!wrap.contains(e.target)) {
      calendarPanel.classList.remove('show');
    }
  });

  closeBtn.addEventListener('click', () => calendarPanel.classList.remove('show'));
  prevBtn.addEventListener('click', () => { currentHijriMonth = (currentHijriMonth + 11) % 12; if (currentHijriMonth === 11) currentHijriYear--; render(); });
  nextBtn.addEventListener('click', () => { currentHijriMonth = (currentHijriMonth + 1) % 12; if (currentHijriMonth === 0) currentHijriYear++; render(); });
  todayBtn.addEventListener('click', () => pickHijriDate(toHijri(new Date()).day, toHijri(new Date()).month, toHijri(new Date()).year));
}

/** 
 * Global initializer function for all Hijri Pickers on the page.
 * This can be called from another script once the DOM is ready.
 */
function initializeAllHijriPickers() {
    console.log('initializeAllHijriPickers called');
    const pickers = document.querySelectorAll('.hijri-picker');
    console.log('Found pickers:', pickers.length);
    pickers.forEach(picker => {
        if (picker.id) {
            createHijriPicker(picker.id);
        } else {
            console.warn('Picker without ID found:', picker);
        }
    });
}

// Make functions globally available
window.createHijriPicker = createHijriPicker;
window.initializeAllHijriPickers = initializeAllHijriPickers;

console.log('hijri-picker.js loaded successfully');