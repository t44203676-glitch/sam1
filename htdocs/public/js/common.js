// common.js

// Global variables and functions
const formInitializers = {};
let countriesData = [];
let globalCitiesData = []; // This will now hold data from cities.json

/**
 * Register a form-specific initialization function
 */
window.registerFormInitialization = function (formName, initFunction) {
    formInitializers[formName] = initFunction;
};

/**
 * Generic function to update arrival places select (kept for backward compatibility if needed)
 */
function updateArrivalPlacesForForm(cities, arrivalSelect) {
    if (!arrivalSelect) return;
    arrivalSelect.innerHTML = '';
    arrivalSelect.disabled = false;

    if (cities && cities.length > 0) {
        cities.forEach(city => {
            const cityName = typeof city === 'string' ? city : (city.name || city.arabic_name);
            const option = document.createElement('option');
            option.value = cityName;
            option.textContent = cityName;
            arrivalSelect.appendChild(option);
        });
    } else {
        const option = document.createElement('option');
        option.value = "مفتوح";
        option.textContent = "مفتوح";
        arrivalSelect.appendChild(option);
    }
}

/**
 * Initializes a nationality search input field and optional city search
 */
function initializeNationalitySearch(inputId, listId, arrivalId, arrivalListId = null, independentCities = true) {
    const nationalityInput = document.getElementById(inputId);
    const nationalityList = document.getElementById(listId);
    const arrivalElement = arrivalId ? document.getElementById(arrivalId) : null;
    const arrivalList = arrivalListId ? document.getElementById(arrivalListId) : null;

    if (!nationalityInput || !nationalityList) {
        console.log(`Skipping ${inputId} - required elements not found on this page`);
        return false;
    }

    let selectedCountryCities = [];
    const isArrivalSelect = arrivalElement ? arrivalElement.tagName === 'SELECT' : false;

    // --- Arabic Normalization Helper ---
    function normalizeArabic(text) {
        if (!text) return "";
        return text
            .toString()
            .trim()
            .replace(/[أإآ]/g, 'ا')
            .replace(/ة/g, 'ه')
            .replace(/ى/g, 'ي')
            .replace(/[ًٌٍَُِّْ]/g, '') // Remove Harakat
            .toUpperCase();
    }

    // --- Search Helper with Prioritization ---
    function searchData(data, query, limit = 15, currentCountryId = null) {
        const normalizedQuery = normalizeArabic(query);
        if (!normalizedQuery && !currentCountryId) return [];

        let filteredData = data;
        if (currentCountryId) {
            filteredData = data.filter(item => item.country_id == currentCountryId);
        }

        const matches = filteredData.map(item => {
            const name = typeof item === 'string' ? item : (item.name || item.arabic_name || item.english_name || "");
            const normalizedName = normalizeArabic(name);
            let score = 0;

            if (normalizedQuery) {
                if (normalizedName.startsWith(normalizedQuery)) score = 2;
                else if (normalizedName.includes(normalizedQuery)) score = 1;
            } else if (currentCountryId) {
                score = 1; // Generic score if only country filter is active
            }

            return { item, name, score };
        }).filter(m => m.score > 0);

        // Sort by score (priority to startsWith)
        // then by priority (States before Cities: priority 1 < priority 2)
        // then by length (shorter names first)
        matches.sort((a, b) => {
            if (b.score !== a.score) return b.score - a.score;
            if (a.item.priority && b.item.priority && a.item.priority !== b.item.priority) {
                return a.item.priority - b.item.priority;
            }
            return a.name.length - b.name.length;
        });

        return matches.slice(0, limit).map(m => m.item);
    }

    // --- Nationality Search Logic ---
    function renderNationalityDropdown(items) {
        nationalityList.innerHTML = '';
        items.forEach(country => {
            const item = document.createElement('div');
            item.classList.add('dropdown-item');
            item.textContent = country.arabic_name || country.name;
            item.addEventListener('click', function () {
                nationalityInput.value = country.arabic_name || country.name;
                nationalityList.style.display = 'none';

                if (country.cities) {
                    selectedCountryCities = country.cities;
                    if (arrivalElement && isArrivalSelect) updateArrivalPlacesForForm(country.cities, arrivalElement);
                }
            });
            nationalityList.appendChild(item);
        });
        nationalityList.style.display = items.length > 0 ? 'block' : 'none';
    }

    nationalityInput.addEventListener('input', function () {
        const query = this.value;
        const filtered = searchData(countriesData, query, 100);
        renderNationalityDropdown(filtered);
    });

    nationalityInput.addEventListener('focus', function () {
        const query = this.value;
        let filtered;
        if (query) {
            filtered = searchData(countriesData, query, 100);
            if (filtered.length === 0) filtered = countriesData.slice(0, 100);
        } else {
            filtered = countriesData.slice(0, 100);
        }
        renderNationalityDropdown(filtered);
    });

    // --- Arrival Place / City Search Logic ---
    if (arrivalElement && !isArrivalSelect && arrivalList) {
        function renderArrivalDropdown(items) {
            arrivalList.innerHTML = '';
            if (items.length === 0) {
                arrivalList.style.display = 'none';
                return;
            }
            items.forEach(cityValue => {
                const item = document.createElement('div');
                item.classList.add('dropdown-item');
                const name = typeof cityValue === 'string' ? cityValue : (cityValue.name || cityValue.arabic_name);
                item.textContent = name;
                item.addEventListener('click', function () {
                    arrivalElement.value = name;
                    arrivalList.style.display = 'none';
                });
                arrivalList.appendChild(item);
            });
            arrivalList.style.display = 'block';
        }

        arrivalElement.addEventListener('input', function () {
            const query = this.value;
            const currentCountry = nationalityInput.value;
            let countryId = null;

            // Hardcoded mapping for common countries if needed, or dynamic search
            if (normalizeArabic(currentCountry) === normalizeArabic("اليمن")) countryId = "243";
            else if (normalizeArabic(currentCountry) === normalizeArabic("السعودية")) countryId = "191";

            let data = (independentCities && globalCitiesData.length > 0) ? globalCitiesData : selectedCountryCities;
            const filtered = searchData(data, query, 100, countryId);
            renderArrivalDropdown(filtered);
        });

        arrivalElement.addEventListener('focus', function () {
            const query = this.value;
            const currentCountry = nationalityInput.value;
            let countryId = null;
            if (normalizeArabic(currentCountry) === normalizeArabic("اليمن")) countryId = "243";
            else if (normalizeArabic(currentCountry) === normalizeArabic("السعودية")) countryId = "191";

            let data = (independentCities && globalCitiesData.length > 0) ? globalCitiesData : selectedCountryCities;
            let filtered;
            if (query || countryId) {
                filtered = searchData(data, query, 100, countryId);
                if (filtered.length === 0 && !countryId) filtered = data.slice(0, 100);
            } else {
                filtered = data.slice(0, 100);
            }
            renderArrivalDropdown(filtered);
        });
    }

    // Hide dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (e.target !== nationalityInput) nationalityList.style.display = 'none';
        if (arrivalList && e.target !== arrivalElement) arrivalList.style.display = 'none';
    });

    console.log(`✅ Improved smart search initialized for: #${inputId} & #${arrivalId}`);
    return true;
}

