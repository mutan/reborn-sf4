require('../css/admin.scss');

require('./modernizr.custom');
const Storages = require('js-storage/js.storage');
const $ = require('jquery');
require('popper.js/dist/popper');
require('bootstrap/dist/js/bootstrap');
require('datatables.net/js/jquery.dataTables');
require('datatables.net-bs4/js/dataTables.bootstrap4');







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
