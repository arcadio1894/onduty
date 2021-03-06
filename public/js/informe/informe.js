var $formRegister, $formEdit, $formDelete, $modalEliminar;

$ (function () {
    $formRegister = $('#form-register');
    $formEdit = $('#form-editar');
    $formDelete = $('#form-delete');

    $modalEliminar = $('#modal3');

    $formRegister.on('submit', function () {
        event.preventDefault();
        avatarUrl = $formRegister.attr('action');
        $('#save-informe').prop('disabled', true);
        $('#line-loader').show();
        $.ajax({
            url: avatarUrl,
            method: 'POST',
            data: $formRegister.serialize(),
            success: function (data) {
                console.log(data);
                $('#line-loader').hide();
                if (data != "") {
                    for (var property in data) {
                        Materialize.toast(data[property], 4000);
                    }
                    $('#save-informe').prop('disabled', false);
                } else {
                    Materialize.toast("Informe registrado correctamente", 2000);
                    setTimeout(function(){
                        location.reload();
                    }, 1800);
                }

            },
            error: function (data) {
                console.log("Error inesperado");
                // Render the errors with js ...
            }
        });

    });

    $('[data-delete]').on('click', function () {
        var id = $(this).data('delete');
        var name = $(this).data('name');

        $formDelete.find('[name="id"]').val(id);
        $formDelete.find('[name="name"]').val(name);
        $modalEliminar.modal('open');
    });

    $('#delete-informe').on('click', function () {
        avatarUrl = $formDelete.attr('action');
        $.ajax({
            url: avatarUrl,
            method: 'POST',
            data: $formDelete.serialize(),
            success: function (data) {
                console.log(data);
                if (data != "") {
                    for (var property in data) {
                        Materialize.toast(data[property], 4000);
                    }
                } else {
                    Materialize.toast("Informe eliminado correctamente", 2000);
                    setTimeout(function(){
                        location.reload();
                    }, 1800);
                }
            },
            error: function (error) {
                console.log(error);
            }
        })

    });
});



