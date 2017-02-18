var $formRegister, $formEdit, $modalEditar;

$ (function () {
    $formRegister = $('#form-register');
    $formEdit = $('#form-editar');
    $modalEditar = $('#modal2');

    $('#save-location').on('click', function () {
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
        var name = $(this).data('name');
        var description = $(this).data('description');

        $formEdit.find('[name="id"]').val(id);
        $formEdit.find('[name="name"]').val(name);
        $formEdit.find('[name="description"]').val(description);
        $modalEditar.modal('open');
    });
});



