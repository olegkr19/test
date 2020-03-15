<?php

class DB
{

  private $dsn;
  private $user;
  private $password;
  private $table_name = 'clients';
  private $errors = null;
  public $db_conn;

  public function __construct($dsn = 'mysql:dbname=***;host=***',$user = '***',$password = '***'){
    try{
     $this->dsn = $dsn;
     $this->user = $user;
     $this->password = $password;
     $this->db_conn = new PDO($this->dsn,$this->user,$this->password,array(PDO::ATTR_TIMEOUT => 30));
     $this->db_conn->beginTransaction();
   }catch(PDOException $e){
       echo 'Подключение не удалось: ' . $e->getMessage();
   }
  }
  public function getData(){
    $sql = "SELECT * FROM $this->table_name";
    $stm = $this->db_conn->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    print_r($result);
  }
  public function insertData($name, $phone,$email,$card,$money,array $data){
    if($this->errors == null){
    $data = json_encode($data);
    $sql = "INSERT INTO $this->table_name(client_name,client_phone,client_email,client_card,client_money,client_data) VALUES(:name,:phone,:email,:card,:money,:data)";
    $stm = $this->db_conn->prepare($sql);
    $stm->bindParam(':name',$name,PDO::PARAM_STR);
    $stm->bindParam(':phone',$phone,PDO::PARAM_STR);
    $stm->bindParam(':email',$email,PDO::PARAM_STR);
    $stm->bindParam(':card',$card,PDO::PARAM_STR);
    $stm->bindParam(':money',$money,PDO::PARAM_STR);
    $stm->bindParam(':data',$data,PDO::PARAM_STR);
    $stm->fetch();
    $stm->execute();
    $this->db_conn->commit();
  }else{
    $this->db_conn->rollback();
    $this->errors[] = "Ошибка при добавлении";
  }
  }
  public function updateData($id,$name,$phone,$email,$card,$money,array $data){
    if($this->errors == null){
    $data = json_encode($data);
    $sql = "UPDATE $this->table_name SET client_name=:name, client_phone=:phone,client_email=:email, client_card=:card,client_money=:money,client_data=:data WHERE client_id=:id";
    $stm = $this->db_conn->prepare($sql);
    $stm->bindParam(':name',$name,PDO::PARAM_STR);
    $stm->bindParam(':phone',$phone,PDO::PARAM_STR);
    $stm->bindParam(':email',$email,PDO::PARAM_STR);
    $stm->bindParam(':card',$card,PDO::PARAM_STR);
    $stm->bindParam(':money',$money,PDO::PARAM_STR);
    $stm->bindParam(':data',$data,PDO::PARAM_STR);
    $stm->bindParam(':id',$id,PDO::PARAM_INT);
    $stm->execute();
    $this->db_conn->commit();
  }else {
    $this->db_conn->rollback();
    $this->errors[] = "Ошибка при изменении";
  }
  }
  public function deleteData($id){
    if($this->errors == null){
    $sql = "DELETE FROM $this->table_name WHERE client_id=:id";
    $stm = $this->db_conn->prepare($sql);
    $stm->bindParam(':id',$id,PDO::PARAM_INT);
    $stm->execute();
    $this->db_conn->commit();
  }else{
    $this->db_conn->rollback();
    $this->errors[] = "Ошибка при удалении";
  }
}
}
$db = new DB();
