<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema XPTO - Principal</title>
</head>
<body>
<?php
    if(isset($_POST['logout'])){
        if($_POST['logout'] == 1){
            session_destroy();
            header("location: login.php");
        }
    }
    include("conexaobd.php");
    $sql_user = mysqli_query($conexao,"select * from usuarios order by id asc;");
    $logado = 0;
    if(mysqli_num_rows($sql_user) > 0){
        if(!isset($_SESSION)) session_start();
        if(empty($_SESSION['id']))
            echo "<p><center><h3>Usuário não logado.</h3>&nbsp;&nbsp;<a href='login.php'>Voltar</a></center></p>";
        else
            $logado = 1;
    }else{
        mysqli_close($conexao);
        header("location: incluir.php");
    }
    if($logado == 1){
        echo "<p><h3>Olá, ".$_SESSION['nome']."!</h3></p>";?>
        <p><h2>Usuário(s)</h2></p>
        <table border="1" cellpadding="10">
            <thead>
                <th>Id</th>
                <th>Nome</th>
                <th>Login</th>
                <th>Ação</th>
            </thead>
            <tbody>
            <?php
            $cont = 0;
            $master = 0;
            while($r_user = mysqli_fetch_array($sql_user)){
                if($cont == 0 && $r_user['id'] == $_SESSION['id'])
                    $master = 1;
                if($master == 1 || $r_user['id'] == $_SESSION['id']){
                    echo "
                    <tr>
                        <td>".$r_user['id']."</td>
                        <td>".$r_user['nome']."</td>
                        <td>".$r_user['email']."</td>
                        <td align='center'>";
                        if($master == 1 && $r_user['id'] != $_SESSION['id']){
                            echo "
                            <table border='0'>
                            <tr>
                            <td><form method='POST' action='editar.php'><input type='hidden' name='alt_id' value='".$r_user['id']."'><button type='submit'>Editar</button></form></td>
                            <td><form method='POST' action='excluir.php'><input type='hidden' name='exc_id' value='".$r_user['id']."'><button type='submit'>Excluir</button></form></td>
                            </tr>
                            </table>";
                        }else
                            echo "
                            <form method='POST' action='editar.php'><input type='hidden' name='alt_id' value='".$r_user['id']."'><button type='submit'>Editar</button></form>";
                        echo "
                        </td>
                    </tr>";
                    if($master == 0) break;
                }
                $cont++;
            }
            mysqli_close($conexao);?>
            </tbody>
        </table>
        <?php
        if($master == 1)
            echo "
            <p><form method='POST' action='incluir.php'><button type='submit'>Incluir usuário</button></form></p>";
        echo "
        <p><form method='POST' action=''><input type='hidden' name='logout' value='1'><button type='submit'>Logout</button></form></p>";
    }?>
</body>
</html>
