<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema XPTO - Editar Usuário</title>
</head>
<body>
<?php
    include("conexaobd.php");
    $sql_user = mysqli_query($conexao,"select 1 from usuarios;");
    $logado = 0;
    $aviso = "";
    if(mysqli_num_rows($sql_user) > 0){
        $achou_user = 0;
        if(!isset($_POST['alt_id']) && !isset($_POST['alt_id2']))
            echo "<p><center><h3>Acesso não autorizado.</h3>&nbsp;&nbsp;<a href='acesso.php'>Voltar</a></center></p>";
        elseif(isset($_POST['alt_id'])){
            $sql_user = mysqli_query($conexao,"select * from usuarios where id='".$_POST['alt_id']."';");
            $achou_user = mysqli_num_rows($sql_user);
        }elseif(isset($_POST['alt_id2'])){
            $sql_user = mysqli_query($conexao,"select * from usuarios where id='".$_POST['alt_id2']."';");
            $achou_user = mysqli_num_rows($sql_user);
        }
        if($achou_user == 0)
            echo "<p><center><h3>Registro de usuário não encontrado.</h3>&nbsp;&nbsp;<a href='acesso.php'>Voltar</a></center></p>";
        else{
            if(!isset($_SESSION)) session_start();
            if(empty($_SESSION['id']))
                echo "
                <p><center><h3>Usuário não logado.</h3>&nbsp;&nbsp;<a href='login.php'>Voltar</a></center></p>";
            else{
                $r_user = mysqli_fetch_array($sql_user);
                if(isset($_POST['alt_id2'])){
                    if($_POST['email'] != $r_user['email']){
                        $sql_login = mysqli_query($conexao,"select 1 from usuarios where email='".$_POST['email']."' and id!='".$_POST['alt_id2']."';");
                        if(mysqli_num_rows($sql_login) > 0){
                            $aviso = "&nbsp;&nbsp;<font color='red'><b>Já existe outro usuário com este login.</b></font>";
                            $logado = 1;
                        }
                    }
                    if($aviso == ""){
                        $senha2 = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                        mysqli_query($conexao,"update usuarios set nome='".$_POST['nome']."',email='".$_POST['email']."',senha='".$senha2."' where id='".$_POST['alt_id2']."';") or die(mysqli_error());
                        echo "<p><center><h3>Usuário editado com sucesso.</h3>&nbsp;&nbsp;<a href='acesso.php'>Voltar</a></center></p>";
                    }
                }else
                    $logado = 1;
            }
        }
    }else{
        mysqli_close($conexao);
        header("location: incluir.php");
    }
    if($logado == 1){
        $senha = "";
        if(isset($_POST['alt_id2'])){
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $alt_id2 = $_POST['alt_id2'];
        }else{
            $nome = $r_user['nome'];
            $email = $r_user['email'];
            $alt_id2 = $_POST['alt_id'];
        }
        echo "<p><h3>Olá, ".$_SESSION['nome']."!</h3></p>";
        echo "<p><h2>Edição de dados do usuário</h2></p>";?>
        <form method="POST" action="">
            <p><label for="nome">Nome</label>
            <input type="text" name="nome" value="<?=$nome;?>" size="40" maxlength="100" required></p>
            <p><label for="email">Login</label>
            <input type="email" name="email" value="<?=$email;?>" size="30" maxlength="45" required><?=$aviso;?></p>
            <p><label for="senha">Senha</label>
            <input type="password" name="senha" value="<?=$senha;?>" size="20" maxlength="16" required></p>
            <p><button type="submit">Alterar</button></p>
            <input type="hidden" name="alt_id2" value="<?=$alt_id2;?>">
        </form>
        <p><form method="POST" action="acesso.php"><p><button type="submit">Cancelar</button></p></form></p>
        <?php
    }
    mysqli_close($conexao);
?>
</body>
</html>
