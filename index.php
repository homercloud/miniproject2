<?php
    include("conexaobd.php");
    $sql_user = mysqli_query($conexao,"select 1 from usuarios;");
    $usuarios = mysqli_num_rows($sql_user);
    mysqli_close($conexao);
    if($usuarios > 0)
        header("location: login.php");
    else
        header("location: incluir.php");
