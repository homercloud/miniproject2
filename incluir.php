<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema XPTO - Novo Usuário</title>
</head>
<body>
<?php
    include("conexaobd.php");
    $sql_user = mysqli_query($conexao,"select 1 from usuarios;");
    $stop = 0;
    $titulo = "Preencha os dados do ";
    $aviso = "";
    if(mysqli_num_rows($sql_user) > 0){
        if(!isset($_SESSION)) session_start();
        if(empty($_SESSION['id'])){
            echo "<p><center><h3>Usuário não logado.</h3>&nbsp;&nbsp;<a href='login.php'>Voltar</a></center></p>";
            $stop = 1;
        }else{
            $sql_adm = mysqli_query($conexao,"select min(id) from usuarios;");
            $r_adm = mysqli_fetch_array($sql_adm);
            if($_SESSION['id'] > $r_adm[0]){
                echo "<p><center><h3>Área Restrita para o usuário master.</h3>&nbsp;&nbsp;<a href='acesso.php'>Voltar</a></center></p>";
                $stop = 1;
            }
        }
        if(isset($_POST['email'])){
            $sql_login = mysqli_query($conexao,"select 1 from usuarios where email='".$_POST['email']."';");
            if(mysqli_num_rows($sql_login) > 0)
                $aviso = "&nbsp;&nbsp;<font color='red'><b>Já existe outro usuário com este login.</b></font>";
        }
        $titulo.= "novo usuário (básico)";
    }else
        $titulo.= "primeiro usuário (master)";
        
    if(isset($_POST['senha']) && $aviso==""){
        $senha2 = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        mysqli_query($conexao,"insert into usuarios(nome,email,senha) values('".$_POST['nome']."','".$_POST['email']."','$senha2');") or die(mysqli_error());
        $pagina = "login";
        if(!empty($_SESSION['id']))
            $pagina = "acesso";
        echo "<p><center><h3>Usuário incluído com sucesso.</h3>&nbsp;&nbsp;<a href='".$pagina.".php'>Voltar</a></center></p>";
        $stop = 1;
    }
    mysqli_close($conexao);
    if($stop == 0){
        $nome = "";
        $email = "";
        $senha = "";
        $cancelar = "";
        if(isset($_POST['nome'])){
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
        }
        if(!empty($_SESSION['nome'])){
            echo "<p><h3>Olá, ".$_SESSION['nome']."!</h3></p>";
            $cancelar = "<p><form method='POST' action='acesso.php'><p><button type='submit'>Cancelar</button></p></form></p>";
        }
        echo "<p><h2>".$titulo."</h2></p>";?>
        <form method="POST" action="">
            <p><label for="nome">Nome</label>
            <input type="text" name="nome" value="<?=$nome;?>" size="40" maxlength="100" required></p>
            <p><label for="email">Login</label>
            <input type="email" name="email" value="<?=$email;?>" size="30" maxlength="45" required><?=$aviso;?></p>
            <p><label for="senha">Senha</label>
            <input type="password" name="senha" value="<?=$senha;?>" size="20" maxlength="16" required></p>
            <p><button type="submit">Incluir</button></p>
        </form>
        <?php
        echo $cancelar;
    }
?>
</body>
</html>
