$(document).ready(function() {
    $("#tool-form").submit(function(e) {
        var that = this;
        var preloader = $("#tool-form-preloader");

        $(this).fadeTo(1000, 0.5);
        $(preloader).fadeIn(1000);

        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");

        $.ajax({
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data, textStatus, jqXHR) {
                $(that).fadeTo(1000, 1.0);
                $(preloader).fadeOut(1000);

                $("#tool-results")
                    .hide()
                    .html(data)
                    .fadeIn(1000);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                $(that).fadeTo(1000, 1.0);
                $(preloader).fadeOut(1000);
            }
        });

        e.preventDefault(); //STOP default action
        e.unbind(); //unbind. to stop multiple form submit.
    });
});