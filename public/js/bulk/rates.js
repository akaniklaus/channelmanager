/**
 * Created by alex on 10/17/14.
 */
$(function () {
    $('.input-group.date').datepicker({
        format: "yyyy-mm-dd",
        weekStart: 1,
        startDate: "today",
        todayBtn: "linked",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });

    $('#loading-example-btn').click(function () {
        var btn = $(this)
        btn.button('loading')
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: BULK_UPDATE_URL,
            data: $('#bulk_rates').serialize()
        }).always(function () {
            btn.button('reset')
        }).fail(function (data) {
            if (data.responseJSON.errors) {
                var msg = '';
                for (var i in data.responseJSON.errors) {
                    msg += data.responseJSON.errors[i] + "\n";
                }
                alert(msg);
            }
        }).done(function () {
            alert('Success');
        })
    });
});


function bulkSelectAll(className) {
    $('.' + className + '  input:checkbox').each(function () {
        this.checked = true;
    });
}
function bulkUnSelectAll(className) {
    $('.' + className + '  input:checkbox').each(function () {
        this.checked = false;
    });
}