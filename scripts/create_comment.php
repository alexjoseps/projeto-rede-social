<?php
  session_start();
  include('../utils/config.php');

  if (isset($_POST['create-comment'])) {
    $description = $_POST['comment'];
    $publication_id = $_POST['publication_id'];
    $user_id = $_SESSION['user_id'];
    date_default_timezone_set("America/Sao_Paulo");
    $created_at = date("Y-m-d H:i:s");
    
    $query = $connection->prepare("INSERT INTO comments(comment, user_id, publication_id, created_at) VALUES (:comment, :user_id, :publication_id, :created_at)");
    $query->bindParam("comment", $comment, PDO::PARAM_STR);
    $query->bindParam("user_id", $user_id, PDO::PARAM_INT);
    $query->bindParam("publication_id", $publication_id, PDO::PARAM_INT);
    $query->bindParam("created_at", $created_at, PDO::PARAM_STR);
    $result = $query->execute();
  }

  header('Location: ../views/feed.php');
?>