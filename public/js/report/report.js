var $formEdit, $modalEditar;

var $selectLocation, $selectUsers;

$ (function () {

    $formEdit = $('#form-edit');
    
    $modalEditar = $('#modal1');

    $selectLocation = $('#location-select');
    $selectUsers = $('#user-select');

    $selectLocation.material_select();
    $selectUsers.material_select();

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
            console.log("got you");
            $selectLocation.append($("<option></option>").attr("value", value.id).text(value.name));
        });
    });


    
    $('#edit-informe').on('click', function () {
        var id = $(this).data('informe');
        var location = $(this).data('location');
        var user = $(this).data('user');
        var from_date = $(this).data('fromdate');
        var to_date = $(this).data('todate');

        $selectUsers.val(user).change();
        $selectLocation.val(location).change();

        $formEdit.find('[name="id"]').val(id);
        $formEdit.find('[name="fromdate"]').val(from_date);
        $formEdit.find('[name="todate"]').val(to_date);

        $modalEditar.modal('open');
        Materialize.updateTextFields(); // use this after change the field values

        $selectUsers.material_select();
        $selectLocation.material_select();
    });

    $('#save-informe').on('click', function () {
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
});



