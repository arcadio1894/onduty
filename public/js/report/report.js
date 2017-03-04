var $formEliminar, $modalEliminar, $formEdit, $modalEditar;

var $selectLocation, $selectUsers, $elementImg, $modalShowImage, $elementAction, $modalShowAction;

$ (function () {

    $formEliminar = $('#form-delete');
    
    $modalEliminar = $('#modal1');
    $modalEditar = $('#modal4');

    $selectLocation = $('#location-select');
    $selectUsers = $('#user-select');

    $formEdit = $('#form-edit');

    $elementImg = $('#verImage');

    $modalShowImage = $('#modal2');

    $elementAction = $('#verAction');

    $modalShowAction = $('#modal3');



    $.getJSON('/informes/users',function(response)
    {
        console.log(response);
        $.each(response,function(key,value)
        {
            console.log("got you");
            $selectUsers.append($("<option></option>").attr("value", value.id).text(value.name));
        });

    });

    $.getJSON('/informes/locations',function(response)
    {
        console.log(response);
        $.each(response,function(key,value)
        {
            console.log("Entro");
            $selectLocation.append($("<option></option>").attr("value", value.id).text(value.name));
        });
    });

    $('[data-delete]').on('click', function () {
        var id = $(this).data('delete');

        $formEliminar.find('[name="id"]').val(id);

        $modalEliminar.modal('open');
    });

    $('#delete-report').on('click', function () {
        event.preventDefault();
        avatarUrl = $formEliminar.attr('action');
        $.ajax({
            url: avatarUrl,
            method: 'POST',
            data: $formEliminar.serialize(),
            success: function (data) {
                console.log(data);
                if (data != "") {
                    for (var property in data) {
                        Materialize.toast(data[property], 4000);
                    }
                } else {
                    Materialize.toast("Reporte eliminado correctamente", 4000);
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

    $('[data-img]').on('click', function () {
        var path = "/images/report/"+$(this).data('img');
        $elementImg.attr("src",path);
        $modalShowImage.modal('open');
    });

    $('[data-action]').on('click', function () {
        var path = "/images/action/"+$(this).data('action');
        $elementAction.attr("src",path);
        $modalShowAction.modal('open');
    });

    $('#edit-informe').on('click', function () {
        console.log("Entre");
        var id = $(this).data('informe');
        var location_id = $(this).data('location');
        var user_id = $(this).data('user');
        var fromdate = $(this).data('fromdate');
        var todate = $(this).data('todate');

        $selectLocation.val(location_id).change();
        $selectUsers.val(user_id).change();

        $formEdit.find('[name="id"]').val(id);
        $formEdit.find('[name="fromdate"]').val(fromdate);
        $formEdit.find('[name="todate"]').val(todate);

        $selectLocation.material_select();
        $selectUsers.material_select();

        $modalEditar.modal('open');
        Materialize.updateTextFields(); // use this after change the field values
    });

    $('#save-edit').on('click', function () {
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
                    Materialize.toast("Informe modificado correctamente", 4000);
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



