<?php
$valid_form = TRUE; // Começa como válido até ter algum erro

require_once "db-connect.php";

if(isset($_POST['register']) && !empty($_POST['register'])){
    if(empty($_POST['full_name'])){
        $form_error[] = 'Campo Nome está vazio';
    } else {
        $fname = $_POST['full_name'];
    }

    if(empty($_POST['mail'])){
        $form_error[] = 'Campo email está vazio';
    } else{
        if(filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
            $email = $_POST['mail'];
        }  else{
            $form_error[] = 'Email inválido';
        }
    }

    if(empty($_POST['pass']) || empty($_POST['pass_confirm'])){
        $form_error[] = 'Preencha e confirme o campo de senha';
    } else{
        if($_POST['pass'] != $_POST['pass_confirm']){
            $form_error[] = 'As senhas não são iguais';
        }  else {
            $password = $_POST['pass'];
        }
    }

    if(empty($form_error)){
        // Verifica se o email já existe
        try {	
            $select_mail = $db->prepare("SELECT mail FROM obra_na_medida.users WHERE mail=:umail");
            
            $select_mail->execute(array(':umail'=>$email));
            $row=$select_mail->fetch(PDO::FETCH_ASSOC);	
            
            if($row["mail"] == $email){ // O Email já existe no banco, então não vai cadastrar novamente.
                $valid_form = FALSE;
            }

            if($valid_form) {   
                $new_password = password_hash($password, PASSWORD_DEFAULT); // hash simples na senha
                
                $save_on_db = $db->prepare("INSERT INTO obra_na_medida.users (`name`, mail, `password`) VALUES (:fname, :umail, :upassword)");
                
                if($save_on_db->execute(array(	':fname'	=>$fname, 
                                                ':umail'	=>$email, 
                                                ':upassword'=>$new_password))){
                    $registered = TRUE;
                }
            } else {
                $registered = FALSE;
            }

            // Posteriormente colocar função de confirmação via EMAIL. As duas mensagens são iguais para evitar account enumeration.
            if(isset($registered)){
                if($registered){
                    $alerta = "Email cadastrado com sucesso!";
                } else {
                    if(isset($valid_form)){
                        $alerta = "Email cadastrado com sucesso!";
                    }
                }
            }
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
} else {
    if(isset($_POST['login']) && !empty($_POST['login'])){
        if(empty($_POST['mail_login'])){
            $form_error[] = 'Campo email está vazio';
        } else {
            $email = $_POST['mail_login'];
        }

        if(empty($_POST['pass_login'])){
            $form_error[] = "As credenciais de acesso não estão corretas";
        } else {
            $pass = $_POST['pass_login'];
        }

        if(empty($form_error)){
            // Verifica se o email existe
            try {	
                $select_mail = $db->prepare("SELECT mail FROM obra_na_medida.users WHERE mail=:umail");
                
                $select_mail->execute(array(':umail'=>$email));
                $row=$select_mail->fetch(PDO::FETCH_ASSOC);	
                
                if($row["mail"] == $email){ // O Email é valido, então verifica a senha
                    $select_password = $db->prepare("SELECT `uid`, `mail`, `password` FROM obra_na_medida.users WHERE mail=:umail");
                    $select_password->execute(array(':umail'=>$email));
                    $row=$select_password->fetch(PDO::FETCH_ASSOC);	
                    if(password_verify($pass, $row["password"])){
                        session_start(); 
                        $_SESSION['user'] = $row["uid"];
                        $_SESSION['user_mail'] = $row["mail"];
                    } else {
                        $form_error[] = "As credenciais de acesso não estão corretas";
                    }
                } else {
                    $form_error[] = "As credenciais de acesso não estão corretas";
                }
    
               
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
        
    }
}




?>

<!DOCTYPE html>
<html>
    <head>
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/cadastro.css">

        <title>Obra na Medida</title>
    </head>
    <body>
       <?PHP
            include('nav-bar.php');
       ?>
       <?php
            if(!empty($form_error)){
                foreach($form_error as $error){
                    if(empty($msg)){
                        $msg = 'Error: ';
                        $glue = '';
                    } else {
                        $glue = ', ';
                    }
                    $msg = $msg . $glue . $error;
                }
                ?>
                    <div class="alert-box">
                        <div class="alert error-alert">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                            <?php echo $msg;
                            ?>
                        </div> 
                    </div>
                    <?php
            } else{
                if(isset($alerta)){
                    ?>
                    <div class="alert-box">
                        <div class="alert">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                            <?php echo $alerta;
                            ?>
                        </div> 
                    </div>
                    <?php
                }
            }
        ?>
        <div class="content-box">
            <section id="login">
                <div>
                    <h3>Já possui uma conta?</h3>
                    <form method="POST">
                        <div class="field">
                            <legend>
                                Email:
                            </legend>
                            <label>
                                <input type="text" name="mail_login" required>
                            </label>
                        </div>
                        <div class="field">
                            <legend>
                                Senha:
                            </legend>    
                            <label>
                                <input type="password" name="pass_login" required>    
                            </label>
                        </div>
                        <div class="field">
                            <label>
                                <input type="submit" value="Entrar" name="login">
                            </label>
                        </div>
                    </form>
                </div>
            </section>
            <section id="cadastro">
                <div>
                    <h3>Crie uma conta*:</h3>
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
                                <input type="text" name="mail" required>        
                            </label>
                        </div>
                        <div class="field">
                            <legend>
                                Senha:
                            </legend>
                            <label>
                                <input type="password" name="pass" required >        
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
                            <input type="submit" value="Cadastrar" name="register">
                        </div>
                    </form>
                </div>
            </section>
        </div>
        <footer></footer>
    </body>
    <!-- <script src="validar-cadastro.js"></script> -->
    <script>
        if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
        }
    </script>
</html>