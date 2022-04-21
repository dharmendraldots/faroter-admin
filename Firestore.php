<?php

require_once 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Firestore{

    protected $db;
    protected $name;
    
    public function __construct(string $collection)
    {
        $this->db = new FirestoreClient([
            'projectId' => $_ENV['PROJECT_ID']
        ]) ;

        $this->name = $collection;
    }

    public function getDocument(string $name){

        try {

            if ($this->db->collection($this->name)->document($name)->snapshot()->exists()) {
                return $this->db->collection($this->name)->document($name)->snapshot()->data();
            } else {
                throw new Exception( message: 'Document does not exists');
            }

        }   catch (Exception $exception) {

            return $exception->getMessage();

        }    
      

    }

    public function getAllDocument($fromDate="",$toDate="",$sender_name=""){
     
		$showallData =[];
		$query =[];
		$documents =[];     
		// $query = $this->db->collection($this->name)->orderBy('created_at', 'DESC');
		// $query = $this->db->collection($this->name)->orderBy('created_at', 'DESC')->limit(10);
		// $query = $this->db->collection($this->name)->where('created_at', '>=', $fromDate)->where('created_at', '<=', $toDate)->orderBy('created_at', 'DESC')->limit(120);
		// $query = $this->db->collection($this->name)->where('payment_status', '=', false);
		
		$query = $this->db->collection($this->name);
		
		// $sender_name = "Solomon Mesumbe";
		if($sender_name != ""){
			$query = $query
					->where('sender_name', '=', $sender_name)
					->limit(120);
		}
		else if($fromDate != "" && $toDate != ""){
			$query = $query
					->where('created_at', '>=', $fromDate)
					->where('created_at', '<=', $toDate)
					->orderBy('created_at', 'DESC')
					->limit(120);
		}
		else{
			$query = $query
					->orderBy('created_at', 'DESC')
					->limit(120);			
		}
		$documents = $query->documents();
		// foreach ($documents as $document) {
            // if ($document->exists()) {
                // $showallData[] = $document->data();                         
                // }
        // }        
        return $documents;
    }
    
    public function updateDocument(string $name){
     
        $query = $this->db->collection($this->name)->document($name);
        $query->update([
            ['path' => 'payment_status', 'value' => true]
        ]);
        return true;
    }
	
	public function getAllDocumentUser($country_code="", $mobile="", $password=""){
     
		$showallData =[];
		$query =[];
		$documents =[];
		
		$query = $this->db->collection($this->name);
		
		if($country_code != "" && $mobile != "" && $password != ""){
			$password = md5($password);
			$query = $query
					->where('country_code', '=', $country_code)
					->where('mobile', '=', $mobile)
					->where('password', '=', $password)
					// ->where('is_admin', '=', $is_admin)
					// ->where('is_active', '=', 1)
					->limit(1);
					
			$documents = $query->documents();
			// print_r($documents);
			// die;
			// foreach ($documents as $document) {
				// if ($document->exists()) {
					// $showallData[] = $document->data();
				// }
			// }
		}		
        return $documents;
    }
	
	public function getUserByEmail($email=""){
     
		$showallData =[];
		$query =[];
		$documents =[];
		
		$query = $this->db->collection($this->name);
		
		// $sender_name = "Solomon Mesumbe";
		if($email != ""){
			$query = $query
					->where('email', '=', $email)
					->limit(1);
					
			$documents = $query->documents(); 
			// foreach ($documents as $document) {
				// if ($document->exists()) {
					// $showallData[] = $document->data();
				// }
			// }
		}		
        return $documents;
    }
    
    public function updatePassword(string $name, $password, $newpassword){
		$showallData =[];
		$query =[];
		$documents =[];
		
		$password = md5($password);
		$newpassword = md5($newpassword);
		
		$query = $this->db->collection($this->name);
		$query = $query
				->where('user_id', '=', $name)
				->where('password', '=', $password)
				->limit(1);	
		$documents = $query->documents();
		foreach ($documents as $document) {
			if ($document->exists()) {
				$query = $this->db->collection($this->name)->document($name);
				$query->update([
					['path' => 'password', 'value' => $newpassword]
				]);
				return true;
			}
		}
		return false;
    }
    
    public function resetPassword($email, $reset_token, $newpassword){
		$showallData =[];
		$query =[];
		$documents =[];
		
		$newpassword = md5($newpassword);
		
		$query = $this->db->collection($this->name);
		$query = $query
				->where('email', '=', $email)
				->where('reset_token', '=', $reset_token)
				->limit(1);	
		$documents = $query->documents();
		foreach ($documents as $document) {
			if ($document->exists()) {
				$query = $this->db->collection($this->name)->document($document->id());
				$query->update([
					['path' => 'password', 'value' => $newpassword]
				]);
				return true;
			}
		}
		return false;
    }
    
    public function forgetPassword($email){
		$showallData =[];
		$query =[];
		$documents =[];
		
		$query = $this->db->collection($this->name);
		$query = $query
				->where('email', '=', $email)
				->limit(1);	
		$documents = $query->documents();
		foreach ($documents as $document) {
			if ($document->exists()) {
				$reset_token = $this->generateRandomString();
				$query = $this->db->collection($this->name)->document($document->id());
				$query->update([
					['path' => 'reset_token', 'value' => $reset_token]
				]);
				$this->passwordResetLinkEmail($email,$reset_token);
				return true;
			}
		}
		return false;
    }
	public function generateRandomString($length = 20) {
		$characters = $_ENV['RESET_TOKEN_CHARACTERS'];
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public function passwordResetLinkEmail($email,$reset_token){
		if(isset($email) && $email != ""){
			#get======
			$sendGridUrl = $_ENV['MAIL_HOST'];
		
			$token = $_ENV['MAIL_TOKEN'];
		
			$email_from = $_ENV['MAIL_FROM']; // Email from
			$subject = "Reset Password Notification Faroter Admin !!"; // Email Subject
		
			#=======================================#
			#           File to HTML                #
			#=======================================#
			$loginUrl = $_ENV['LOGIN_URL'];
		
			$body ="<p>Hello,<br><br>You are receiving this email because we received a password reset request for your account.<br><br><b><a href='".$loginUrl."password_reset.php?reset_token=".$reset_token."&email=".$email."'>CLICK HERE</a></b> to reset password<br><br>If you did not request a password reset, no further action is required.<br><br>Thanks,<br>Team Faroter";
		
			$headers = array(
					'Accept: application/json',
					'Content-Type: application/json',
					'Authorization: Bearer '.$token
					);
			$personalizations = '{
			  "personalizations": [
				{
				  "to": [
					{
					  "email": "'.$email.'"
					}
				  ]
				}
			  ],
			  "from": {
				"email": "'.$email_from.'"
			  },
			  "subject": "'.$subject.'",
			  "content": [
				{
				  "type": "text/html",
				  "value": "'.$body.'"
				}
			  ]
			}';
			#===============================
			#    C U R L    P O S T
			#===============================
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $sendGridUrl);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $personalizations);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($ch);
			curl_close($ch);
			// print_r($result);
			if(empty($result)){
				$outputData = array("success" => 200, "message" => "Email sent");
			}
			else{
				$jsonResponse = json_decode($result);
				// print_r($jsonResponse->errors[0]->message);
				$outputData = array("success" => 400, "message" => $jsonResponse->errors[0]->message);
			}
		}
		else{
			$outputData = array("success" => 400, "message" => "Invalid Email");
		}

		return json_encode($outputData);
	}
}