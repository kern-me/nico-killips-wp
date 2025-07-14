/**
 * File navigation.js.
 *
 * Handles hamburger menu toggling and accessibility features.
 */
(function() {
    'use strict';

    // Keep track of elements for focus trap
    let focusableElements = [];
    let firstFocusableElement = null;
    let lastFocusableElement = null;

    // Initialize navigation when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initMainNavigation();
        handleKeyboardFocus();
    });

    /**
     * Initialize main navigation with keyboard accessibility
     */
    function initMainNavigation() {
        const nav = document.getElementById('site-navigation');

        if (!nav) {
            return;
        }

        const button = nav.querySelector('.menu-toggle');
        const menu = document.getElementById('primary-menu-container');
        const menuItems = nav.querySelectorAll('.nav-menu a');

        // Add data-focus-trap attribute to menu container
        menu.setAttribute('data-focus-trap', 'nav-menu');

        // Return early if the navigation doesn't exist
        if (!button || !menu || !menuItems.length) {
            return;
        }

        // Hide menu initially
        menu.setAttribute('aria-hidden', 'true');
        button.setAttribute('aria-expanded', 'false');

        // Toggle menu visibility when button is clicked
        button.addEventListener('click', function() {
            const expanded = button.getAttribute('aria-expanded') === 'true';

            // Toggle menu state
            button.setAttribute('aria-expanded', !expanded);
            menu.toggleAttribute('aria-hidden');

            // Prevent body scroll when menu is open
            document.body.classList.toggle('menu-open', !expanded);

            if (!expanded) {
                // Opening the menu - set up focus trap
                setupFocusTrap(menu, button, menuItems);

                // Focus first menu item when opening
                setTimeout(() => {
                    menuItems[0].focus();
                }, 100);
            } else {
                // Closing the menu - remove focus trap
                removeFocusTrap();
            }
        });

        // Set up keyboard navigation for menu items and add animation delay
        menuItems.forEach(function(link, index) {
            // Set animation delay based on item index
            link.parentElement.style.setProperty('--item-index', index);

            link.addEventListener('keydown', function(e) {
                navigateWithKeys(e, menuItems);
            });
        });

        // Close menu with Escape key
        document.addEventListener('keyup', function(e) {
            if (e.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
                button.setAttribute('aria-expanded', 'false');
                menu.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('menu-open');
                removeFocusTrap();
                button.focus();
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (button.getAttribute('aria-expanded') === 'true' && !nav.contains(e.target)) {
                button.setAttribute('aria-expanded', 'false');
                menu.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('menu-open');
                removeFocusTrap();
            }
        });
    }

    /**
     * Handle keyboard navigation between menu items
     */
    function navigateWithKeys(e, items) {
        const key = e.key;
        const currentIndex = Array.from(items).indexOf(e.target);
        let targetIndex;

        switch (key) {
            case 'ArrowDown':
                e.preventDefault();
                targetIndex = (currentIndex + 1) % items.length;
                items[targetIndex].focus();
                break;

            case 'ArrowUp':
                e.preventDefault();
                targetIndex = (currentIndex - 1 + items.length) % items.length;
                items[targetIndex].focus();
                break;

            case 'Home':
                e.preventDefault();
                items[0].focus();
                break;

            case 'End':
                e.preventDefault();
                items[items.length - 1].focus();
                break;
        }
    }

    /**
     * Handle keyboard focus states
     * Only show focus states when using keyboard navigation
     */
    function handleKeyboardFocus() {
        const body = document.body;

        // Add class when using keyboard for navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                body.classList.add('keyboard-navigation');
            }
        });

        // Remove class when using mouse
        document.addEventListener('mousedown', function() {
            body.classList.remove('keyboard-navigation');
        });
    }

    /**
     * Set up focus trap for the menu
     * @param {HTMLElement} menuContainer - The menu container element
     * @param {HTMLElement} toggleButton - The menu toggle button
     * @param {NodeList} items - The menu items
     */
    function setupFocusTrap(menuContainer, toggleButton, items) {
        // Get all focusable elements inside the menu container
        const menuFocusables = Array.from(menuContainer.querySelectorAll(
            'a[href], button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'
        )).filter(el => !el.hasAttribute('disabled') && el.offsetParent !== null);

        // Include the toggle button in the focus trap
        focusableElements = [toggleButton, ...menuFocusables];

        firstFocusableElement = focusableElements[0];
        lastFocusableElement = focusableElements[focusableElements.length - 1];

        // Add keydown event listener to handle tabbing
        document.addEventListener('keydown', handleFocusTrap);
    }

    /**
     * Handle focus trap keyboard navigation
     * @param {KeyboardEvent} e - The keyboard event
     */
    function handleFocusTrap(e) {
        if (e.key !== 'Tab') return;

        // If shift + tab on first element, move to last focusable element
        if (e.shiftKey && document.activeElement === firstFocusableElement) {
            e.preventDefault();
            lastFocusableElement.focus();
        }

        // If tab on last element, move to first focusable element
        if (!e.shiftKey && document.activeElement === lastFocusableElement) {
            e.preventDefault();
            firstFocusableElement.focus();
        }
    }

    /**
     * Remove focus trap
     */
    function removeFocusTrap() {
        document.removeEventListener('keydown', handleFocusTrap);
        focusableElements = [];
        firstFocusableElement = null;
        lastFocusableElement = null;
    }
})();
