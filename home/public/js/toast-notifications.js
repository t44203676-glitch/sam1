/**
 * Toast Notification System
 * نظام الإشعارات المنبثقة الموحد
 */

(function() {
    'use strict';

    // Default configuration
    const defaults = {
        duration: 5000,
        position: 'top-left', // Default position for RTL (top-left is standard for RTL usually, or top-right)
        closeButton: true,
        progressBar: true
    };

    // Store active containers
    const containers = {};

    /**
     * Get or create a container for a specific position
     * @param {string} position - 'top-right', 'top-left', 'bottom-right', 'bottom-left'
     */
    function getContainer(position) {
        if (containers[position]) {
            return containers[position];
        }

        let container = document.querySelector(`.toast-container.${position}`);
        if (!container) {
            container = document.createElement('div');
            container.className = `toast-container ${position}`;
            document.body.appendChild(container);
        }
        
        containers[position] = container;
        return container;
    }

    /**
     * Show a toast notification
     * @param {string} message - The message to display
     * @param {string} type - Type of toast: 'success', 'error', 'warning', 'info'
     * @param {string} position - Position override (optional)
     */
    function showToast(message, type = 'info', position = defaults.position) {
        const container = getContainer(position);
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        // Accessibility
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        // Icon based on type
        const icons = {
            success: '<i class="fas fa-check-circle"></i>',
            error: '<i class="fas fa-times-circle"></i>',
            warning: '<i class="fas fa-exclamation-triangle"></i>',
            info: '<i class="fas fa-info-circle"></i>'
        };
        
        // Progress bar HTML
        const progressBarHtml = defaults.progressBar ? 
            `<div class="toast-progress"><div class="toast-progress-bar" style="width: 100%"></div></div>` : '';

        toast.innerHTML = `
            <div class="toast-icon">${icons[type] || icons.info}</div>
            <div class="toast-message">${message}</div>
            <button class="toast-close" aria-label="إغلاق">
                <i class="fas fa-times"></i>
            </button>
            ${progressBarHtml}
        `;
        
        // Add to container
        // For bottom positions, we append (stack upwards via CSS flex-direction: column-reverse)
        // For top positions, we append (stack downwards)
        container.appendChild(toast);
        
        // Trigger animation
        // Use requestAnimationFrame to ensure DOM update before adding class
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });
        
        // Progress Bar Logic
        let progressInterval;
        if (defaults.progressBar) {
            const progressBar = toast.querySelector('.toast-progress-bar');
            let width = 100;
            const step = 100 / (defaults.duration / 10); // Update every 10ms
            
            progressInterval = setInterval(() => {
                width -= step;
                if (width <= 0) {
                    clearInterval(progressInterval);
                    width = 0;
                }
                progressBar.style.width = `${width}%`;
            }, 10);
        }

        // Remove function
        const remove = () => {
            clearInterval(progressInterval);
            toast.classList.remove('show');
            toast.classList.add('hide');
            
            // Wait for animation to finish before removing from DOM
            toast.addEventListener('transitionend', () => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
                // Cleanup empty containers
                if (container.childNodes.length === 0) {
                    // Optional: remove container if empty, but keeping it is fine too
                }
            });
        };

        // Close button functionality
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', remove);
        
        // Auto remove after duration
        if (defaults.duration > 0) {
            setTimeout(remove, defaults.duration);
        }
        
        // Pause on hover (optional enhancement)
        toast.addEventListener('mouseenter', () => {
            if (progressInterval) clearInterval(progressInterval);
        });
        
        return toast;
    }

    // Expose to window
    window.showToast = showToast;

    // Check for PHP flash messages and display them
    document.addEventListener('DOMContentLoaded', function() {
        // Check for flash message in hidden element (legacy support or new implementation)
        const flashContainer = document.getElementById('notification-container');
        if (flashContainer) {
            const flashMessage = flashContainer.getAttribute('data-flash-message');
            const flashType = flashContainer.getAttribute('data-flash-type');
            const flashPosition = flashContainer.getAttribute('data-flash-position') || defaults.position;
            
            if (flashMessage) {
                showToast(flashMessage, flashType || 'info', flashPosition);
            }
        }
    });

})();