window.initializeNationalitySearch = initializeNationalitySearch;

/**
 * Displays a toast-style notification.
 */
function showNotification(message, type = 'success') {
    const container = document.getElementById('notification-container');
    if (!container) return;

    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        danger: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };

    const toast = document.createElement('div');
    toast.className = `toast-notification alert alert-${type === 'danger' ? 'error' : type}`;

    const iconElement = document.createElement('i');
    iconElement.className = `${icons[type] || icons.info} me-2`;

    toast.appendChild(iconElement);
    toast.appendChild(document.createTextNode(message));
    container.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        toast.addEventListener('transitionend', () => toast.remove());
    }, 5000);
}

/**
 * Fetch data from local JSON files and merge them
 */
async function fetchCountriesDataGlobal() {
    try {
        console.log('Fetching local data from public/data/...');

        // 1. Fetch Nationalities (Countries)
        const countriesResponse = await fetch('public/data/countries.json');
        if (countriesResponse.ok) {
            countriesData = await countriesResponse.json();
            console.log('Loaded', countriesData.length, 'countries');
        }

        // 2. Fetch States and Cities
        const [statesRes, citiesRes] = await Promise.all([
            fetch('public/data/states.json'),
            fetch('public/data/cities.json')
        ]);

        let states = [];
        let cities = [];

        if (statesRes.ok) {
            const data = await statesRes.json();
            states = data.states || [];
        }
        if (citiesRes.ok) {
            const data = await citiesRes.json();
            cities = data.cities || [];
        }

        // 3. Merge them into globalCitiesData with strict order: States first, then Cities
        const stateToCountryMap = {};
        globalCitiesData = [];

        // Add states first
        states.forEach(s => {
            stateToCountryMap[s.id] = s.country_id;
            globalCitiesData.push({
                id: 's' + s.id,
                name: s.name,
                country_id: s.country_id,
                type: 'state',
                priority: 1 // Higher priority for states
            });
        });

        // Add cities second
        cities.forEach(c => {
            const countryId = stateToCountryMap[c.state_id];
            if (countryId) {
                globalCitiesData.push({
                    id: 'c' + c.id,
                    name: c.name,
                    country_id: countryId,
                    type: 'city',
                    priority: 2 // Lower priority for cities
                });
            }
        });

        console.log(`Loaded ${states.length} states and ${cities.length} cities. Total integrated places: ${globalCitiesData.length}`);

        // Initialize form logic
        const currentForm = new URLSearchParams(window.location.search).get('form');
        if (currentForm && typeof formInitializers[currentForm] === 'function') {
            formInitializers[currentForm]();
        }

    } catch (error) {
        console.error("Error loading data files:", error);
    }
}
document.addEventListener('DOMContentLoaded', () => {
    fetchCountriesDataGlobal();

    const notificationContainer = document.getElementById('notification-container');
    if (notificationContainer && notificationContainer.dataset.flashMessage) {
        showNotification(notificationContainer.dataset.flashMessage, notificationContainer.dataset.flashType || 'success');
    }

    // Global listener for duplicate ID check
    document.addEventListener('input', (e) => {
        if (e.target && e.target.name === 'national_id') {
            const val = e.target.value.replace(/[^0-9٠-٩]/g, '');
            if (val.length === 10) {
                const formElement = e.target.closest('form');
                const formTypeInput = formElement ? formElement.querySelector('input[name="formType"]') : null;
                const formType = formTypeInput ? formTypeInput.value : '';
                
                if (formType) {
                    checkDuplicateID(val, formType, e.target);
                }
            } else if (val.length < 10) {
                // Clear error and re-enable buttons if they delete digits
                const feedbackElement = document.getElementById(e.target.id + '-feedback');
                if (feedbackElement && feedbackElement.textContent.includes('موجود')) {
                    e.target.classList.remove('is-invalid');
                    e.target.style.borderColor = '';
                    feedbackElement.textContent = '';
                }
            }
        }
    });

    // Check again on blur to be sure
    document.addEventListener('blur', (e) => {
        if (e.target && e.target.name === 'national_id' && e.target.value.length === 10) {
            const formElement = e.target.closest('form');
            const formType = formElement?.querySelector('input[name="formType"]')?.value || '';
            if (formType) {
                checkDuplicateID(e.target.value, formType, e.target);
            }
        }
    }, true);

    // Prevent form submission if there are pending errors or duplicate IDs
    document.addEventListener('submit', (e) => {
        const nationalIdInput = e.target.querySelector('input[name="national_id"]');
        if (nationalIdInput && nationalIdInput.classList.contains('is-invalid')) {
            const feedback = document.getElementById(nationalIdInput.id + '-feedback');
            if (feedback && feedback.textContent.includes('موجود')) {
                e.preventDefault();
                showNotification('لا يمكن الإرسال: رقم السجل مكرر في النظام', 'error');
                nationalIdInput.focus();
            }
        }
    });
});


