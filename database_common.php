<?php
class DatabaseCommon {
  private $dbh;

  function __construct() {
    $dbname = 'sample';
    $host = 'localhost';
    $user = 'dbuser';
    $password = 'dbpass';

    try {
      $this->dbh = new PDO("mysql:dbname={$dbname};host={$host}", $user, $password);
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  //PDOエラー時に例外をスローする
    } catch (PDOException $e) {
      die('Database Connection failed: '.$e->getMessage());
    }
  }

  function __destruct() {
    $this->dbh = null;
  }

  protected function prepare($sql, $driverOptions = array() ) {
    return $this->dbh->prepare($sql, $driverOptions);
  }
}