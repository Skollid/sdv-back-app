<?php

class DAO {

  private $pdo;

  public function __construct() {
    $this->pdo = new PDO("pgsql:host=your-server-host;port=5432;dbname=your-database-name",
                        "your-user-name", "your-password");
  }

  public function create($data) {
    $stmt = $this->pdo->prepare("INSERT INTO data (name) VALUES (:name)");
    $stmt->bindParam(":name", $data["name"]);
    $stmt->execute();
  }

  public function read($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM data WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch();
  }

  public function update($id, $data) {
    $stmt = $this->pdo->prepare("UPDATE data SET name = :name WHERE id = :id");
    $stmt->bindParam(":name", $data["name"]);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
  }

  public function delete($id) {
    $stmt = $this->pdo->prepare("DELETE FROM data WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
  }

}

class Controller {

  private $dao;

  public function __construct() {
    $this->dao = new DAO();
  }

  public function handleRequest() {
    $path = $_SERVER['REQUEST_URI'];
    switch ($path) {
      case '/create':
        $this->create($_POST);
        break;
      case '/read':
        $id = $_GET['id'];
        $data = $this->read($id);
        echo json_encode($data);
        break;
      case '/update':
        $id = $_GET['id'];
        $this->update($id, $_POST);
        break;
      case '/delete':
        $id = $_GET['id'];
        $this->delete($id);
        break;
      default:
        http_response_code(404);
        break;
    }
  }

  // ...

}

$controller = new Controller();
$controller->handleRequest();

?>