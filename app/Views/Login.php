<?php
session_start();
if (isset($_SESSION['logInStatus']) && $_SESSION['logInStatus'] === true) {
    header ("Location: Index.php");
    die();
}
include ('Header.php');
?>
<section id="main" class="container-fluid row min-vh-100 min-vw-100">
    <div id="buffer" class="col"></div>
    <div class="col-6">
        <form id="signInForm" method = post action = ../Controllers/AjaxController.php class="">
                <label for="username" class="form-label">Lietotājvārds:</label><br>
                <input type="text" id="name" name="name" required class="form-control" size="8"><br>
                <label for="password" class="form-label">Parole:</label><br>
                <input type="password" id="password" name="password" required class="form-control" size="8"><br>
            <input type="submit" name="Ieiet" class="btn btn-primary">
            <div id="ref">
                Neesi reģistrējies? <a href="Signup.php">Piereģistrējies šeit</a>
            </div>
        </form>
    </div>
    <div id="buffer2" class="col"></div>
</section>
</html>
<script>
    $("#signInForm").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        let name = $("#name").val();
        let password = $("#password").val().toString();
        $.ajax({
            type: "POST",
            url: "../Controllers/AjaxController.php",
            data: {
                'username': name,
                'password': password,
                'action' : 'userLogIn',
            },
            dataType: "json",
            success: function(response)
            {
                //console.log(response);
                //alert("success");
                window.location = 'Index.php';
            },
            error: function(response)
            {
                //console.log(response);
                alert("error")
            },
        });

    });
</script>