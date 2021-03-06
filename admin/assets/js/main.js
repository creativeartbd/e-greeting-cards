(function ($) {
    "use strict";
    $(document).ready(function () {

        var ex_is_smtp = $("#is_smtp").val();
        var ex_show_host = $(".show_host");
        var ex_show_auth_details = $(".show_auth_details");

        if ( ex_is_smtp == "yes" ) {
            ex_show_host.show();
            ex_show_auth_details.css("display", "flex");
        }

        
        // Show/Hide is smtp option
        $(document).on("change", "#is_smtp", function (e) {
            var is_smtp = $(this).val();
            var show_host = $(".show_host");
            var show_auth_details = $(".show_auth_details");

            if ("yes" === is_smtp) {
                show_host.show();
                show_auth_details.css("display", "flex");
            } else if ("no" === is_smtp) {
                show_host.hide();
                show_auth_details.hide();
            }
        });

        // Output the design
        $(document).on("click", ".output-desing", function (e) {
            e.preventDefault();

            var form = document.querySelector(".output-desing");
            var form_name = form.dataset.form;
            var data = new FormData(document.getElementById("form"));
            data.append('form', form_name);

            $.ajax({
                dataType: "html",
                type: "POST",
                url: 'helper/process.php',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $(".output-design").val("Please wait...");
                },
                success: function (data) {
                    //$(".output-design").html("");
                    $(".output-design").html(data);
                }
            });
        });

        // $(document).on("submit", "#design_form", function (e) {
        //     e.preventDefault();
        //     var data = new FormData(this);
        //     $.ajax({
        //         dataType: "html",
        //         type: "POST",
        //         url: 'helper/process.php',
        //         data: data,
        //         processData: false,
        //         contentType: false,
        //         beforeSend: function () {
        //             $(".result_design").val("Please wait...");
        //         },
        //         success: function (data) {
        //             $(".result_design").val('');
        //             $(".result_design").html(data);
        //         }
        //     });
        // });

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
                        $(".ajax-btn").prop("disabled", true);
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