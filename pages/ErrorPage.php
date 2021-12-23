<?php

include 'templates/Header.php';
?>


<div id="base-text">
    <div style="align-items: center">
        <h2 class="header">Er is een error opgetreden!</h2>
        <h3><?php echo $errorName; ?></h3>
        <p>Mocht dit vaker voor komen geef dan dit nummer door aan de klanten service :</p>
        <code style="justify-content: center;" id="errorNumber"></code>
    </div>

</div>


<script>
    makeid();
    function makeid() {
        var res = new Date().toISOString().slice(0,10).replace(/-/g,"");
        var result           = res + '-';
        var characters       = '0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < 15; i++ ) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        document.getElementById('errorNumber').innerText = result;
    }
</script>