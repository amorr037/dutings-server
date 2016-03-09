/**
 * Created by cruiz on 3/7/16.
 */
function login(){
    $.ajax({
        url: '/auth/login/',
        type: 'POST',
        // Form data
        data: function(){
            var data = new FormData();
            data.append('email', $("#login-username").val());
            data.append('password', $("#login-password").val());
            return data;
        }(),
        success: function (data) {
            var obj = JSON.parse(data);
            console.log(obj);
            alert("Success!");
        },
        error: function (data) {
            console.log(data);
            $("#login-alert").html(
                '<div id="login-alert-message"><a class="close" onclick=hideAlert()>×</a><span>'
                +data.responseText+
                '</span></div>').show();
        },
        complete: function () {

        },
        cache: false,
        contentType: false,
        processData: false
    });
}
function hideAlert(){
    $("#login-alert").hide();
}