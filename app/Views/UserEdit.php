<?php

session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}
include ('Header.php');
?>
<section id="main" class="container-fluid row min-vh-100 min-vw-100">
    <div id="buffer" class="col"></div>
    <div class="col-6">
    <form id="editForm" method="post" action="../Controllers/AjaxController.php">
        <label for="username" class="form-label">Lietotājvārds:</label><br>
        <input type="text" id="username" name="username" maxlength="25" minlength="6" value="" class="form-control"><br>
        <label for="email" class="form-label">E-pasts:</label><br>
        <input type="email" id="email" name="email" minlength="3" maxlength="64" value="" class="form-control"><br>
        <label for="password" class="form-label">Jaunā parole:</label><br>
        <input type="password" id="password" name="password" minlength="8" maxlength="25" class="form-control"><br>
        <label for="passwordRepeat" class="form-label">Jaunā parole atkārtoti:</label><br>
        <input type="password" id="passwordRepeat" name="passwordRepeat" minlength="8" maxlength="25" class="form-control"><br>
        <input type="submit" value="Rediģēt" class="btn btn-primary">
        <button id="deleteButton" class="btn btn-primary border border-dark border-2">
            Dzēst kontu.
        </button>
    </form>
    </div>
    <div id="buffer2" class="col"></div>
</section>
<script>
    $(function() {
        document.getElementById("username").value = <?php echo json_encode($_POST['username']);?>;
        //document.getElementById("username").value = <?php //echo $_POST['username']?>;
        document.getElementById("email").value = <?php echo json_encode($_POST['email']);?>;
        //document.getElementById("email").value = <?php //echo $_POST['email']?>;
        document.getElementById("deleteButton").addEventListener("click",deleteUser,false);
        function deleteUser() {
            let userId = JSON.parse(<?php echo json_encode($_SESSION['userId'])?>);
            $.ajax({
                type: "POST",
                url: "../Controllers/AjaxController.php",
                data: {
                    'userId' : userId,
                    'action': 'userDelete',
                },
                dataType: "json",
                success: function (response) {
                    window.location = 'UserProfileView.php';
                    //alert("success");
                },
                error: function (response) {
                    //console.log(response);
                    alert("error");
                },
            });
        }
        $("#editForm").submit(function (e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.
            let password = $("#password").val().toString();
            let passwordRepeat = $("#passwordRepeat").val().toString();
            if(password === passwordRepeat){
                let username = $("#username").val().toString();
                let email = $("#email").val().toString();
                $.ajax({
                    type: "POST",
                    url: "../Controllers/AjaxController.php",
                    data: {
                        'username': username,
                        'email': email,
                        'newPassword' : password,
                        'action': 'userEdit',
                    },
                    //dataType: "json",
                    success: function (response) {
                        window.location = 'UserProfileView.php';
                        //alert("success");
                    },
                    error: function (response) {
                        //console.log(response);
                        alert("error");
                    },
                });
            }


        });
    });
</script>
</html>