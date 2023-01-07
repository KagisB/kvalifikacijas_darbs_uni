<html lang="lv">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="//code.jquery.com/jquery-3.6.2.js"
            integrity="sha256-pkn2CUZmheSeyssYw3vMp1+xyub4m+e+QK4sQskvuo4="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
</head>
<section class="header">
    <div class="container">
        <nav id="linkMenu" class="nav justify-content-end">
            <a class="nav-link col-sm" href="Index.php">Atpakaļ uz galveno lapu</a>
            <a class="nav-link col-sm" href="ParkingLotList.php">Autostāvlaukumi</a>
            <a id="logIn" class="nav-link visible col-sm" href="Login.php">Ieiet sistēmā</a>
            <a id="logOut" class="nav-link col-sm invisible">Izrakstīties no sistēmas</a>
            <a id="userProfile" class="nav-link col-sm invisible" href="UserProfileView.php">Lietotāja profils</a>
        </nav>
    </div>
</section>
<script>
    $(function(){

        $.ajax({
            type: "POST",
            url:"../Controllers/AjaxController.php",
            async:true,
            data: "action=userGet",
            success: function(data){
                let user = JSON.parse(data);
                if(user){
                    if(user.status>0){
                        //$('#userProfile').setAttribute("href","UserProfileView.php");
                        //document.getElementById("userProfile").href += "?userId="+user.id;
                        $('#logIn').removeClass(" visible").addClass(" invisible");
                        $('#logOut').removeClass(" invisible").addClass(" visible");
                        $('#userProfile').removeClass(" invisible").addClass(" visible");
                    }
                }
            }
        });
    });
</script>

