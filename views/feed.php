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

        foreach ($publications as $publication) {
          ?>
          <h2>
            
          </h2>
          <form method="post" action="../scripts/create_comment.php">
            <div>
              <input type="text" name="comment" placeholder="Comente algo" required />
              <input type="hidden" name="publication_id" value="<?php $publication["id"] ?>" />
            </div>

            <button type="submit" name="create-comment" value="create-comment">Comentar</button>
          </form>
          <?php
        }
        ?>
      </div>
    </div>

    <div class="column right"></div>
  </div>
</body>

</html>