/**
 * Checks if a national ID is duplicate via AJAX
 */
async function checkDuplicateID(nationalId, formType, inputElement) {
    if (!nationalId || nationalId.length < 10) return;
    
    const feedbackElement = document.getElementById(inputElement.id + '-feedback');
    if (!feedbackElement) return;

    // Set pending state to block navigation while checking
    inputElement.dataset.checkingDuplicate = "true";

    // Build the absolute API URL using the <base> tag if available
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '';
    const apiPath = baseUrl + 'api/check_duplicate_id.php';

    try {
        const response = await fetch(`${apiPath}?national_id=${encodeURIComponent(nationalId)}&formType=${encodeURIComponent(formType)}`);
        const data = await response.json();

        if (data.exists) {
            inputElement.classList.add('is-invalid');
            inputElement.style.borderColor = '#dc3545';
            feedbackElement.style.color = '#dc3545';
            feedbackElement.textContent = data.message || 'رقم السجل موجود في النظام';
        } else {
            inputElement.classList.remove('is-invalid');
            inputElement.style.borderColor = '';
            feedbackElement.style.color = '';
            
            // Only clear if it was an "exists" error
            if (feedbackElement.textContent.includes('موجود')) {
                feedbackElement.textContent = '';
            }
        }
    } catch (error) {
        console.error('Error checking duplicate ID:', error);
    } finally {
        // Clear pending state
        inputElement.dataset.checkingDuplicate = "false";
    }
}

window.checkDuplicateID = checkDuplicateID;
