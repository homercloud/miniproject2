<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema XPTO - Excluir Usuário</title>
</head>
<body>
<?php
    include("conexaobd.php");
    $sql_user = mysqli_query($conexao,"select 1 from usuarios;");
    if(mysqli_num_rows($sql_user) > 0){
        $achou_user = 0;
        if(!isset($_POST['exc_id']))
            echo "<p><center><h3>Acesso não autorizado.</h3>&nbsp;&nbsp;<a href='acesso.php'>Voltar</a></center></p>";
        else
            $sql_user = mysqli_query($conexao,"select 1 from usuarios where id='".$_POST['exc_id']."';");
        if(mysqli_num_rows($sql_user) == 0)
            echo "<p><center><h3>Registro de usuário não encontrado.</h3>&nbsp;&nbsp;<a href='acesso.php'>Voltar</a></center></p>";
        else{
            if(!isset($_SESSION)) session_start();
            if(empty($_SESSION['id']))
                echo "
                <p><center><h3>Usuário não logado.</h3>&nbsp;&nbsp;<a href='login.php'>Voltar</a></center></p>";
            else{
                mysqli_query($conexao,"delete from usuarios where id='".$_POST['exc_id']."';") or die(mysqli_error());
                echo "<p><center><h3>Usuário excluído com sucesso.</h3>&nbsp;&nbsp;<a href='acesso.php'>Voltar</a></center></p>";
            }
        }
    }else{
        mysqli_close($conexao);
        header("location: incluir.php");
    }
    mysqli_close($conexao);
?>
</body>
</html>
