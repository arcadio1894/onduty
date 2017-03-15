var $formRegister;

$ (function () {
    
    $("#responsible").change(function () {
        var valor = $(this).val();
        var position = $("#responsible option[value="+ valor +"]").data("position");
        var email = $("#responsible option[value="+ valor +"]").data("email");
        $('#responsible-position').val(position);
        $('#responsible-email').val(email);
        Materialize.updateTextFields();
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview-image').attr('src', e.target.result);
            };
            $('#preview-image').removeAttr("style");
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image").change(function(){
        $('#preview-image').hide();
        readURL(this);
    });

    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview-action').attr('src', e.target.result);
            };

            $('#preview-action').removeAttr("style");
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image-action").change(function(){
        $('#preview-action').hide();
        readURL2(this);
    });

    $formRegister = $('#form-register');

    $formRegister.on('submit', function () {
        event.preventDefault();
        url = $formRegister.attr('action');
        $('#save-report').prop('disabled', true);
        $.ajax({
            url: url,
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (data) {
                //$('#save-report').prop('disabled', false);
                console.log(data);
                if (data != "") {
                    for (var property in data) {
                        Materialize.toast(data[property], 4000);
                    }
                } else {
                    Materialize.toast("Reporte registrado correctamente", 4000);
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
                $('#save-report').prop('disabled', false);
            },
            error: function (data) {
                console.log("CZFDFDSF");
                // Render the errors with js ...
            }

        })
    });
});



