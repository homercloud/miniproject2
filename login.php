<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema XPTO - Login</title>
</head>
<body>
    <?php
    include("conexaobd.php");
    $sql_user = mysqli_query($conexao,"select 1 from usuarios;");
    $aviso = "";
    $aviso2 = "";
    if(mysqli_num_rows($sql_user) > 0){
        if(isset($_POST['email'])){
            $sql_senha = mysqli_query($conexao,"select * from usuarios where email='".$_POST['email']."';");
            $r_senha = mysqli_fetch_array($sql_senha);
            if(mysqli_num_rows($sql_senha) == 0)
                $aviso = "&nbsp;&nbsp;<font color='red'><b>Este login não está cadastrado.</b></font>";
            else{
                if(password_verify($_POST['senha'], $r_senha['senha'])){
                    if(isset($_SESSION)) session_destroy();
                    session_start();
                    $_SESSION['id'] = $r_senha['id'];
                    $_SESSION['nome'] = $r_senha['nome'];
                    $_SESSION['email'] = $r_senha['email'];
                    mysqli_close($conexao);
                    header("location: acesso.php");
                }else
                    $aviso2 = "&nbsp;&nbsp;<font color='red'><b>A senha deste login não confere.</b></font>";
            }
        }
        mysqli_close($conexao);
    }else{
        mysqli_close($conexao);
        header("location: incluir.php");
    }
    $email = "";
    $senha = "";
    if(isset($_POST['email'])){
        $email = $_POST['email'];
        $senha = $_POST['senha'];
    }
    ?>
    <p><h2>Bem-vindo(a)</h2></p>
    <form method="POST" action="">
        <p><label for="email">Login</label>
        <input type="email" name="email" value="<?=$email;?>" size="30" maxlength="45" required><?=$aviso;?></p>
        <p><label for="senha">Senha</label>
        <input type="password" name="senha" value="<?=$senha;?>" size="20" maxlength="16" required><?=$aviso2;?></p>
        <p><button type="submit">Acessar</button></p>
    </form>
</body>
</html>
