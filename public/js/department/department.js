var $formRegister, $formEdit, $formDelete, $modalEditar, $modalEliminar;

$ (function () {
    $formRegister = $('#form-register');
    $formEdit = $('#form-editar');
    $formDelete = $('#form-delete');
    
    $modalEditar = $('#modal2');
    $modalEliminar = $('#modal3');

    $('#save-department').on('click', function () {
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
                    Materialize.toast("Departamento registrado correctamente", 2200);
                    setTimeout(function(){
                        location.reload();
                    }, 1800);
                }
            },
            error: function (data) {
                // Render the errors with js ...
                console.log(data);
            }
        });
    });

    
    $('[data-edit]').on('click', function () {
        var id = $(this).data('edit');
        var location = $(this).data('location');
        var name = $(this).data('name');
        var description = $(this).data('description');

        $formEdit.find('[name="id"]').val(id);
        $formEdit.find('[name="name"]').val(name);
        $formEdit.find('[name="description"]').val(description);

        $modalEditar.modal('open');
        Materialize.updateTextFields(); // use this after change the field values
    });

    $('#edit-department').on('click', function () {
        event.preventDefault();
        avatarUrl = $formEdit.attr('action');
        $.ajax({
            url: avatarUrl,
            method: 'POST',
            data: $formEdit.serialize(),
            success: function (data) {
                console.log(data);
                if (data != "") {
                    for (var property in data) {
                        Materialize.toast(data[property], 4000);
                    }
                } else {
                    Materialize.toast("Departamento moodificado correctamente", 2200);
                    setTimeout(function () {
                        location.reload();
                    }, 1800);
                }
            },
            error: function (data) {
                // Render the errors with js ...
                console.log(data);
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

    $('#delete-department').on('click', function () {
        event.preventDefault();
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
                    Materialize.toast("Departamento eliminado correctamente", 2200);
                    setTimeout(function () {
                        location.reload();
                    }, 1800);
                }
            },
            error: function (data) {
                // Render the errors with js ...
                console.log(data);
            }
        });
    });
});
