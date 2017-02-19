/**
 * Created by USUARIO on 17/02/2017.
 */

var $avatarInput, $avatarImage, $avatarForm;

$(function () {
    $avatarImage = $('#avatarImage');
    $avatarInput = $('#avatarInput');
    $avatarForm = $('#avatarForm');

    $avatarImage.on('click', function () {
        $avatarInput.click();
    });

    avatarUrl = $avatarForm.attr('action');

    $avatarInput.on('change', function () {
        // Peticion ajax

        var formData = new FormData();
        formData.append('photo', $avatarInput[0].files[0]);

        $.ajax({
            url: avatarUrl+'?'+$avatarForm.serialize(),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false
        })
        .done(function (data) {
            if (data.success)
                $avatarImage.attr('src', '/images/users/'+data.path+'?'+ new Date().getTime());
        })
        .fail(function () {
            alert('La imagen subida no tiene el formato correcto');
        });
        
    });
});
