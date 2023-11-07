<?php
    session_start();
    include('../utils/config.php');

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = $connection->prepare("SELECT * FROM users WHERE email=:email");
        $query->bindParam("email", $email, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
          echo "<p class='error'>Usuário ou senha inválidos</p>";
        } else {
          $senha = $result['password'];
  
          if (password_verify($password, $result['password'])) {
              $_SESSION['user_id'] = $result['id'];
              header('Location: ../index.php');
          } else {
              echo "<p class='error'>Usuário ou senha inválidos</p>";
          }
        }
    }
?>

<html>
  <header>
    <title>Rede Social</title>
    <link rel="stylesheet" href="../css/login.css">
  </header>

  <body>
    <div id="login-div">
    <h1>Acesso</h1>
      <form method="post" action="" name="login-form">
      <div class="form-element">
        <label>E-mail</label>
        <input type="text" name="email" placeholder="Digite seu e-mail" required />
      </div>
      <div class="form-element">
        <label>Senha</label>
        <input type="password" name="password" placeholder="Digite sua senha" required />
      </div>

      <button onclick="window.location.href = '../index.php'">Voltar</button>
      <button type="submit" name="login" value="login">Acessar</button>
    </form>
    </div>
  </body>
</html>
