<?php

include ('Header.php');
?>
<section id="main" class="container-fluid row min-vh-100 min-vw-100">
    <div id="buffer" class="col"></div>
    <div class="col-6">
        <form id="signUpForm" method="post" action="../Controllers/AjaxController.php">
            <label for="username" class="form-label">Lietotājvārds:</label><br>
            <input type="text" id="username" name="username" minlength="6" maxlength="25" class="form-control" required><br>
            <label for="email" class="form-label">E-pasts:</label><br>
            <input type="email" id="email" name="email" minlength="3" maxlength="64" class="form-control" required><br>
            <label for="password" class="form-label">Parole:</label><br>
            <span id="emailHelp" class="form-text">Vismaz 1 lielais burts, 1 cipars un 1 mazais burts, minimums 8 simboli</span>
            <input type="password" id="password" name="password" minlength="8" maxlength="25" class="form-control" required><br><br>
            <label for="passwordRepeat" class="form-label">Parole atkārtoti:</label><br>
            <input type="password" id="passwordRepeat" name="passwordRepeat" minlength="8" maxlength="25" class="form-control" required><br>
            <input type="submit" value="Reģistrēties" class="btn btn-primary border border-dark border-2">
            <div id="ref">
                Jau esi reģistrējies? <a href="Login.php">Ieej sistēmā šeit</a>
            </div>
        </form>
    </div>
    <div id="buffer2" class="col"></div>
</section>

<script>
    $("#signUpForm").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        let username = $("#username").val().toString();
        let email = $("#email").val().toString();
        let password = $("#password").val().toString();
        let passwordRepeat = $("#passwordRepeat").val().toString();
        if(password === passwordRepeat){
            $.ajax({
                type: "POST",
                url: "../Controllers/AjaxController.php",
                data: {
                    'username': username,
                    'email': email,
                    'password': password,
                    'action' : 'userSignUp',
                },
                success: function(data)
                {
                    window.location = 'Login.php';
                    //redirect uz homepage, tagad logged in/signed up.
                }
            });
        }
        else alert("Paroles nesakrīt!");
    });
</script>
</html>