
/**
 * Modern Navigation Logic
 * Senior Frontend Approach: Modular, Performant, and Responsive
 */

const modernNav = {
    init() {
        this.cacheDOM();
        this.bindEvents();
    },

    cacheDOM() {
        this.body = document.body;
        this.header = document.getElementById('mainHeader');
        this.navLinks = document.getElementById('navLinks');
        this.backdrop = document.querySelector('.mobile-backdrop');
    },

    bindEvents() {
        // Sticky Header on Scroll
        window.addEventListener('scroll', () => this.handleScroll());

        // Handle accessibility for dropdowns
        const dropItems = document.querySelectorAll('.has-dropdown');
        dropItems.forEach(item => {
            item.addEventListener('mouseenter', () => this.onItemEnter(item));
        });
    },

    handleScroll() {
        if (window.scrollY > 50) {
            this.header.classList.add('is-sticky');
        } else {
            this.header.classList.remove('is-sticky');
        }
    },

    onItemEnter(item) {
        // Any dynamic logic when hovering menu items
    }
};

// Mobile Toggle Function (Global as called from HTML onclick)
function toggleMobileMenu() {
    const nav = document.getElementById('navLinks');
    const body = document.body;

    nav.classList.toggle('active');
    body.classList.toggle('mobile-menu-active');
}

// Split Menu Category Switcher
function modernShowServices(catId, element) {
    // Parent container lookup (Senior approach: scoping)
    const container = element.closest('.split-nav');
    if (!container) return;

    // Remove active class from all cats in THIS container
    container.querySelectorAll('.split-cat').forEach(cat => cat.classList.remove('active'));
    element.classList.add('active');

    // Hide all lists in THIS container
    container.querySelectorAll('.split-list').forEach(list => {
        list.style.display = 'none';
        list.classList.remove('animate-in');
    });

    // Show target
    const target = container.querySelector('#modern_' + catId);
    if (target) {
        target.style.display = 'block';
        // Potential for adding micro-animation classes here
    }
}

// Initialize on Load
document.addEventListener('DOMContentLoaded', () => modernNav.init());
