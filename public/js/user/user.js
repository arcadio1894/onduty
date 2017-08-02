var $selectDepartmentDropdowm, $selectPositions, $selectDepartments, $selectLocationEdit, $$selectPositionEdit, $formRegister, $formEdit, $formDelete, $modalEditar, $modalEliminar, $selectPositionContainer, $positionContainerEdit, $avatarInput, $selectDropdown, $selectRol;

$ (function () {
    $formRegister = $('#form-register');
    $formEdit = $('#form-editar');
    $formDelete = $('#form-delete');
    
    $modalEditar = $('#modal2');
    $modalEliminar = $('#modal3');

    $avatarInput = $('#avatarInput');

    $selectRol = $('#role');
    $selectPositionContainer = $('#positions');
    $selectPositions = $('#position');    
    $selectDepartments = $('#department');

    var role = $selectRol.val();

    $selectDepartments.on('change', function () {
        var id_department = $selectDepartments.val();
        // console.log(id_department);
        if (id_department != "")
        {
            $selectPositions
                .empty()
                .append('<option value="" disabled selected >Escoja un cargo</option>')
            ;
            $.getJSON('position/department/' + id_department , function(response)
            {
                //console.log(response);
                $.each(response,function(key,value)
                {
                    $selectPositions.append($("<option></option>").attr("value", value.id).text(value.name));
                });
                $selectPositions.material_select();
                Materialize.updateTextFields();
            });
        }
    });
    $selectRol.on('change', function () {
        var role = $selectRol.val();
        if (role == 4) {
            $selectPositionContainer.fadeOut();
        } else {
            $selectPositionContainer.fadeIn();
        }
    });

    $formRegister.on('submit', function () {
        event.preventDefault();
        var avatarUrl = $formRegister.attr('action');
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

    $positionContainerEdit = $('#positionDropdown');

    $selectDropdown.material_select();

    $$selectPositionEdit = $('#position_select');
    $$selectPositionEdit.material_select();

    $selectLocationEdit = $('#location_select');
    $selectLocationEdit.material_select();

    $selectDepartmentDropdowm = $('#department_select');
    $.getJSON('locations/users',function(response)
    {
        // console.log(response);
        $.each(response,function(key,value)
        {
            $selectLocationEdit.append($("<option></option>").attr("value", value.id).text(value.name));
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

    

    $('[data-edit]').on('click', function () {
        var id = $(this).data('edit');
        var name = $(this).data('name');
        var password = $(this).data('password');
        var role_id = $(this).data('roleid');
        var department_id = $(this).data('departmentid');
        var position_id = $(this).data('positionid');
        var location_id = $(this).data('locationid');
        var role = $(this).data('role');
        // console.log('department_id ' + department_id);

        $.getJSON('department/user',function(response)
        {
            // console.log(response);
            $selectDepartmentDropdowm
                .empty()
                .append('<option value="" disabled selected >Escoja un departamento</option>')
            ;
            $.each(response,function(key,value)
            {
                $selectDepartmentDropdowm.append($("<option></option>").attr("value", value.id).text(value.name));
            });

            $selectDepartmentDropdowm.val(department_id).change();
            $selectDepartmentDropdowm.material_select();
            Materialize.updateTextFields();
        });



        $selectDepartmentDropdowm.on('change', function () {
            var new_department = $selectDepartmentDropdowm.val();
            if (department_id != "")
            {

                $.getJSON('position/department/' + new_department , function(response)
                {
                    $$selectPositionEdit
                        .empty()
                        .append('<option value="" disabled selected >Escoja un cargo</option>')
                    ;
                    $.each(response,function(key,value)
                    {
                        $$selectPositionEdit.append($("<option></option>").attr("value", value.id).text(value.name));
                    });
                    $$selectPositionEdit.val(position_id).change();
                    $$selectPositionEdit.material_select();
                    Materialize.updateTextFields();
                });
            }
        });


        $formEdit.find('[name="id"]').val(id);
        $formEdit.find('[name="name"]').val(name);

        if (role_id == 4) {
            $positionContainerEdit.fadeOut();
        } else {
            $positionContainerEdit.fadeIn();
            $$selectPositionEdit.val(position_id).change();
        }

        $selectDropdown.val(role_id).change();
        $selectLocationEdit.val(location_id).change();

        $modalEditar.modal('open');

        Materialize.updateTextFields(); // use this after change the field values
        $selectDropdown.material_select();
        $$selectPositionEdit.material_select();
        $selectLocationEdit.material_select();
    });

    $selectDropdown.on('change', function () {
        var role = $selectDropdown.val();
        if (role == 4) {
            $positionContainerEdit.fadeOut();
        } else {
            $positionContainerEdit.fadeIn();
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



