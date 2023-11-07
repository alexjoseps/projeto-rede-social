<?php
    session_start();

    if(isset($_SESSION['user_id'])){
      header('Location: views/feed.php');
      exit;
    }
?>

<html>
  <header>
    <title>Rede Social</title>
  </header>

  <body>
    <button onclick="window.location.href = 'views/login.php'">Acessar rede</button>
    <button onclick="window.location.href = 'views/register.php'">Criar conta</button>
  </body>
</html>