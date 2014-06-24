<?php
require_once 'database_common.php';

class database extends DatabaseCommon {
  function __construct() {
    parent::__construct();
  }

  /* ユーザ追加 */
  public function insertUser($loginId, $loginPassword, $name) {
    $loginPassword = md5($loginPassword);

    $sql  = ' INSERT INTO user ';
    $sql .= ' ( ';
    $sql .= '  login_id ';
    $sql .= ' ,login_password ';
    $sql .= ' ,name ';
    $sql .= ' ,update_date ';
    $sql .= ' ) VALUES ( ';
    $sql .= '  :login_id ';
    $sql .= ' ,:login_password ';
    $sql .= ' ,:name ';
    $sql .= ' ,NOW() ';
    $sql .= ' ) ';

    $stmt = $this->prepare($sql);

    $stmt->bindValue(':login_id', $loginId);
    $stmt->bindValue(':login_password', $loginPassword);
    $stmt->bindValue(':name', $name);

    try {
      $stmt->execute();
    } catch (PDOException $e) {
      die('insertUser failed: '.$e->getMessage());
    }
  }

  /* ユーザ削除（フラグをセット） */
  public function deleteUser($id) {
    $sql  = 'UPDATE user SET is_deleted = :is_deleted WHERE id = :id';

    $stmt = $this->prepare($sql);

    $stmt->bindValue(':is_deleted', '1');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    try {
      $stmt->execute();
    } catch (PDOException $e) {
      die('insertUser failed: '.$e->getMessage());
    }
  }

  /* 一覧HTML作成 */
  public function getUserList() {
    $this->selectUser($rows);
    $html =<<<_EOD
<table border="1">
<tr>
<th>login id</th>
<th>name</th>
<th>delete</th>
</tr>
_EOD;
    foreach ($rows as $row) {
      $html .= <<<_EOD
<tr>
<td>{$row['login_id']}</td>
<td>{$row['name']}</td>
<td>
<form method="post" action="index.php">
<input type="hidden" name="id" value="{$row['id']}">
<input type="submit" name="delete" value="delete">
</form>
</td>
</tr>
_EOD;
    }
    $html .= "</table>";
    echo $html;
  }

  /* 一覧用データ取得 */
  private function selectUser(&$rows) {
    $sql  = ' SELECT ';
    $sql .= ' id';
    $sql .= ',login_id';
    $sql .= ',name';
    $sql .= ' FROM user ';
    $sql .= ' WHERE is_deleted = :is_deleted ';
    $sql .= ' ORDER BY id ';

    $stmt = $this->prepare($sql);

    $stmt->bindValue(':is_deleted', '0');

    try {
      $stmt->execute();
      $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
      die('selectUser failed: '.$e->getMessage());
    }
  }
}