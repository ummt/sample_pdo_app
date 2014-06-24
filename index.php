<?php
require_once 'database.php';
$db = new Database();
// 追加
if (isset($_POST['insert'])) {
  $db->insertUser($_POST['loginId'], $_POST['loginPassword'], $_POST['name']);
}
// 削除
if (isset($_POST['delete'])) {
  $db->deleteUser($_POST['id']);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
  <form method="post" action="index.php">
    <label>login id<input type="text" name="loginId"></label>
    <label>login password<input type="text" name="loginPassword"></label>
    <label>name<input type="text" name="name"></label>
    <input type="submit" name="insert" value="追加">
  </form>
  <?php $db->getUserList(); ?>
</body>
</html>