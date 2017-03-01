var $formEdit;

$ (function () {

    $formEdit = $('#form-edit');

    $formEdit.on('submit', function () {
        event.preventDefault();
        url = $formEdit.attr('action');
        $.ajax({
            url: url,
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false
        })
            .done(function (data) {
                if(data.error)
                    Materialize.toast(data.message, 4000);
                else{
                    Materialize.toast(data.message, 4000);
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
            })
            .fail(function () {
                alert('Ocurrió un error inesperado');
            });
    });
});



