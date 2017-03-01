var $formRegister;

$ (function () {

    $formRegister = $('#form-register');

    $formRegister.on('submit', function () {
        event.preventDefault();
        url = $formRegister.attr('action');
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



