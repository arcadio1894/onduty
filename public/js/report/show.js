var $formRegister;

$ (function () {
    
    $("#responsible").change(function () {
        var valor = $(this).val();
        var position = $("#responsible option[value="+ valor +"]").data("position");
        var email = $("#responsible option[value="+ valor +"]").data("email");
        var idPosition = $("#responsible option[value="+ valor +"]").data("idposition");


        var department="";

        $.getJSON('../../department/user/'+idPosition, function(response)
        {
            if (response.name != null){
                department = response.name;
                $('#responsible-department').val(department);
                Materialize.updateTextFields();
            } else {
                $('#responsible-department').val("Pendiente de asignar");
                Materialize.updateTextFields();
            }

        });

        $('#responsible-position').val(position);
        $('#responsible-email').val(email);
        $('#responsible-department').val(department);
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
        $('#line-loader').show();
        //$('#circle-loader').show();
        $.ajax({
            url: url,
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (data) {
                //$('#save-report').prop('disabled', false);
                console.log(data);
                $('#line-loader').hide();
                //$('#circle-loader').hide();
                if (data != "") {
                    for (var property in data) {
                        Materialize.toast(data[property], 4000);
                    }
                    $('#save-report').prop('disabled', false);
                } else {
                    Materialize.toast("Reporte registrado correctamente", 2000);
                    setTimeout(function(){
                        location.href = $('#linkBackToList').attr('href');
                    }, 1800);
                }

            },
            error: function (data) {
                // Render the errors with js ...
                console.log(data);
            }

        })
    });
});



