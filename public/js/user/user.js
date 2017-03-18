var $selectLocationD, $selectPositionDropdowm, $formRegister, $formEdit, $formDelete, $modalEditar, $modalEliminar, $selectPosition, $divPositionD, $avatarInput, $selectDropdown, $selectRol;

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
            contentType: false,
            success: function (data) {
                console.log(data);
                if (data != "") {
                    for (var property in data) {
                        Materialize.toast(data[property], 4000);
                    }
                } else {
                    Materialize.toast("Usuario registrado correctamente", 2200);
                    setTimeout(function(){
                        location.reload();
                    }, 1600);
                }
            },
            error: function (data) {
                // Render the errors with js ...
                console.log(data);
            }
        })
    });

    // initialize
    $('select').material_select();

    $selectDropdown = $('#role_select');

    $divPositionD = $('#positionDropdown');

    $selectDropdown.material_select();

    $selectPositionDropdowm = $('#position_select');
    $selectPositionDropdowm.material_select();

    $selectLocationD = $('#location_select');
    $selectLocationD.material_select();
    $.getJSON('locations/users',function(response)
    {
        // console.log(response);
        $.each(response,function(key,value)
        {
            $selectLocationD.append($("<option></option>").attr("value", value.id).text(value.name));
        });

    });

    $.getJSON('roles/users',function(response)
    {
        // console.log(response);
        $.each(response,function(key,value)
        {
            $selectDropdown.append($("<option></option>").attr("value", value.id).text(value.name));
        });

    });

    $.getJSON('positions/users',function(response)
    {
        // console.log(response);
        $selectPositionDropdowm.append($("<option></option>").attr("value", "").text("Escoja una opcion"));
        $.each(response,function(key,value)
        {
            $selectPositionDropdowm.append($("<option></option>").attr("value", value.id).text(value.name));
        });

    });

    $('[data-edit]').on('click', function () {
        var id = $(this).data('edit');
        var name = $(this).data('name');
        var password = $(this).data('password');
        var role_id = $(this).data('roleid');
        var position_id = $(this).data('positionid');
        var location_id = $(this).data('locationid');
        var role = $(this).data('role');
        console.log('location_id'+location_id);

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
        $selectLocationD.val(location_id).change();

        $modalEditar.modal('open');

        Materialize.updateTextFields(); // use this after change the field values
        $selectDropdown.material_select();
        $selectPositionDropdowm.material_select();
        $selectLocationD.material_select();
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
            data: $formEdit.serialize(),
            success: function (data) {
                console.log(data);
                if (data != "") {
                    for (var property in data) {
                        Materialize.toast(data[property], 4000);
                    }
                } else {
                    Materialize.toast("Usuaro modificado correctamente", 2500);
                    setTimeout(function(){
                        location.reload();
                    }, 1700);
                }
            },
            error: function (data) {
                // Render the errors with js ...
                console.log(data);
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

    $('#delete-user').on('click', function () {
        var avatarUrl = $formDelete.attr('action');
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
                    Materialize.toast("Se actualizó el estado del usuario correctamente", 2200);
                    setTimeout(function(){
                        location.reload();
                    }, 1700);
                }
            },
            error: function (data) {
                // Render the errors with js ...
                console.log(data);
            }
        })
    });
});



