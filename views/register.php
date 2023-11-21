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

        header('Location: feed.php');
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
  <style>
    body {
      background-color: #f3f3f3;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    #header-bar {
      background-color: #0077b5;
      color: #ffffff;
      text-align: center;
      padding: 10px;
    }

    #logo-container {
      margin-bottom: 20px;
    }

    #register-div {
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin: 10% auto;
      padding: 20px;
      text-align: center;
      width: 300px;
    }

    #register-div h1 {
      color: #0077b5;
      /* Cor azul semelhante ao LinkedIn */
    }

    form div {
      margin-bottom: 15px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button {
      background-color: #0077b5;
      color: #ffffff;
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #005983;
      /* Cor mais escura ao passar o mouse */
    }

    button:first-child {
      background-color: #dcdcdc;
      color: #000000;
      margin-right: 10px;
    }

    button:first-child:hover {
      background-color: #bfbfbf;
    }
  </style>
  <link rel="stylesheet" href="../css/register.css">
</header>

<body>
  <div id="header-bar">
    <div id="logo-container">
      <!-- Adicione sua logo aqui -->
      <img src="c:\xampp\htdocs\AV2\logo.png" alt="Logo SendMe" style="max-width: 100px;">
    </div>
    <h1>SendMe</h1>
  </div>

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

      <button onclick="window.location.href = 'login.php'">Acessar rede</button>
      <button type="submit" name="register" value="register">Cadastrar</button>
    </form>
  </div>
</body>

</html>