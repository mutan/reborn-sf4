require('../css/admin.scss');

require('./modernizr.custom');
const Storages = require('js-storage/js.storage');
const $ = require('jquery');
require('popper.js/dist/popper');
require('bootstrap/dist/js/bootstrap');
require('datatables.net/js/jquery.dataTables');
require('datatables.net-bs4/js/dataTables.bootstrap4');










// GLOBAL CONSTANTS
// -----------------------------------

(function(window, document, $, undefined){

    window.APP_COLORS = {
        'primary':                '#5d9cec',
        'success':                '#27c24c',
        'info':                   '#23b7e5',
        'warning':                '#ff902b',
        'danger':                 '#f05050',
        'inverse':                '#131e26',
        'green':                  '#37bc9b',
        'pink':                   '#f532e5',
        'purple':                 '#7266ba',
        'dark':                   '#3a3f51',
        'yellow':                 '#fad732',
        'gray-darker':            '#232735',
        'gray-dark':              '#3a3f51',
        'gray':                   '#dde6e9',
        'gray-light':             '#e4eaec',
        'gray-lighter':           '#edf1f2'
    };

    window.APP_MEDIAQUERY = {
        'desktopLG':             1200,
        'desktop':                992,
        'tablet':                 768,
        'mobile':                 480
    };

})(window, document, window.jQuery);

// SIDEBAR
// -----------------------------------
(function(window, document, $, undefined) {

    var $win;
    var $html;
    var $body;
    var $sidebar;
    var mq;

    $(function() {

        $win = $(window);
        $html = $('html');
        $body = $('body');
        $sidebar = $('.sidebar');
        mq = APP_MEDIAQUERY;





        var sidebarAnyclickClose = $sidebar.data('sidebarAnyclickClose');

        // Allows to close
        if (typeof sidebarAnyclickClose !== 'undefined') {

            $('.wrapper').on('click.sidebar', function(e) {
                // don't check if sidebar not visible
                if (!$body.hasClass('aside-toggled')) return;

                var $target = $(e.target);
                if (!$target.parents('.aside-container').length && // if not child of sidebar
                    !$target.is('#user-block-toggle') && // user block toggle anchor
                    !$target.parent().is('#user-block-toggle') // user block toggle icon
                ) {
                    $body.removeClass('aside-toggled');
                }

            });
        }

    });

    function sidebarAddBackdrop() {
        var $backdrop = $('<div/>', { 'class': 'dropdown-backdrop' });
        $backdrop.insertAfter('.aside-container').on("click mouseenter", function() {
            removeFloatingNav();
        });
    }

    // Open the collapse sidebar submenu items when on touch devices
    // - desktop only opens on hover
    function toggleTouchItem($element) {
        $element
            .siblings('li')
            .removeClass('open')
            .end()
            .toggleClass('open');
    }

    // Handles hover to open items under collapsed menu
    // -----------------------------------
    function toggleMenuItem($listItem) {

        removeFloatingNav();

        var ul = $listItem.children('ul');

        if (!ul.length) return $();
        if ($listItem.hasClass('open')) {
            toggleTouchItem($listItem);
            return $();
        }

        var $aside = $('.aside-container');
        var $asideInner = $('.aside-inner'); // for top offset calculation
        // float aside uses extra padding on aside
        var mar = parseInt($asideInner.css('padding-top'), 0) + parseInt($aside.css('padding-top'), 0);

        var subNav = ul.clone().appendTo($aside);

        toggleTouchItem($listItem);

        var itemTop = ($listItem.position().top + mar) - $sidebar.scrollTop();
        var vwHeight = $win.height();

        subNav
            .addClass('nav-floating')
            .css({
                position: isFixed() ? 'fixed' : 'absolute',
                top: itemTop,
                bottom: (subNav.outerHeight(true) + itemTop > vwHeight) ? 0 : 'auto'
            });

        subNav.on('mouseleave', function() {
            toggleTouchItem($listItem);
            subNav.remove();
        });

        return subNav;
    }

    function removeFloatingNav() {
        $('.sidebar-subnav.nav-floating').remove();
        $('.dropdown-backdrop').remove();
        $('.sidebar li.open').removeClass('open');
    }

    function isTouch() {
        return $html.hasClass('touch');
    }

    function isSidebarCollapsed() {
        return $body.hasClass('aside-collapsed') || $body.hasClass('aside-collapsed-text');
    }

    function isSidebarToggled() {
        return $body.hasClass('aside-toggled');
    }

    function isMobile() {
        return $win.width() < mq.tablet;
    }

    function isFixed() {
        return $body.hasClass('layout-fixed');
    }

    function useAsideHover() {
        return $body.hasClass('aside-hover');
    }

})(window, document, window.jQuery);





// TOGGLE SIDEBAR STATE
// -----------------------------------
(function(window, document, $, undefined) {

    $(function() {
        let $body = $('body');
        let toggle = new StateToggler();

        $('[data-toggle-state]').on('click', function(e) {
            // e.preventDefault();
            e.stopPropagation();
            let element = $(this),
                classname = element.data('toggleState'),
                target = element.data('target'),
                noPersist = (element.attr('data-no-persist') !== undefined);
            // Specify a target selector to toggle classname
            // use body by default
            let $target = target ? $(target) : $body;

            if (classname) {
                if ($target.hasClass(classname)) {
                    $target.removeClass(classname);
                    if (!noPersist) {
                        toggle.removeState(classname);
                    }
                } else {
                    $target.addClass(classname);
                    if (!noPersist) {
                        toggle.addState(classname);
                    }
                }
            }

            // some elements may need this when toggled class change the content size
            $(window).resize();
        });
    });

    // Handle states to/from localstorage
    window.StateToggler = function() {
        let storageKeyName = 'jq-toggleState';
        // Helper object to check for words in a phrase //
        let WordChecker = {
            hasWord: function(phrase, word) {
                return new RegExp('(^|\\s)' + word + '(\\s|$)').test(phrase);
            },
            addWord: function(phrase, word) {
                if (!this.hasWord(phrase, word)) {
                    return (phrase + (phrase ? ' ' : '') + word);
                }
            },
            removeWord: function(phrase, word) {
                if (this.hasWord(phrase, word)) {
                    return phrase.replace(new RegExp('(^|\\s)*' + word + '(\\s|$)*', 'g'), '');
                }
            }
        };

        // Return service public methods
        return {
            // Add a state to the browser storage to be restored later
            addState: function(classname) {
                let data = $.localStorage.get(storageKeyName);
                if (!data) {
                    data = classname;
                } else {
                    data = WordChecker.addWord(data, classname);
                }
                $.localStorage.set(storageKeyName, data);
            },

            // Remove a state from the browser storage
            removeState: function(classname) {
                let data = $.localStorage.get(storageKeyName);
                // nothing to remove
                if (!data) {
                    return;
                }
                data = WordChecker.removeWord(data, classname);
                $.localStorage.set(storageKeyName, data);
            },

            // Load the state string and restore the classlist
            restoreState: function($elem) {
                var data = $.localStorage.get(storageKeyName);
                // nothing to restore
                if (!data) {
                    return;
                }
                $elem.addClass(data);
            }
        };
    };

})(window, document, window.jQuery);
