<?php
/*
 * Izveidot input, lai ņem visas rezervācijas mēneša laikā, un neļauj ievadīt laikus, kas neiekļaujas brīvajos laikos.
 * Vajadzētu limitēt laikus ,tas ir, intervāli būtu ik pa pusstundai,stundai utt. Jo citādāk nevarēs izmantot plugins/citas lietas.
 * https://github.com/jannicz/appointment-picker#readme
 * */
session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}
//echo $_POST['lotId'];
include ('Header.php');
?>

