<?php
?>
<html lang="lv">
<head>
    <script src="https://code.jquery.com/jquery-3.6.2.js"
            integrity="sha256-pkn2CUZmheSeyssYw3vMp1+xyub4m+e+QK4sQskvuo4="
            crossorigin="anonymous"></script>
    <title>Reģistrēšanās</title>
</head>
<div id="Intro">
    <p>Reģistrēšanās view.</p>
</div>
<form id="signUpForm" method="post" action="../Controllers/AjaxController.php">
    <label for="username">Lietotājvārds:</label><br>
    <input type="text" id="username" name="username" maxlength="25" minlength="6"><br>
    <label for="email">E-pasts:</label><br>
    <input type="email" id="email" name="email"><br>
    <label for="password">Parole:</label><br>
    <input type="text" id="password" name="password" min="8" max="25"><br><br>
    <input type="submit" value="Iesniegt">
</form>
<script>
    $("#signUpForm").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        let form = $(this);
        let actionUrl = form.attr('action');

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
                //redirect uz homepage, tagad logged in/signed up.
            }
        });

    });
</script>
</html>