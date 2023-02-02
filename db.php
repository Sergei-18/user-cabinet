<?php
class _DataBase
{
	private $dbFileName = 'db/db.json';
	private $salt = 'hB5wG92p';
	public $users = array();
	
	public function loadUsers()
	{
		$this->users = json_decode(file_get_contents($this->dbFileName));
		return true;
	}
	
	public function saveUsers()
	{
		file_put_contents($this->dbFileName, json_encode($this->users));
		return true;
	}
	
	public function checkUserExist($login)
	{
		$returnStatus = false;
		foreach ($this->users as $userData)
			if ($userData->login == $login) {
				$returnStatus = true;
				break;
			}
		return $returnStatus;
	}
	
	public function checkEmailExist($email)
	{
		$returnStatus = false;
		foreach ($this->users as $userData)
			if ($userData->email == $email) {
				$returnStatus = true;
				break;
			}
		return $returnStatus;
	}
	
	public function getName($login)
	{
		$returnStatus = '';
		foreach ($this->users as $userData)
			if ($userData->login == $login) {
				$returnStatus = $userData->name;
				break;
			}
		return $returnStatus;
	}
	
	public function addUser($login, $password, $email, $nickname)
	{
		$returnStatus = -1;
		$FoundUser = $this->checkUserExist($login);
		$FoundEmail = $this->checkEmailExist($email);

		if ($FoundUser == false && $FoundEmail == false) {
			array_push($this->users, array('login' => $login, 'password' => md5($password . $this->salt), 'email' => $email, 'name' => $nickname));
			$this->saveUsers();
			$returnStatus = 0;
		} else if ($FoundUser)
			$returnStatus = 1;
		else if ($FoundEmail)
			$returnStatus = 2;
		return $returnStatus;
	}	
	
	public function checkUserPassword($login, $password)
	{
		$FoundUser = false;
		$PassCorrect = false;
		foreach ($this->users as $userData)
			if ($userData->login == $login) {
				$FoundUser = true;
				if ($userData->password == md5($password . $this->salt))
					$PassCorrect = true;
				break;
			}
		$returnStatus = -1;
		if ($FoundUser == false) $returnStatus = 2;
		else if ($PassCorrect == false) $returnStatus = 1;
		else if ($PassCorrect) $returnStatus = 0;
		
		return $returnStatus;
	}
	
	public function printUsers()
	{
		print_r($this->users);
		return true;
	}
}		  
?>