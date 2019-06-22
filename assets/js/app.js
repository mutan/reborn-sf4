/* SASS CSS */

require('../css/app.scss');

/* JS LIBRARIES */

const $ = require('jquery');
//global.$ = global.jQuery = $;
require('@fortawesome/fontawesome-free/js/all');
require('popper.js/dist/popper');
require('bootstrap/dist/js/bootstrap');

require('datatables.net/js/jquery.dataTables');
require('datatables.net-bs4/js/dataTables.bootstrap4');

/* CUSTOM JS */

$(document).ready( function () {
    $('#datatable').DataTable({
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
                "sFirst": "Первая",
                "sPrevious": "Предыдущая",
                "sNext": "Следующая",
                "sLast": "Последняя"
            },
            "oAria": {
                "sSortAscending":  ": активировать для сортировки столбца по возрастанию",
                "sSortDescending": ": активировать для сортировки столбцов по убыванию"
            }
        }
    });
} );
