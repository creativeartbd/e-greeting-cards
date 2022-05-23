(function ($) {
    "use strict";
    $(document).ready(function () {

        // $(document).on("change", ".choose-domain", function (e) {
        //     var form = document.querySelector(".choose-domain");
        //     var form_name = form.dataset.form;
        //     var domain_id = $(this).val();
        //     var data = $("#design_form").serialize();
        //     console.log(data);
        //     $.ajax({
        //         dataType: "html",
        //         type: "POST",
        //         url: 'helper/process.php',
        //         data: data,
        //         beforeSend: function () {
        //             $(".form_name").val("Please wait...");
        //         },
        //         success: function (data) {
        //             $(".form_name").val('');
        //             $(".result_design").html(data);
        //         }
        //     });
        // });

        $(document).on("submit", "#design_form", function (e) {
            e.preventDefault();
            var data = new FormData(this);
            $.ajax({
                dataType: "html",
                type: "POST",
                url: 'helper/process.php',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $(".result_design").val("Please wait...");
                },
                success: function (data) {
                    $(".result_design").val('');
                    $(".result_design").html(data);
                }
            });
        });

        $(document).on("click", ".delete-btn", function (e) {
            var delete_id = $(this).data("delete-id");
            var formname = $(this).data("form");
            var btn_level = $(".delete-btn").val();
            if (confirm("Are you sure to delete?")) {
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: 'helper/process.php',
                    data: {
                        delete_id: delete_id,
                        form: formname
                    },
                    beforeSend: function () {
                        $(".delete-btn").val("Please wait...");
                        $(".delete-btn").prop("disabled", true);
                    },
                    success: function (data) {
                        $(".delete-btn").prop("disabled", false);
                        $(".delete-btn").val(btn_level);
                        var messages = data.message;
                        $(".result").html('');
                        if (!data.success) {
                            for (var key in messages) {
                                $(".result").append("<div class='alert alert-danger'>" + data.message[key] + "</div>");
                            }
                        } else {
                            // $(".ajax-btn").prop("disabled", true);
                            $(".result").append("<div class='alert alert-success'>" + data.message + "</div>");
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
            }
        });

        $(document).on("submit", "#form", function (e) {
            e.preventDefault();
            var form = $(this);
            var data = new FormData(this);
            var btn_level = $(".ajax-btn").val();
            $.ajax({
                dataType: "json",
                type: "POST",
                url: 'helper/process.php',
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