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
            contentType: false,
            success: function (data) {
                console.log(data);
                if (data != "") {
                    for (var property in data) {
                        Materialize.toast(data[property], 4000);
                    }
                } else {
                    Materialize.toast("Informe editado correctamente", 4000);
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
            },
            error: function (data) {
                console.log("CZFDFDSF");
                // Render the errors with js ...
            }
        })
    });
});



