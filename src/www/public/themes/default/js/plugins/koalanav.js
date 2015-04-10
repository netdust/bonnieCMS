;(function (root, factory) {
    'use strict';

    if (typeof define === 'function' && define.amd) {
        define([], function () {
            return (root.returnExportsGlobal = factory());
        });
    } else if (typeof exports === 'object') {
        module.exports = factory();
    } else {
        factory();
    }

}(this, function () {
    'use strict';

    /**
     * KoalaNav constructor
     *
     * Accepts a navigation element (by ID),
     *  mobile button position ('left' or 'right'),
     *  and mobile button icon ('hamburger' or 'arrow')
     */
    function KoalaNav (el, options) {
        // If the first character of el is a "#", strip it out
        if (el.charAt(0) === "#") {
            var oldEl = el;
            el = el.substr(1);

            // Inform the user of the conversion
            // console.warn('You used "' + oldEl + '", a jQuery-like selector for your menu selector. This must be written without the "#" and has been converted to "' + el + '" for you.');
        }

        this.wrapper = document.getElementById(el);
        this.wrapper.className += " mobile";

        // Option defaults
        this.options = {
            btnPosition: 'left',
            mobileWidth: 500
        };

        // Loop through the declared options
        for (var i in options) {
            // Avoid inherited properties conflicting with our code
            if (options.hasOwnProperty(i)) {
                this.options[i] = options[i];
            }
        }

        // Run the init() function to handle all onload and event handlers
        this.init();
    }

    // Main initialize function to check viewport size, errors, and other events
    KoalaNav.prototype.init = function () {

        // Swap functionality between desktop and mobile sizes
        this.detectViewportSize();
        window.addEventListener('resize', this.detectViewportSize.bind(this));

        // Adds main mobile toggle button
        this.addMobileMainToggleBtn();

        // Adds mobile toggle buttons to every parent item
        this.addMobileSubToggleBtns();

        // Check if user opens a list item in mobile navigation
        this.listenForMobileSubExpand();
    };


    // Checks browser width and fires mobile/desktop events
    KoalaNav.prototype.detectViewportSize = function () {
        if (window.innerWidth <= this.options.mobileWidth) {
            // Changes the wrapper class for general mobile styling
            this.addMobileClass();
        } else {
            // Changes the wrapper class for general desktop styling
            this.addDesktopClass();
        }
    };

    // Adds a mobile class to nav element at the mobile viewport
    KoalaNav.prototype.addMobileClass = function () {
        if (this.wrapper.className.search(/mobile/) !== 0) {
            this.wrapper.className =
                this.wrapper.className.replace
                    ( /(?:^|\s)desktop(?!\S)/g , ' mobile' );
        }
    };

    // Checks if the menu is open. If not, open it; if so, close it.
    KoalaNav.prototype.checkToggleStatus = function () {

        var toggleBtn = this;
        if (toggleBtn.parentNode.className.search(/open/) !== -1) {
            // Close navigation

            // Don't change text of main toggle button
            if (toggleBtn.tagName !== 'A') {
                toggleBtn.innerHTML = '+';
            }
            toggleBtn.parentNode.className = toggleBtn.parentNode.className.replace(/open/, '');
        } else {
            // Open navigation

            // Don't change text of main toggle button
            if (toggleBtn.tagName !== 'A') {
                toggleBtn.innerHTML = '&times;';
            }
            toggleBtn.parentNode.className += ' open';
        }
    };

    KoalaNav.prototype.open = function () {

        var toggleBtn = this;

        // Don't change text of main toggle button
        if (toggleBtn.tagName !== 'A') {
            toggleBtn.innerHTML = '&times;';
        }
        toggleBtn.parentNode.className += ' open';

    };

    KoalaNav.prototype.close = function () {

        var toggleBtn = this;

        if (toggleBtn.tagName !== 'A') {
            toggleBtn.innerHTML = '+';
        }

        toggleBtn.parentNode.className = toggleBtn.parentNode.className.replace(/open/, '');

    };

    // Detects events on list items in mobile navigation and fires checkToggleStatus
    KoalaNav.prototype.listenForMobileSubExpand = function () {
        var topLevelNav,
            nav = this;

        topLevelNav = nav.wrapper.childNodes[0].nextElementSibling;
        topLevelNav.addEventListener("click", function (e) {
            if (e.target.nodeName == "SPAN") {
                nav.checkToggleStatus.call(e.target);
            }
        });
    };

    // Add main toggle button to mobile menu to open and close first-level items
    KoalaNav.prototype.addMobileMainToggleBtn = function () {
        var toggleMobileBtn;

        // Add button into DOM
        this.wrapper.insertAdjacentHTML('afterbegin', '<a class="button toggle"></a>');

        // Assign toggle button to reusable variable if it's available
        if (this.wrapper.childNodes[0]) {
            toggleMobileBtn = this.wrapper.childNodes[0];

            // Add a click handler to the toggle button to open the menu if active
            toggleMobileBtn.addEventListener('click', this.checkToggleStatus);

            // Position the button using CSS text-align
            if (this.options.btnPosition === 'right') {
                toggleMobileBtn.className += ' btn-align-right';
            }
        } else {
            // If the button could not be found, throw an error
            throw new Error('Could not locate the main toggle button for mobile. Check your HTML to ensure it matches the README example.');
        }
    };

    // Add toggle buttons to list items that have child menus
    KoalaNav.prototype.addMobileSubToggleBtns = function () {
        var listItems = this.wrapper.getElementsByTagName('li');

        // Find all li's that have ul's inside
        for (var i = 0; i < listItems.length; i++) {
            if (listItems[i].getElementsByTagName('ul')[0]) {

                // Add a class to each li for CSS styling
                listItems[i].className = 'has-child-menu';

                // Insert the button as a span within each li
                // listItems[i].insertAdjacentHTML('afterbegin', '<span>+</span>');
            }
        }
    };

    // Adds a desktop class to nav element at the desktop viewport
    KoalaNav.prototype.addDesktopClass = function () {
        if (this.wrapper.className.search(/desktop/) !== 0) {
            this.wrapper.className =
                this.wrapper.className.replace
                    ( /(?:^|\s)mobile(?!\S)/g , ' desktop' );
        }
    };

    // Pass the entire constructor to the window object
    return KoalaNav;

// Goodbye
}));
