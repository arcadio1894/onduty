var $formEdit;

$ (function () {
    $("#responsible").change(function () {
        console.log('Entre');
        var valor = $(this).val();
        console.log(valor);
        var position2 = $("#responsible option[value="+ valor +"]").data("position");
        var email2 = $("#responsible option[value="+ valor +"]").data("email");
        console.log(email2);
        $('#responsible-position').val(position2);
        $('#responsible-email').val(email2);
        Materialize.updateTextFields();
    });

    var valor = $('#responsible').val();
    var position = $("#responsible option[value="+ valor +"]").data("position");
    var email = $("#responsible option[value="+ valor +"]").data("email");
    $('#responsible-position').val(position);
    $('#responsible-email').val(email);
    Materialize.updateTextFields();



    $formEdit = $('#form-edit');

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

    $formEdit.on('submit', function () {
        event.preventDefault();
        url = $formEdit.attr('action');
        $.ajax({
            url: url,
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
                    Materialize.toast("Reporte editado correctamente", 2000);
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



