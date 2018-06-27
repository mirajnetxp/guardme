$(document).ready(function () {
    // Checking unread message

    // setInterval(function () {
        $.ajax({
            url: "https://guarddme.com/api/notification/unread",
            method: "GET",
            dataType: 'json',
            global: false,
            success: function (d) {

                if (d.count > 0) {
                    $(".fa-bell-o").removeClass('fa-bell-o').addClass('fa-bell')
                    $(".badge").removeClass('hidden');
                    $(".badge").html(d.count);

                    $(".fa-bell").click(function () {

                        $.ajax({
                            url: "https://guarddme.com/api/notification/mark/as/read",
                            method: "GET",
                            dataType: 'json',
                            global: false,
                            success: function (d) {
                                if (d.code == 101) {
                                    $(".fa-bell").removeClass('fa-bell').addClass('fa-bell-o')
                                    $(".badge").addClass('hidden');
                                }
                            }
                        });
                    })

                } else {
                    $(".fa-bell").removeClass('fa-bell').addClass('fa-bell-o')
                }

                $("#notification-div").html('')
                $.each(d.notifications, function (i, v) {
                    $("#notification-div").append("<li><a href=\"#\">" + v + "</a></li>")
                })
                $("#notification-div").append("<li><a style='color: #8BC34A;font-weight: bold;' href=\"#\">See all notifications</a></li>")
            }
        });

    // }, 2000)


});