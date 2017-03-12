var $formRegister, $formEdit, $formDelete, $modalEditar, $modalEliminar, $modalCrear;

$ (function () {
    $formRegister = $('#form-register');
    $formEdit = $('#form-edit');
    $formDelete = $('#form-delete');

    $modalCrear = $('#modal2');
    $modalEditar = $('#modalEdit');
    $modalEliminar = $('#modal1');

    $formRegister.on('submit', function () {
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
                    Materialize.toast("Observación registrada correctamente", 4000);
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
            },
            error: function (data) {
                console.log("CZFDFDSF");
                // Render the errors with js ...
            }
        });
    });

    $('[data-edit]').on('click', function () {
        var id = $(this).data('edit');
        var turn = $(this).data('turn');
        var supervisor = $(this).data('supervisor');
        var hse = $(this).data('hse');
        var man = $(this).data('man');
        var woman = $(this).data('woman');
        var turn_hours = $(this).data('turn_hours');
        var observation = $(this).data('observation');

        $formEdit.find('[name="id"]').val(id);
        $formEdit.find('[name="turn_edit"]').val(turn).change();
        $formEdit.find('[name="supervisor_edit"]').val(supervisor).change();
        $formEdit.find('[name="hse_edit"]').val(hse).change();
        $formEdit.find('[name="man_edit"]').val(man);
        $formEdit.find('[name="woman_edit"]').val(woman);
        $formEdit.find('[name="turn_hours_edit"]').val(turn_hours);
        $formEdit.find('[name="observation_edit"]').html(observation);

        $formEdit.find('[name="turn_edit"]').material_select();
        $formEdit.find('[name="supervisor_edit"]').material_select();
        $formEdit.find('[name="hse_edit"]').material_select();
        
        $modalEditar.modal('open');
        
        Materialize.updateTextFields(); // use this after change the field values
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
                    Materialize.toast("Observación moodificada correctamente", 4000);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            },
            error: function (data) {
                console.log("CZFDFDSF");
                // Render the errors with js ...
            }
        });
    });

    $('[data-delete]').on('click', function () {
        var id = $(this).data('delete');

        $formDelete.find('[name="id"]').val(id);
        $modalEliminar.modal('open');
    });

    $formDelete.on('submit', function () {
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
                    Materialize.toast("Observación eliminada correctamente", 4000);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            },
            error: function (data) {
                console.log("CZFDFDSF");
                // Render the errors with js ...
            }
        });
    });
});



