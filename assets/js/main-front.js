(function ($) {
    "use strict";
    $(document).ready(function () {
        $(document).on("submit", "#form", function (e) {
            e.preventDefault();
            var form = $(this);
            var data = new FormData(this);
            var btn_level = $(".ajax-btn").val();
            $.ajax({
                dataType: "json",
                type: "POST",
                url: 'helper/front-process.php',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $(".ajax-btn").val("Please wait...");
                    $(".ajax-btn").prop("disabled", true);
                },
                success: function (data) {
                    console.log(btn_level);
                    $(".ajax-btn").prop("disabled", false);
                    $(".ajax-btn").val(btn_level);
                    var messages = data.message;
                    $(".result").html('');
                    if (!data.success) {
                        for (var key in messages) {
                            $(".result").append("<div class='alert alert-danger'>" + data.message[key] + "</div>");
                        }
                    } else {
                        // $(".ajax-btn").prop("disabled", true);
                        $(".result").append("<div class='alert alert-success'>" + data.message + "</div>");
                        document.getElementById("form").reset();
                        if (data.redirect) {
                            setTimeout(function () {
                                window.location = data.redirect;
                            }, 3000);
                        }
                        if (data.reload) {
                            setTimeout(function () {
                                window.location.reload();
                            }, 3000);
                        }
                    }
                }
            });
        });
    });
})(jQuery);