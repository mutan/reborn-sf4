/* =============================================================================
   Styles, libraries
============================================================================= */

require('../css/admin.scss');

require('./modernizr.custom');
const store = require('store');
const $ = require('jquery');

require('popper.js/dist/popper');
require('bootstrap/dist/js/bootstrap');

require('datatables.net/js/jquery.dataTables');
require('datatables.net-bs4/js/dataTables.bootstrap4');

const tinymce = require('tinymce/tinymce');
require('tinymce/themes/silver'); // A theme is also required
require('tinymce/plugins/paste'); // Any plugins you want to use has to be imported
require('tinymce/plugins/link');
require('tinymce/plugins/code');
require('tinymce/plugins/image');
require('tinymce/plugins/advlist');
require('tinymce/plugins/lists');

/* =============================================================================
   Custom styles
============================================================================= */

/* Сохраняем состояние меню "card_parts" в localStorage */
$(document).ready(function () {
    let $cardPartsUl = $('#card_parts');
    let $cardPartsA = $cardPartsUl.prev('a');

    $cardPartsA.on('click', () => {
        store.get('card_menu_collapsed')
            ? store.set('card_menu_collapsed', 0)
            : store.set('card_menu_collapsed', 1)
    });

    if (store.get('card_menu_collapsed')) { // меню должно быть свернуто
        if (!$cardPartsA.hasClass('collapsed')) {
            $cardPartsA.addClass('collapsed');
        }
        if ($cardPartsUl.hasClass('show')) {
            $cardPartsUl.removeClass('show');
        }
    } else { // меню должно быть развернуто
        if ($cardPartsA.hasClass('collapsed')) {
            $cardPartsA.removeClass('collapsed');
        }
        if (!$cardPartsUl.hasClass('show')) {
            $cardPartsUl.addClass('show');
        }
    }
});

/* Bootstrap Tooltips Initialization */
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

/* Bootstrap Popover Initialization */
$(function () {
    $('[data-toggle="image-popover"]').popover({
        trigger: 'hover',
        placement: 'right',
        html: true,
        boundary: 'window',
        fallbackPlacement: 'clockwise',
        container: 'body',
    })
});

/* Datatables Initialization */
$(document).ready(function () {
    $('.datatable').DataTable(datatable_config);
});

/* Modal */
let Modal = {
    getModal: function() {
        return $('#mainModal');
    },
    toggleButtonSpinnerIcon: function(e) {
        $(e.currentTarget).find('i').toggleClass('fa-spinner fa-spin');
    },
    /* JQuery автодополнение */
    /* http://api.jqueryui.com/autocomplete/ */
    /*shopAutocomplete: function(id) {
        $(id).autocomplete({
            minLength: 2,
            source: '/basket/shop/autocomplete',
            select: function(event, ui) {
                $(id).val(ui.item.value);
                //$('#basket-shop-form').submit();
            }
        });
    },*/
    handleMainModal: function(e, options) {
        e.preventDefault();
        $.ajax({
            url: options.url,
            type: 'POST',
            beforeSend: ()=> {
                Modal.toggleButtonSpinnerIcon(e);
                $(e.currentTarget).addClass('disabled');
            },
            complete: ()=> {
                Modal.toggleButtonSpinnerIcon(e);
                $(e.currentTarget).removeClass('disabled');
            }
        }).then(function (response) {
            Modal.reload(Modal.getModal(), response, options);
        });
    },
    reload: function($modal, response, options) {
        if (options.size) {
            $modal.find('.modal-dialog').addClass(options.size);
        }
        $modal.find('.modal-content').html(response.output);
        tinymce.init(tiny_config);
        $modal.modal('show');
        if (options.shopAutocomplete) {
            Modal.shopAutocomplete(options.shopAutocompleteElem);
        }

        $modal.find('form').on('submit', (e)=> {
            e.preventDefault();
            let formData = $(e.currentTarget).serialize();
            const $submitButton = $(e.currentTarget).find('button[type=submit]');
            $.ajax({
                url: options.url,
                type: 'POST',
                data: formData,
                beforeSend: ()=> {
                    $submitButton.prop('disabled', true).html("<i class='fa fa-spinner fa-spin'></i> Идет сохранение");
                }
            }).then(function (response) {
                (response.reload) ? location.reload() : Modal.reload($modal, response, options);
            });
        });
    }
};

$('#card_new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/card/new`,
        size: 'modal-lg'
    });
});

$('.card_edit').on('click', (e)=> {
    let cardId = $(e.currentTarget).attr('data-id');
    Modal.handleMainModal(e, {
        url: `/admin/card/${cardId}/edit`,
        size: 'modal-lg'
    });
});

$('#artist_new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/artist/new`,
        size: 'modal-sm'
    });
});

$('.artist_edit').on('click', (e)=> {
    let artistId = $(e.currentTarget).attr('data-id');
    Modal.handleMainModal(e, {
        url: `/admin/artist/${artistId}/edit`,
        size: 'modal-sm'
    });
});

$('#edition_new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/edition/new`
    });
});

$('.edition_edit').on('click', (e)=> {
    let editionId = $(e.currentTarget).attr('data-id');
    Modal.handleMainModal(e, {
        url: `/admin/edition/${editionId}/edit`
    });
});

