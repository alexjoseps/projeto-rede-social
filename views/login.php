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
      header('Location: feed.php');
    } else {
      echo "<p class='error'>Usuário ou senha inválidos</p>";
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

    #login-div {
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin: 10% auto;
      padding: 20px;
      text-align: center;
      width: 300px;
    }

    #login-div h1 {
      color: #0077b5;
    }

    .form-element {
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
      /* Altera cor ao passar do mouse */
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
  <link rel="stylesheet" href="../css/login.css">
</header>

<body>
  <div id="header-bar">
    <div id="logo-container">
      <!-- Adicionar a imagem aqui -->
      <img src="c:Teste\logo.png" alt="Logo SendMe" style="max-width: 100px;">
    </div>
    <h1>SendMe</h1>
  </div>

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

      <button onclick="window.location.href = 'register.php'">Criar conta</button>
      <button type="submit" name="login" value="login">Acessar</button>
    </form>
  </div>
</body>

</html>