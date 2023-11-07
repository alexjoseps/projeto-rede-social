<?php
  session_start();

  include('../utils/config.php');

  if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $query = $connection->prepare("SELECT * FROM users WHERE email=:email");
    $query->bindParam("email", $email, PDO::PARAM_STR);
    $query->execute();

    if ($password !== $password_confirmation) {
      echo '<p class="error">As senhas precisam ser iguais</p>';
    } else {
      if ($query->rowCount() > 0) {
        echo '<p class="error">E-mail já está em uso</p>';
      }

      if ($query->rowCount() == 0) {
        $query = $connection->prepare("INSERT INTO users(name, email, password) VALUES (:name, :email, :password_hash)");
        $query->bindParam("name", $name, PDO::PARAM_STR);
        $query->bindParam("password_hash", $password_hash, PDO::PARAM_STR);
        $query->bindParam("email", $email, PDO::PARAM_STR);
        $result = $query->execute();

        if ($result) {
          $query = $connection->prepare("SELECT * FROM users WHERE email=:email");
          $query->bindParam("email", $email, PDO::PARAM_STR);
          $query->execute();
          $result = $query->fetch(PDO::FETCH_ASSOC);
          $_SESSION['user_id'] = $result['id'];

          header('Location: ../index.php');
        } else {
          echo '<p class="error">Não foi possível registar seu usuário</p>';
        }
      }
    }
  }
?>

<html>
  <header>
    <title>Rede Social</title>
    <link rel="stylesheet" href="../css/register.css">
  </header>

  <body>
    <div id="register-div">
      <h1>Registro</h1>
      <form method="post" action="">
      <div>
        <label>Nome</label>
        <input type="text" name="name" placeholder="Digite seu nome" required />
      </div>
      <div>
        <label>E-mail</label>
        <input type="text" name="email" placeholder="Digite seu e-mail" required />
      </div>
      <div>
        <label>Senha</label>
        <input type="password" name="password" placeholder="Digite sua senha" required />
      </div>
      <div>
        <label>Confirmação da senha</label>
        <input type="password" name="password_confirmation" placeholder="Confirme sua senha" required />
      </div>

      <button onclick="window.location.href = '../index.php'">Voltar</button>
      <button type="submit" name="register" value="register">Cadastrar</button>
      </form>
    </div>
  </body>
</html>