$('#element_new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/element/new`,
        size: 'modal-sm'
    });
});

$('.element_edit').on('click', (e)=> {
    let elementId = $(e.currentTarget).attr('data-id');
    Modal.handleMainModal(e, {
        url: `/admin/element/${elementId}/edit`,
        size: 'modal-sm'
    });
});

$('#liquid_new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/liquid/new`,
        size: 'modal-sm'
    });
});

$('.liquid_edit').on('click', (e)=> {
    let liquidId = $(e.currentTarget).attr('data-id');
    Modal.handleMainModal(e, {
        url: `/admin/liquid/${liquidId}/edit`,
        size: 'modal-sm'
    });
});

$('#rarity_new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/rarity/new`,
        size: 'modal-sm'
    });
});

$('.rarity_edit').on('click', (e)=> {
    let rarityId = $(e.currentTarget).attr('data-id');
    Modal.handleMainModal(e, {
        url: `/admin/rarity/${rarityId}/edit`,
        size: 'modal-sm'
    });
});

$('#subtype_new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/subtype/new`,
        size: 'modal-sm'
    });
});

$('.subtype_edit').on('click', (e)=> {
    let subtypeId = $(e.currentTarget).attr('data-id');
    Modal.handleMainModal(e, {
        url: `/admin/subtype/${subtypeId}/edit`,
        size: 'modal-sm'
    });
});

$('#supertype_new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/supertype/new`,
        size: 'modal-sm'
    });
});

$('.supertype_edit').on('click', (e)=> {
    let supertypeId = $(e.currentTarget).attr('data-id');
    Modal.handleMainModal(e, {
        url: `/admin/supertype/${supertypeId}/edit`,
        size: 'modal-sm'
    });
});

$('#type_new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/type/new`,
        size: 'modal-sm'
    });
});

$('.type_edit').on('click', (e)=> {
    let typeId = $(e.currentTarget).attr('data-id');
    Modal.handleMainModal(e, {
        url: `/admin/type/${typeId}/edit`,
        size: 'modal-sm'
    });
});

/* TinyMCE config */
const tiny_config = {
    selector: "textarea.tinymce",
    menubar : false,
    plugins: ['advlist', 'code', 'link', 'lists', 'paste'],
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignjustify | bullist numlist | counter tap insttap | erratadate | code removeformat',
    branding: false,
    setup: (editor) => {
        editor.ui.registry.addButton('erratadate', {
            title: 'Дата эрраты',
            icon: 'insert-time',
            onAction: () => {
                editor.focus();
                let today = new Date();
                let dd = String(today.getDate()).padStart(2, '0');
                let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                let yyyy = today.getFullYear();
                editor.selection.setContent('[' + mm + '.' + dd + '.' + yyyy + '] ');
            }
        });

        /* Чтобы не дублировать код */
        const icon_buttons = [
            {'name': 'counter', 'tooltip': 'Жетон', 'image': 'counter-16x16.png'},
            {'name': 'tap', 'tooltip': 'Закрыть', 'image': 'tap-16x16.png'},
            {'name': 'insttap', 'tooltip': 'Внезапное действие', 'image': 'insttap-16x16.png'}
        ];
        icon_buttons.forEach(function(item, index) {
            editor.ui.registry.addButton(item.name, {
                tooltip : item.tooltip,
                text: '<img src="/icons/' + item.image + '">',
                onAction: () => {
                    editor.focus();
                    editor.selection.setContent(item.image + ' ');
                }
            });
        });
    }
};

/* Datatables config */
const datatable_config = {
    "pagingType": "full_numbers",
    "language": {
        "sProcessing":   "Подождите...",
        "sLengthMenu":   "Показать _MENU_ записей",
        "sZeroRecords":  "Записи отсутствуют.",
        "sInfo":         "Записи с _START_ до _END_ из _TOTAL_ записей",
        "sInfoEmpty":    "Записи с 0 до 0 из 0 записей",
        "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
        "sInfoPostFix":  "",
        "sSearch":       "Поиск:",
        "sUrl":          "",
        "oPaginate": {
            "sFirst": "|<",
            "sPrevious": "<<",
            "sNext": ">>",
            "sLast": ">|"
        },
        "oAria": {
            "sSortAscending":  ": активировать для сортировки столбца по возрастанию",
            "sSortDescending": ": активировать для сортировки столбцов по убыванию"
        }
    }
};

/* -----------------------------------------------------------------------------
   Sidebar – закрываем при клике на на нем
----------------------------------------------------------------------------- */
(function(window, document, $, undefined) {

    let $body;
    $(function() {
        $body = $('body');
        $('.wrapper').on('click.sidebar', function(e) {
            // don't check if sidebar not visible
            if (!$body.hasClass('aside-toggled')) {
                return;
            }
            if (!$(e.target).parents('.aside-container').length) { // if not child of sidebar
                $body.removeClass('aside-toggled');
            }
        });
    });

})(window, document, window.jQuery);

/* -----------------------------------------------------------------------------
   Toggle sidebar state
----------------------------------------------------------------------------- */
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
                //console.dir($.localStorage);
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
