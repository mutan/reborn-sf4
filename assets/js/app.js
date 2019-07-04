/* SASS CSS */

require('../css/app.scss');

/* JS LIBRARIES */

const $ = require('jquery');
//global.$ = global.jQuery = $;

//Если включить, теги i будут заменяться на svg
//require('@fortawesome/fontawesome-free/js/all');

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


/* CUSTOM JS */

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

/* Datatables */
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
            beforeSend: ()=> {Modal.toggleButtonSpinnerIcon(e);},
            complete: ()=> {Modal.toggleButtonSpinnerIcon(e);}
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

$('#card-new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/card/new`,
        size: 'modal-lg'
    });
});

$('.card-edit').on('click', (e)=> {
    let cardId = $(e.currentTarget).attr('data-card-id');
    Modal.handleMainModal(e, {
        url: `/admin/card/${cardId}/edit`,
        size: 'modal-lg'
    });
});

$('#artist-new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/artist/new`,
        size: 'modal-sm'
    });
});

$('.artist-edit').on('click', (e)=> {
    let artistId = $(e.currentTarget).attr('data-artist-id');
    Modal.handleMainModal(e, {
        url: `/admin/artist/${artistId}/edit`,
        size: 'modal-sm'
    });
});

$('#edition-new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/edition/new`
    });
});

$('.edition-edit').on('click', (e)=> {
    let editionId = $(e.currentTarget).attr('data-edition-id');
    Modal.handleMainModal(e, {
        url: `/admin/edition/${editionId}/edit`
    });
});

$('#element-new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/element/new`,
        size: 'modal-sm'
    });
});

$('.element-edit').on('click', (e)=> {
    let elementId = $(e.currentTarget).attr('data-element-id');
    Modal.handleMainModal(e, {
        url: `/admin/element/${elementId}/edit`,
        size: 'modal-sm'
    });
});

$('#liquid-new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/liquid/new`,
        size: 'modal-sm'
    });
});

$('.liquid-edit').on('click', (e)=> {
    let liquidId = $(e.currentTarget).attr('data-liquid-id');
    Modal.handleMainModal(e, {
        url: `/admin/liquid/${liquidId}/edit`,
        size: 'modal-sm'
    });
});

$('#rarity-new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/rarity/new`,
        size: 'modal-sm'
    });
});

$('.rarity-edit').on('click', (e)=> {
    let rarityId = $(e.currentTarget).attr('data-rarity-id');
    Modal.handleMainModal(e, {
        url: `/admin/rarity/${rarityId}/edit`,
        size: 'modal-sm'
    });
});

$('#subtype-new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/subtype/new`,
        size: 'modal-sm'
    });
});

$('.subtype-edit').on('click', (e)=> {
    let subtypeId = $(e.currentTarget).attr('data-subtype-id');
    Modal.handleMainModal(e, {
        url: `/admin/subtype/${subtypeId}/edit`,
        size: 'modal-sm'
    });
});

$('#supertype-new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/supertype/new`,
        size: 'modal-sm'
    });
});

$('.supertype-edit').on('click', (e)=> {
    let supertypeId = $(e.currentTarget).attr('data-supertype-id');
    Modal.handleMainModal(e, {
        url: `/admin/supertype/${supertypeId}/edit`,
        size: 'modal-sm'
    });
});

$('#type-new').on('click', (e)=> {
    Modal.handleMainModal(e, {
        url: `/admin/type/new`,
        size: 'modal-sm'
    });
});

$('.type-edit').on('click', (e)=> {
    let typeId = $(e.currentTarget).attr('data-type-id');
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
            {'name': 'counter', 'tooltip': 'Жетон', 'image': '<img src="/icons/counter-16x16.png">'},
            {'name': 'tap', 'tooltip': 'Закрыть', 'image': '<img src="/icons/tap-16x16.png">'},
            {'name': 'insttap', 'tooltip': 'Внезапное действие', 'image': '<img src="/icons/insttap-16x16.png">'}
        ];
        icon_buttons.forEach(function(item, index) {
            editor.ui.registry.addButton(item.name, {
                tooltip : item.tooltip,
                text: item.image,
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