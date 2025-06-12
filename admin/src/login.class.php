<?php
class login {

        /** @var PDO */
        private $pdo;
	
	function __construct($pdo) {
		$this->pdo = $pdo;
    }

    function checkLogin( $username, $hash ) {
		          
        //echo "<br>username: ".$username;
        //echo "<br>hash: ". $hash;
		if(!$username || !$hash) {
			return false;
		} else {

			$sql = "SELECT * FROM group_login WHERE username = :username";
			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ "username" => $username ] );

			$row = $stmt->fetch();
            
            if(!$row) { return false; } else {
                $password = $row['password'];
                $username = $row['username'];

                if (password_verify($hash, $password)) {
                    $result = true;
                } else {
                    $result = false;
                }
            }
            
            return $result;
		}
	}
    
    function getLogin($email) {
		          
		if(!$email) {
			return false;
		} else {

			$sql = "SELECT * FROM group_login WHERE email = :email";
			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ "email" => $email ] );

			$row = $stmt->fetch();
            
            return $row;
		}
	}
    
    function getUserInfo($field, $value) {
		          
		if(!$field) {
			return false;
		} else {

			$sql = "SELECT * FROM group_login WHERE {$field} = :{$field}";
			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ $field => $value ] );

			$row = $stmt->fetch();
            
            return $row;
		}
	}
    
    function getLoginHash($hash) {
		          
		if(!$hash) {
			return false;
		} else {

			$sql = "SELECT * FROM group_password 
            INNER JOIN group_login
                ON group_password.uid = group_login.id
            WHERE group_password.hash = :hash";
            
			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ "hash" => $hash ] );

			$row = $stmt->fetch();
            
            return $row;
		}
	}
    
    function checkMailExist($email) {
		          
		if(!$email) {
			return false;
		} else {

			$sql = "SELECT * FROM group_login WHERE email = ?";
			$stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);
            $mailExists = $stmt->fetchColumn();
            
            if ($mailExists) {
                $result = true;
            } else {
                $result = false;
            }		
            
            return $result;
		}
	}
    
    function mailRecoveryLink($uid, $site) {
        
        $hash = password_hash(time(), PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO group_password (uid, hash, startdate) VALUES ('".$uid."', '".$hash."', NOW()) ON DUPLICATE KEY UPDATE hash = '".$hash."'";
         
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        $link = $site.$hash;

        $loc = '<a href="'.$link.'">'.$link.'</a>';
        return $loc;
    }
        
    // more function
    function daytime($time) {

        $time = date("H");

        if ($time >=  0) $daytime = "Goede nacht"; // REALLY early
        if ($time >  6) $daytime = "Goede morgen";      // After 6am
        if ($time > 12) $daytime = "Goede middag";    // After 12pm
        if ($time > 18) $daytime = "Goede avond";      // After 5pm
        if ($time > 22) $daytime = "Bedtijd";        // After 10pm
        
        return $daytime;
    }
}
?>