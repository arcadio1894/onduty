var $formRegister, $formEdit, $formDelete, $modalEditar, $modalEliminar;

$ (function () {
    $formRegister = $('#form-register');
    $formEdit = $('#form-editar');
    $formDelete = $('#form-delete');
    
    $modalEditar = $('#modal2');
    $modalEliminar = $('#modal3');

    $('#save-area').on('click', function () {
        avatarUrl = $formRegister.attr('action');
        $.ajax({
            url: avatarUrl,
            method: 'POST',
            data: $formRegister.serialize()
        })
            .done(function (data) {
                if(data.error)
                    alert(data.message);
                else{
                    alert(data.message);
                    location.reload();
                }
            })
            .fail(function () {
                alert('Ocurrió un error inesperado');
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

    $('#edit-area').on('click', function () {
        avatarUrl = $formEdit.attr('action');
        $.ajax({
            url: avatarUrl,
            method: 'POST',
            data: $formEdit.serialize()
        })
            .done(function (data) {
                if(data.error)
                    alert(data.message);
                else{
                    alert(data.message);
                    location.reload();
                }
            })
            .fail(function () {
                alert('Ocurrió un error inesperado');
            });
    });

    $('[data-delete]').on('click', function () {
        var id = $(this).data('delete');
        var name = $(this).data('name');

        $formDelete.find('[name="id"]').val(id);
        $formDelete.find('[name="name"]').val(name);
        $modalEliminar.modal('open');
    });

    $('#delete-area').on('click', function () {
        avatarUrl = $formDelete.attr('action');
        $.ajax({
            url: avatarUrl,
            method: 'POST',
            data: $formDelete.serialize()
        })
            .done(function (data) {
                if(data.error)
                    alert(data.message);
                else{
                    alert(data.message);
                    location.reload();
                }
            })
            .fail(function () {
                alert('Ocurrió un error inesperado');
            });
    });
});



