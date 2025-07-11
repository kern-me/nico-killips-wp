/**
 * File navigation.js.
 * 
 * Handles hamburger menu toggling and accessibility features.
 */
(function() {
    'use strict';

    // Initialize navigation when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('INIT!');
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

            // Focus first menu item when opening
            if (!expanded) {
                setTimeout(() => {
                    menuItems[0].focus();
                }, 100);
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
                menu.setAttribute('hidden', '');
                document.body.classList.remove('menu-open');
                button.focus();
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (button.getAttribute('aria-expanded') === 'true' && !nav.contains(e.target)) {
                button.setAttribute('aria-expanded', 'false');
                menu.setAttribute('hidden', '');
                document.body.classList.remove('menu-open');
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
})();

