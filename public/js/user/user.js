var $formRegister, $formEdit, $formDelete, $modalEditar, $modalEliminar, $avatarInput, $selectDropdown;

$ (function () {
    $formRegister = $('#form-register');
    $formEdit = $('#form-editar');
    $formDelete = $('#form-delete');
    
    $modalEditar = $('#modal2');
    $modalEliminar = $('#modal3');

    $avatarInput = $('#avatarInput');

    $formRegister.on('submit', function () {
        event.preventDefault();
        avatarUrl = $formRegister.attr('action');
        $.ajax({
            url: avatarUrl,
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

    // initialize
    $('select').material_select();

    $selectDropdown = $('#role_select');

    $selectDropdown.material_select();

    $.getJSON('roles/users',function(response)
    {
        console.log(response);
        $.each(response,function(key,value)
        {
            console.log("got you");
            $selectDropdown.append($("<option></option>").attr("value", value.id).text(value.name));
        });

    });

    $('[data-edit]').on('click', function () {
        var id = $(this).data('edit');
        var name = $(this).data('name');
        var password = $(this).data('password');
        var role_id = $(this).data('roleid');
        var role = $(this).data('role');

        $formEdit.find('[name="id"]').val(id);
        $formEdit.find('[name="name"]').val(name);
        $selectDropdown.val(role_id).change();

        $modalEditar.modal('open');

        Materialize.updateTextFields(); // use this after change the field values
        $selectDropdown.material_select();
    });

    $formEdit.on('submit', function () {
        event.preventDefault();
        avatarUrl = $formEdit.attr('action');
        $.ajax({
            url: avatarUrl,
            method: 'POST',
            data: $formEdit.serialize()
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

    $('[data-delete]').on('click', function () {
        var id = $(this).data('delete');
        var name = $(this).data('name');

        $formDelete.find('[name="id"]').val(id);
        $formDelete.find('[name="name"]').val(name);
        $modalEliminar.modal('open');
    });

    $('#delete-user').on('click', function () {
        avatarUrl = $formDelete.attr('action');
        $.ajax({
            url: avatarUrl,
            method: 'POST',
            data: $formDelete.serialize()
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



