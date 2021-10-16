<?php

function save_edit($uid, $full_name, $actpass, $newpass, $cnfpass){
    require "db-connect.php";
    
    echo $full_name;
    echo $actpass;
    echo $newpass;
    echo $cnfpass;

    $user_name = $db->prepare("SELECT mail FROM obra_na_medida.users WHERE uid=:uid");
    $user_name->execute(array(':uid'=>$uid));
    $row=$user_name->fetch(PDO::FETCH_ASSOC);	
        
    
    try {	
        if(!empty($row["mail"])){ // existe o user
            $select_password = $db->prepare("SELECT `uid`, `name`, `password` FROM obra_na_medida.users WHERE mail=:umail");
            $select_password->execute(array(':umail'=>$row["mail"]));
            $row=$select_password->fetch(PDO::FETCH_ASSOC);	
            if(password_verify($actpass, $row["password"])){

                if(!empty(trim($full_name))){
                    if($full_name != $row["name"]){
                        preg_match("/^[a-zA-Z][a-zA-Z ]+$/", $full_name, $valid_name);
                        if(!empty($valid_name)){
                            $valid_name = TRUE;
                        } else {
                            $form_error[] = "Campo Nome possui caracteres inválidos";    
                        }
                    } else {
                        $form_error[] = "O nome é igual o atual";    
                    }
                } else {
                    $form_error[] = "Campo Nome está vazio";
                }

                if(empty($new_pass) && empty($cnfpass)){
                    $save[] = "name";
                } elseif(empty($new_pass) || empty($cnfpass)){
                    $save[] = "error";
                } else {
                    if($newpass === $cnfpass){
                        $valid_pass = TRUE;
                        $save[] = "pass";
                    } else {
                        $form_error[] = "As credenciais não estão corretas"; // Nesse caso é a senha
                        $save[] = "error";
                    }
                }

                $error = array_search("error", $save);

            } else {
                $form_error[] = "As credenciais não estão corretas";
            }

            var_dump($form_error);
            var_dump($save);
            exit();

            if(empty($error)){
                foreach($save as $act)
                    switch($act){
                        case "name":
                            $save_on_db = $db->prepare("UPDATE obra_na_medida.users SET `name`=:name)");
                            if($save_on_db->execute(array(':name'=>$full_name))){
                                $alerta = "Dados atualizados";
                            }
                            print("AKHJOIQNDFMOQ");
                            exit();
                            break; 
                        case "pass":
                            $newpass = password_hash($newpass, PASSWORD_DEFAULT); // hash simples na senha
                            $save_on_db = $db->prepare("UPDATE obra_na_medida.users SET `password`=:upassword)");
                            if($save_on_db->execute(array(':upassword'=>$new_password))){
                                $alerta = "Dados atualizados";
                            } 
                            print("AKHJOIQNDFMOQ 2");
                            exit();
                            break;
                    }
                }  
            } else {
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
                    return $msg;
                } else{
                    if(isset($alerta)){
                        return $alerta;
                    }
                }
            }
        } catch(PDOException $e) {
        echo $e->getMessage();
    }
}

?>