<?php

// EXAMPLES //

/*
// getting the number of rows in the table
$count = $db->runQuery("SELECT count(*) FROM users")->fetchColumn();

// the user data based on email
$user = $db->runQuery("SELECT * FROM users WHERE email=?", [$email])->fetch();

// getting many rows from the table
$data = $db->runQuery("SELECT * FROM users WHERE salary > ?", [$salary])->fetchAll();

// getting the number of affected rows from DELETE/UPDATE/INSERT
$deleted = $db->runQuery("DELETE FROM users WHERE id=?", [$id])->rowCount();

// insert
$db->runQuery("INSERT INTO users VALUES (null, ?,?,?)", [$name, $email, $password]);

// named placeholders are also welcome though I find them a bit too verbose
$db->runQuery("UPDATE users SET name=:name WHERE id=:id", ['id'=>$id, 'name'=>$name]);

// using a sophisticated fetch mode, indexing the returned array by id
$indexed = $db->runQuery("SELECT id, name FROM users")->fetchAll(PDO::FETCH_KEY_PAIR);

*/
class database {

        /** @var PDO */
        private $pdo;

	public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }
    
	public function runQuery($sql, $parameters = []) {
    
        $stmt = $this->pdo->prepare($sql);
    
        if ($stmt->execute($parameters)) {
            return $stmt; // Retourneer het PDOStatement-object voor verdere verwerking
        } else {
            return false;
        }
    }
    /*
	public function runQuery($sql, $parameters = []) {
    	
		$stmt = $this->pdo->prepare($sql);
		
		if ( $stmt->execute($parameters) ) {
		  return true;
		} else {
		  return false;
		}
	}*/
    
  public function countQuery($sql, $parameters = []) {
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($parameters);

    return $stmt->rowCount() > 0;
  }

  function deletedata( $table, $field, $value ) {

	  $sql= "DELETE FROM ".$table." WHERE ".$field."=?";
	  $stmt = $this->pdo->prepare($sql);
	  
	  if ( $stmt->execute( [$value] ) ) {
      return true;
    } else {
      return false;
    }
  }

  function insertdata( $table, $values ) {

    $fields = '';
    $placeholder = '';

    foreach ( $values as $key => $value ) {
      $fields .= $key . ", ";
      $placeholder .= ":" . $key . ", ";
    }

    $collumns = substr( $fields, 0, -2 );
    $placeholders = substr( $placeholder, 0, -2 );

    $sql = "INSERT INTO " . $table . " (";
    $sql .= $collumns;
    $sql .= ") VALUES (" . $placeholders . ")";

    $stmt = $this->pdo->prepare( $sql );

    if ( $stmt->execute( $values ) ) {
      return true;
    } else {
      return false;
    }
  }
}
?>