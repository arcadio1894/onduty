var $formEliminar, $modalEliminar;

var $selectLocation, $selectUsers, $elementImg, $modalShowImage, $elementAction, $modalShowAction;

$ (function () {

    $formEliminar = $('#form-delete');
    
    $modalEliminar = $('#modal1');

    $selectLocation = $('#location-select');
    $selectUsers = $('#user-select');

    $elementImg = $('#verImage');

    $modalShowImage = $('#modal2');

    $elementAction = $('#verAction');

    $modalShowAction = $('#modal3');

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
            data: $formEliminar.serialize()
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
});



