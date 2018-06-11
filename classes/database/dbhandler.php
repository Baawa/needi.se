<?php

class DBHandler{

  private $pdo;

  public function __construct(){
    $host = constant('DB_SERVERNAME');
    $db   = constant('DB_NAME');
    $user = constant('DB_USER');
    $pass = constant('DB_PASS');
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $this->pdo = new PDO($dsn, $user, $pass, $opt);
  }

  /* USER FUNCTIONS */
  const user_table = "user_table";
  public function createUserTable(){
    $table_name = self::user_table;

    $sql = "CREATE TABLE $table_name (
      id VARCHAR(10) PRIMARY KEY,
      email VARCHAR(50) NOT NULL,
      password VARCHAR(50) NOT NULL,
      name VARCHAR(30) NOT NULL,
      fb_id VARCHAR(200),
      adress VARCHAR(50),
      phone_number VARCHAR(10) NOT NULL,
      sid VARCHAR(10) NOT NULL,
      last_login VARCHAR(20) NOT NULL,
      reset_pass_url VARCHAR(50)
    ) CHARACTER SET utf8 COLLATE utf8_bin";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->errorCode() == "00000") {
      return TRUE;
    }

    return FALSE;
  }

  private function findUser($column, $value){
    if ($column != "" && $value != "") {
      $table_name = self::user_table;

      $sql = "SELECT * FROM $table_name WHERE $column = ?";
      $stmt = $this->pdo->prepare($sql);
      $user = new User();
      $stmt->setFetchMode(PDO::FETCH_INTO, $user);
      $stmt->execute([$value]);
      $user = $stmt->fetch();

      return $user;
    }

    return NULL;
  }

  public function createUser($email, $password, $name, $fb_id, $adress, $phone_number){
    if ($email != "" && $password != "" && $name != "" && $adress != "" && $phone_number != "") {
      $table_name = self::user_table;

      $email = strtolower($email);

      $adress = strtolower($adress);

      $column = "email";
      $value = $email;
      if ($this->findUser($column, $value)) {
        return NULL;
      }

      $column = "fb_id";
      $value = $fb_id;
      if ($this->findUser($column, $value)) {
        return NULL;
      }

      $password_hash = md5($password);

      $column = "id";
      $value = randomString(10);
      while ($this->findUser($column, $value) != NULL) {
        $value = randomString(10);
      }
      $id = $value;

      $column = "sid";
      $value = randomString(10);
      while ($this->findUser($column, $value) != NULL) {
        $value = randomString(10);
      }
      $sid = $value;

      $last_login = currentDateAndTime();

      $sql = "INSERT INTO $table_name VALUES(:id, :email, :password, :name, :fb_id, :adress, :phone_number, :sid, :last_login, :reset_pass_url)";
      $stmt = $this->pdo->prepare($sql);
      $success = $stmt->execute(["id" => $id, "email" => $email, "password" => $password_hash, "name" => $name, "fb_id" => $fb_id, "adress" => $adress, "phone_number" => $phone_number, "sid" => $sid, "last_login" => $last_login, "reset_pass_url" => ""]);

      if ($success) {
        return $this->getUser($id);
      }
    }

    return NULL;
  }

  public function loginUser($email, $password){
    if ($email != "" && $password != "") {
      $table_name = self::user_table;

      $email = strtolower($email);

      $password_hash = md5($password);

      $sql = "SELECT * FROM $table_name WHERE email = :email AND password = :password";
      $stmt = $this->pdo->prepare($sql);
      $user = new User();
      $stmt->setFetchMode(PDO::FETCH_INTO, $user);
      $stmt->execute(["email" => $email, "password" => $password_hash]);

      $user = $stmt->fetch();

      if ($user) {
        $column = "sid";
        $value = randomString(10);
        while ($this->findUser($column, $value) != NULL) {
          $value = randomString(10);
        }
        $sid = $value;

        $last_login = currentDateAndTime();

        $sql = "UPDATE $table_name SET sid = :sid, last_login = :last_login WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $user_mod = new User();
        $stmt->setFetchMode(PDO::FETCH_INTO, $user_mod);
        $success = $stmt->execute(["sid" => $sid, "last_login" => $last_login, "id" => $user->id]);

        if ($success) {
          $user->sid = $sid;
          $user->last_login = $last_login;

          return $user;
        }
      }
    }

    return NULL;
  }

  public function loginUserFB($fb_id){
    if ($fb_id != "") {
      $table_name = self::user_table;

      $sql = "SELECT * FROM $table_name WHERE fb_id = :fb_id";
      $stmt = $this->pdo->prepare($sql);
      $user = new User();
      $stmt->setFetchMode(PDO::FETCH_INTO, $user);
      $stmt->execute(["fb_id" => $fb_id]);

      $user = $stmt->fetch();

      if ($user) {
        $column = "sid";
        $value = randomString(10);
        while ($this->findUser($column, $value) != NULL) {
          $value = randomString(10);
        }
        $sid = $value;

        $last_login = currentDateAndTime();

        $sql = "UPDATE $table_name SET sid = :sid, last_login = :last_login WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $user_mod = new User();
        $stmt->setFetchMode(PDO::FETCH_INTO, $user_mod);
        $success = $stmt->execute(["sid" => $sid, "last_login" => $last_login, "id" => $user->id]);

        if ($success) {
          $user->sid = $sid;
          $user->last_login = $last_login;

          return $user;
        }
      }
    }

    return NULL;
  }

  public function changeUserPassword($user, $new_password){
    if ($user != NULL && $new_password != "") {
      $table_name = self::user_table;

      $pass_hash = md5($new_password);

      $sql = "UPDATE $table_name SET password = :password WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $user = new User();
      $stmt->setFetchMode(PDO::FETCH_INTO, $user);
      $stmt->execute(["password" => $pass_hash, "id" => $user->id]);
      $user = $stmt->fetch();

      return $user;
    }

    return NULL;
  }

  public function setUserResetPassURL($email){
    if ($email != "") {
      $table_name = self::user_table;

      $email = strtolower($email);

      $column = "reset_pass_url";
      $value = randomString(10);
      while ($this->findUser($column, $value) != NULL) {
        $value = randomString(10);
      }
      $url = $value;

      $sql = "UPDATE $table_name SET reset_pass_url = :url WHERE email = :email";
      $stmt = $this->pdo->prepare($sql);
      $user = new User();
      $stmt->setFetchMode(PDO::FETCH_INTO, $user);
      $stmt->execute(["url" => $url, "email" => $email]);
      $user = $stmt->fetch();

      if ($user) {
        return $url;
      }
    }

    return NULL;
  }

  public function isUserLoggedIn($id, $sid){
    if ($id != "" && $sid != ""){
      $table_name = self::user_table;

      $sql = "SELECT * FROM $table_name WHERE id = :id AND sid = :sid";
      $stmt = $this->pdo->prepare($sql);
      $user = new User();
      $stmt->setFetchMode(PDO::FETCH_INTO, $user);
      $stmt->execute(["id" => $id, "sid" => $sid]);
      $user = $stmt->fetch();

      return $user != NULL;
    }

    return FALSE;
  }

  public function getUser($user_id){
    if ($user_id != "") {
      $table_name = self::user_table;

      $sql = "SELECT * FROM $table_name WHERE id = ?";
      $stmt = $this->pdo->prepare($sql);
      $user = new User();
      $stmt->setFetchMode(PDO::FETCH_INTO, $user);
      $stmt->execute([$user_id]);
      $user = $stmt->fetch();

      return $user;
    }

    return NULL;
  }

  public function getUsers(){
    $table_name = self::user_table;

    $sql = "SELECT * FROM $table_name";
    $stmt = $this->pdo->prepare($sql);
    $success = $stmt->execute();

    if ($success) {
      $user = new User();
      $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($user));
      $users = $stmt->fetchAll();

      return $users;
    } else{
      //failure.;
    }
  }

  /* END: USER FUNCTIONS */

  /* ITEM FUNCTIONS */
  const item_table = "item_table";
  public function createItemTable(){
    $table_name = self::item_table;

    $sql = "CREATE TABLE $table_name (
      id VARCHAR(10) PRIMARY KEY,
      user_id VARCHAR(10) NOT NULL,
      name VARCHAR(200) NOT NULL,
      description VARCHAR(400),
      price VARCHAR(50),
      image_url VARCHAR(50),
      time_available VARCHAR(20),
      days_available VARCHAR(20)
    ) CHARACTER SET utf8 COLLATE utf8_bin";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->errorCode() == "00000") {
      return TRUE;
    }

    return FALSE;
  }

  private function findItem($column, $value){
    if ($column != "" && $value != "") {
      $table_name = self::item_table;

      $sql = "SELECT * FROM $table_name WHERE $column = ?";
      $stmt = $this->pdo->prepare($sql);
      $item = new Item();
      $stmt->setFetchMode(PDO::FETCH_INTO, $item);
      $stmt->execute([$value]);
      $item = $stmt->fetch();

      return $item;
    }

    return NULL;
  }

  public function createItem($user, $name, $description, $price, $image_url, $time_available, $days_available){
    if ($user != NULL && $name != "" && $description != "" && $price != "" && $image_url != "" && $time_available != "" && $days_available != "") {
      $table_name = self::item_table;

      $column = "id";
      $value = randomString(10);
      while ($this->findItem($column, $value) != NULL) {
        $value = randomString(10);
      }
      $id = $value;

      $sql = "INSERT INTO $table_name VALUES(:id, :user_id, :name, :description, :price, :image_url, :time_available, :days_available)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(["id" => $id, "user_id" => $user->id, "name" => $name, "description" => $description, "price" => $price, "image_url" => $image_url, "time_available" => $time_available, "days_available" => $days_available]);

      $sql = "SELECT * FROM $table_name WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $item = new Item();
      $stmt->setFetchMode(PDO::FETCH_INTO, $item);
      $stmt->execute(["id" => $id]);
      $item = $stmt->fetch();

      return $item;
    }

    return NULL;
  }

  public function updateItem($item, $name = "", $description = "", $price = "", $image_url = "", $time_available = "", $days_available = ""){
    if ($item != NULL) {
      $table_name = self::item_table;

      if ($name == "") {
        $name = $item->name;
      }

      if ($description == "") {
        $description = $item->description;
      }

      if ($price == "") {
        $price = $item->price;
      }

      if ($image_url == "") {
        $image_url = $item->image_url;
      }

      if ($time_available == "") {
        $time_available = $item->time_available;
      }

      if ($days_available == "") {
        $days_available = $item->days_available;
      }

      $sql = "UPDATE $table_name SET name = :name, description = :description, price = :price, image_url = :image_url, time_available = :time_available, days_available = :days_available WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $success = $stmt->execute(["name" => $name, "description" => $description, "price" => $price, "image_url" => $image_url, "time_available" => $time_available, "days_available" => $days_available, "id" => $item->id]);

      return $success;
    }

    return FALSE;
  }

  public function searchItem($name){
    if ($name != "") {
      $table_name = self::item_table;

      $search_term = strtolower("%$name%");

      $sql = "SELECT * FROM $table_name WHERE lower(name) LIKE :name";
      $stmt = $this->pdo->prepare($sql);
      $success = $stmt->execute(["name" => $search_term]);

      if ($success) {
        $item = new Item();
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($item));
        $items = $stmt->fetchAll();

        return $items;
      } else{
        //failure.
      }
    }
    return NULL;
  }

  public function getItem($id){
    if ($id != "") {
      $table_name = self::item_table;

      $sql = "SELECT * FROM $table_name WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $item = new Item();
      $stmt->setFetchMode(PDO::FETCH_INTO, $item);
      $stmt->execute(["id" => $id]);
      $item = $stmt->fetch();

      return $item;
    }

    return NULL;
  }

  public function getItems($user_id){
    if ($user_id != "") {
      $table_name = self::item_table;

      $sql = "SELECT * FROM $table_name WHERE user_id = :user_id";
      $stmt = $this->pdo->prepare($sql);
      $success = $stmt->execute(["user_id" => $user_id]);

      if ($success) {
        $item = new Item();
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($item));
        $items = $stmt->fetchAll();

        return $items;
      } else{
        //failure.;
      }
    }
    return NULL;
  }

  public function getRandomItems($amount = 5){
    $table_name = self::item_table;

    $sql = "SELECT * FROM $table_name ORDER BY RAND() LIMIT :amount";
    $stmt = $this->pdo->prepare($sql);
    $success = $stmt->execute(["amount" => "" . $amount]);

    if ($success) {
      $item = new Item();
      $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($item));
      $items = $stmt->fetchAll();

      return $items;
    } else{
      return NULL;
    }
  }

  public function removeItem($item, $user){
    if ($item != NULL && $user != NULL) {
      $table_name = self::item_table;

      $sql = "DELETE FROM $table_name WHERE id = :id AND user_id = :user_id";
      $stmt = $this->pdo->prepare($sql);
      $success = $stmt->execute(["id" => $item->id, "user_id" => $user->id]);

      return $success;
    }

    return FALSE;
  }
  /* END: ITEM FUNCTIONS */

  /* REQUEST FUNCTIONS */
  const request_table = "request_table";
  public function createRequestTable(){
    $table_name = self::request_table;

    $sql = "CREATE TABLE $table_name (
      id VARCHAR(10) PRIMARY KEY,
      item_id VARCHAR(10) NOT NULL,
      from_user_id VARCHAR(10) NOT NULL,
      to_user_id VARCHAR(10) NOT NULL,
      start_date VARCHAR(20) NOT NULL,
      end_date VARCHAR(20) NOT NULL,
      approved_date VARCHAR(20),
      retrieved_date VARCHAR(20),
      returned_date VARCHAR(20)
    ) CHARACTER SET utf8 COLLATE utf8_bin";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->errorCode() == "00000") {
      return TRUE;
    }

    return FALSE;
  }

  private function findRequest($column, $value){
    if ($column != "" && $value != "") {
      $table_name = self::request_table;

      $sql = "SELECT * FROM $table_name WHERE $column = ?";
      $stmt = $this->pdo->prepare($sql);
      $request = new Request();
      $stmt->setFetchMode(PDO::FETCH_INTO, $request);
      $stmt->execute([$value]);
      $request = $stmt->fetch();

      return $request;
    }

    return NULL;
  }

  public function addRequest($item, $from_user, $to_user, $start_date, $end_date){
    if ($item != NULL && $from_user != NULL && $to_user != NULL && $start_date != "" && $end_date != "") {
      $table_name = self::request_table;

      $column = "id";
      $value = randomString(10);
      while ($this->findRequest($column, $value) != NULL) {
        $value = randomString(10);
      }
      $id = $value;

      $sql = "INSERT INTO $table_name VALUES(:id, :item_id, :from_user_id, :to_user_id, :start_date, :end_date, :approved_date, :retrieved_date, :returned_date)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(["id" => $id, "item_id" => $item->id, "from_user_id" => $from_user->id, "to_user_id" => $to_user->id, "start_date" => $start_date, "end_date" => $end_date, "approved_date" => "", "retrieved_date" => "", "returned_date" => ""]);

      $sql = "SELECT * FROM $table_name WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $request = new Request();
      $stmt->setFetchMode(PDO::FETCH_INTO, $request);
      $stmt->execute(["id" => $id]);
      $request = $stmt->fetch();

      return $request;
    }
  }

  public function removeRequest($request_id){
    if ($request_id != "") {
      $table_name = self::request_table;

      $sql = "DELETE FROM $table_name WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $success = $stmt->execute(["id" => $request_id]);

      return $success;
    }
  }

  public function getRequests($user_id){
    if ($user_id != "") {
      $table_name = self::request_table;

      $sql = "SELECT * FROM $table_name WHERE from_user_id = :from_user_id OR to_user_id = :to_user_id";
      $stmt = $this->pdo->prepare($sql);
      $success = $stmt->execute(["from_user_id" => $user_id, "to_user_id" => $user_id]);

      if ($success) {
        $request = new Request();
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($request));
        $requests = $stmt->fetchAll();

        return $requests;
      } else{
        //failure.;
      }
    }
    return NULL;
  }

  public function getRequest($request_id){
    if ($request_id != "") {
      $table_name = self::request_table;

      $sql = "SELECT * FROM $table_name WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $request = new Request();
      $stmt->setFetchMode(PDO::FETCH_INTO, $request);
      $stmt->execute(["id" => $request_id]);
      $request = $stmt->fetch();

      return $request;
    }

    return NULL;
  }

  public function setRequestApproved($request_id){
    if ($request_id != "") {
      $table_name = self::request_table;

      $date = currentDateAndTime();

      $sql = "UPDATE $table_name SET approved_date = :approved_date WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $success = $stmt->execute(["approved_date" => $date, "id" => $request_id]);

      return $success;
    }
    return FALSE;
  }

  public function setRequestItemRetrieved($request_id){
    if ($request_id != "") {
      $table_name = self::request_table;

      $date = currentDateAndTime();

      $sql = "UPDATE $table_name SET retrieved_date = :retrieved_date WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $success = $stmt->execute(["retrieved_date" => $date, "id" => $request_id]);

      return $success;
    }
    return FALSE;
  }

  public function setRequestItemReturned($request_id){
    if ($request_id != "") {
      $table_name = self::request_table;

      $date = currentDateAndTime();

      $sql = "UPDATE $table_name SET returned_date = :returned_date WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $success = $stmt->execute(["returned_date" => $date, "id" => $request_id]);

      return $success;
    }
    return FALSE;
  }
  /* END: REQUEST FUNCTIONS */


}

 ?>
