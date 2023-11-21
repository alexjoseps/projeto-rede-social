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
        <?php
        $query = $connection->prepare("SELECT * FROM users WHERE id = :user_id");
        $query->bindParam("user_id", $_SESSION['user_id'], PDO::PARAM_INT);
        $query->execute();
        $current_user = $query->fetch(PDO::FETCH_ASSOC);

        $query = $connection->prepare("SELECT COUNT(*) AS total FROM friendships WHERE requestor_id = :user_id OR requestee_id = :user_id");
        $query->bindParam("user_id", $_SESSION['user_id'], PDO::PARAM_INT);
        $query->execute();
        $friendships = $query->fetch(PDO::FETCH_ASSOC);
        ?>

        <h2>
          <?php echo $current_user['name'] ?>
        </h2>

        <h3>
          <?php echo $friendships['total'] ?> amizades
        </h3>
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

    <div class="column right">
      <h2>Sugestões de amizades</h2>

      <?php
      $query = $connection->prepare("SELECT * FROM users WHERE users.id <> :current_user_id AND NOT EXISTS(SELECT 1 FROM friendships WHERE (requestor_id = users.id AND requestee_id = :current_user_id) OR (requestor_id = :current_user_id AND requestee_id = users.id))");
      $query->bindParam("current_user_id", $_SESSION['user_id'], PDO::PARAM_INT);
      $query->execute();
      $friendship_suggestions = $query->fetchAll(PDO::FETCH_ASSOC);
      shuffle($friendship_suggestions);

      foreach ($friendship_suggestions as $friendship_suggestion) { ?>
        <h2>
          <?php echo $friendship_suggestion["name"] ?>
        </h2>
        <form method="post" action="../scripts/create_friendship.php">
            <div>
              <input type="hidden" name="friend_id" value="<?php echo $friendship_suggestion["id"] ?>" />
            </div>

            <button type="submit" name="create-friendship" value="create-friendship">Adicionar amigo</button>
          </form>

      <?php } ?>
    </div>
  </div>
</body>

</html>