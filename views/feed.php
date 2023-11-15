<?php
include('../utils/config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: ../index.php');
  exit;
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Rede Social</title>
  <style>
    body {
      background-color: #f3f3f3;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
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

    .row {
      display: flex;
    }

    .column {
      flex: 1;
      padding: 20px;
    }

    .left {
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin-right: 10px;
    }

    .middle {
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin: 0 10px;
    }

    .right {
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin-left: 10px;
    }

    .profile {
      height: 200px;
      width: 200px;
      background-color: #dcdcdc;
      /* Cor de fundo temporária para o perfil */
      border-radius: 50%;
      margin: 20px auto;
    }

    .post-form {
      margin-top: 20px;
    }

    .post-list {
      margin-top: 20px;
    }

    .post-list h2 {
      color: #333333;
    }

    .post-list form {
      margin-top: 10px;
    }

    .post-list input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      margin-bottom: 10px;
    }

    .post-list button {
      background-color: #0077b5;
      color: #ffffff;
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    .post-list button:hover {
      background-color: #005983;
    }
  </style>
  <link rel="stylesheet" href="../css/rede-social.css">
</head>

<body>
  <div>
    <button onclick="window.location.href = '../scripts/logout.php'">Sair</button>
  </div>

  <div class="row">
    <div class="column left">
      <div class="profile">

      </div>
    </div>

    <div class="column middle">
      <div class="post-form">
        <form method="post" action="../scripts/create_post.php">
          <div>
            <input type="text" name="post-description" placeholder="Publique algo" required />
          </div>

          <button type="submit" name="create-post" value="create-post">Criar publicação</button>
        </form>
      </div>

      <div class=post-list>
        <?php
        $query = $connection->prepare("SELECT * FROM publications");
        $query->execute();
        $publications = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($publications as $publication) { ?>
          <h2>
            <?php echo $publication["description"] ?>
          </h2>

          <?php
          $publication_id = $publication['id'];
          $query = $connection->prepare("SELECT * FROM comments WHERE publication_id = $publication_id");
          $query->execute();
          $comments = $query->fetchAll(PDO::FETCH_ASSOC);

          foreach ($comments as $comment) { ?>
            <h3>
              <?php echo $comment["comment"] ?>
            </h3>
          <?php } ?>

          <form method="post" action="../scripts/create_comment.php">
            <div>
              <input type="text" name="comment" placeholder="Comente algo" required />
              <input type="hidden" name="publication_id" value="<?php echo $publication["id"] ?>" />
            </div>

            <button type="submit" name="create-comment" value="create-comment">Comentar</button>
          </form>
        <?php } ?>
      </div>
    </div>

    <div class="column right"></div>
  </div>
</body>

</html>