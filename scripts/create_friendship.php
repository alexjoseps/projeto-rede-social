<?php
  session_start();
  include('../utils/config.php');

  if (isset($_POST['create-friendship'])) {
    $friend_id = $_POST['friend_id'];
    $current_user_id = $_SESSION['user_id'];
    date_default_timezone_set("America/Sao_Paulo");
    $created_at = date("Y-m-d H:i:s");
    
    $query = $connection->prepare("INSERT INTO friendships(requestor_id, requestee_id, created_at) VALUES (:requestor_id, :requestee_id, :created_at)");
    $query->bindParam("requestor_id", $friend_id, PDO::PARAM_INT);
    $query->bindParam("requestee_id", $current_user_id, PDO::PARAM_INT);
    $query->bindParam("created_at", $created_at, PDO::PARAM_STR);
    $result = $query->execute();
  }

  header('Location: ../views/feed.php');
?>