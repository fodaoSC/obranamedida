
<?php 
if(!isset($_SESSION["user"])){
    session_start();
}
?>
<!DOCTYPE html>
<html>
    <head>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/cadastro.css">

        <title>Projetos</title>
    </head>
    <body>
       <?PHP
            include('nav-bar.php');
       ?>
        <div class="content-box">
        <?php
        if(isset($_SESSION["user"])){
            echo "logou";
        } else {
            echo "deslogou";
        }
            
        ?>    
        </div>
    </body>
</html>
