var $formRegister, $formEdit, $formDelete, $modalEditar, $modalEliminar;

$ (function () {
    $formRegister = $('#form-register');
    $formEdit = $('#form-editar');
    $formDelete = $('#form-delete');
    
    $modalEditar = $('#modal2');
    $modalEliminar = $('#modal3');

    $('#save-informe').on('click', function () {
        event.preventDefault();
        avatarUrl = $formRegister.attr('action');
        $.ajax({
            url: avatarUrl,
            method: 'POST',
            data: $formRegister.serialize(),
            success: function (data) {
                console.log(data);
                if (data != "") {
                    for (var property in data) {
                        Materialize.toast(data[property], 4000);
                    }
                } else {
                    Materialize.toast("Informe registrado correctamente", 4000);
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
                    Materialize.toast("Informe eliminado correctamente", 4000);
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



