<?php 
require_once("db-act.php");
if(!isset($_SESSION["user"])){
    session_start();
}
if(isset($_POST["save_edit"]) && !empty($_POST["save_edit"])){
    $message = save_edit($_SESSION['user'], $_POST["full_name"], $_POST["pass"], $_POST["new_pass"], $_POST["pass_confirm"]);
}
echo $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
    <head>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/cadastro.css">

        <title>Perfil</title>
    </head>
    <body>
       <?PHP
            include('nav-bar.php');
       ?>
        <div class="content-box">
            <?php 
                if(isset($message)){
                    echo $message;
                }
            ?>
            <section id="edit-profile">
                <div>
                    <h3>Editar Perfil</h3>
                    <form method="POST">
                        <div class="field">
                            <legend> 
                                Nome:
                            </legend>
                                <input type="text" name="full_name" required>
                            </div>
                        <div class="field">
                            <legend>
                                Email:
                            </legend>
                            <label>    
                                <input type="text" name="mail" value="<?php echo $_SESSION['user_mail'] ?>" disabled>        
                            </label>
                        </div>
                        <div class="field">
                            <legend>
                                Senha Atual:
                            </legend>
                            <label>
                                <input type="password" name="pass" required >        
                            </label>
                        </div>
                        <div class="field">
                            <legend>
                                Nova Senha:
                            </legend>
                            <label>
                                <input type="password" name="new_pass" required >        
                            </label>
                        </div>
                        <div class="field">
                            <legend>
                                Confirme a senha:
                            </legend>
                            <label>
                                <input type="password" name="pass_confirm" required >
                            </label>
                        </div>
                        <div class="field">
                            <input type="submit" value="Salvar Edição" name="save_edit">
                        </div>
                    </form>
                </div>
            </section>
    </body>
</html>