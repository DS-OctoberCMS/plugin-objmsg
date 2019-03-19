const mainJqdd = {

    /*
     * Properties
     */

    lang: {
        "processing": "Подождите...",
        "search": "Поиск:",
        "lengthMenu": "Показать _MENU_ записей",
        "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
        "infoEmpty": "Записи с 0 до 0 из 0 записей",
        "infoFiltered": "(отфильтровано из _MAX_ записей)",
        "infoPostFix": "",
        "loadingRecords": "Загрузка записей...",
        "zeroRecords": "Записи отсутствуют.",
        "emptyTable": "В таблице отсутствуют данные",
        "paginate": {
            "first": "Первая",
            "previous": "Предыдущая",
            "next": "Следующая",
            "last": "Последняя"
        },
        "aria": {
            "sortAscending": ": активировать для сортировки столбца по возрастанию",
            "sortDescending": ": активировать для сортировки столбца по убыванию"
        }
    }
};

$(document).ready(function()
{
    var table = $('#userMsgList').DataTable({
        language: mainJqdd.lang,
        processing: true,
        serverSide: true,
        stateSave: true,
        scrollX: true,
        ajax: {
            url: window.location.href,
            type: 'POST',
            headers: {
                'X-OCTOBER-REQUEST-FLASH': 1,
                'X-OCTOBER-REQUEST-HANDLER': 'messages::onUserMsgList'
            }
        },
        columns: [
            {data: "created"},
            {data: "message"},
        ],
    });

    $('#userMsgList tbody').on('click', 'tr', function() {
        let $this  = $(this);
        let msgId  = $this.find('.msg-url-data:eq(0)').data('msg-id');
        let msgUrl = $('#userMsgList').data('msg-url');

        if (msgUrl)
            msgUrl = msgUrl.replace(/\/msg_id$/, '/');

        table.$('tr.selected').removeClass('selected');
        $this.addClass('selected');

        window.location.href = msgUrl + msgId;
    });
});