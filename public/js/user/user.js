var $selectPositionDropdowm, $formRegister, $formEdit, $formDelete, $modalEditar, $modalEliminar, $selectPosition, $divPositionD, $avatarInput, $selectDropdown, $selectRol;

$ (function () {
    $formRegister = $('#form-register');
    $formEdit = $('#form-editar');
    $formDelete = $('#form-delete');
    
    $modalEditar = $('#modal2');
    $modalEliminar = $('#modal3');

    $avatarInput = $('#avatarInput');

    $selectRol = $('#role');

    $selectPosition = $('#positions');

    var role = $selectRol.val();

    $selectRol.on('change', function () {
        var role = $selectRol.val();
        if (role == 4)
        {
            $selectPosition.fadeOut();
        }else{
            $selectPosition.fadeIn();
        }
    });

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

    $divPositionD = $('#positionDropdown');

    $selectDropdown.material_select();

    $selectPositionDropdowm = $('#position_select');
    $selectPositionDropdowm.material_select();

    $.getJSON('roles/users',function(response)
    {
        console.log(response);
        $.each(response,function(key,value)
        {
            console.log("got you");
            $selectDropdown.append($("<option></option>").attr("value", value.id).text(value.name));
        });

    });

    $.getJSON('positions/users',function(response)
    {
        console.log(response);
        $selectPositionDropdowm.append($("<option></option>").attr("value", "").text("Escoja una opcion"));
        $.each(response,function(key,value)
        {
            console.log("got you");
            $selectPositionDropdowm.append($("<option></option>").attr("value", value.id).text(value.name));
        });

    });

    $('[data-edit]').on('click', function () {
        var id = $(this).data('edit');
        var name = $(this).data('name');
        var password = $(this).data('password');
        var role_id = $(this).data('roleid');
        var position_id = $(this).data('positionid');
        var role = $(this).data('role');
        console.log('role_id'+role_id);
        console.log('role'+role);

        $formEdit.find('[name="id"]').val(id);
        $formEdit.find('[name="name"]').val(name);

        if (role_id == 4)
        {
            $divPositionD.fadeOut();
        }else{
            $divPositionD.fadeIn();
            $selectPositionDropdowm.val(position_id).change();
        }

        $selectDropdown.val(role_id).change();

        $modalEditar.modal('open');

        Materialize.updateTextFields(); // use this after change the field values
        $selectDropdown.material_select();
        $selectPositionDropdowm.material_select();
    });

    $selectDropdown.on('change', function () {
        var role = $selectDropdown.val();
        if (role == 4)
        {
            $divPositionD.fadeOut();
        }else{
            $divPositionD.fadeIn();
        }
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



