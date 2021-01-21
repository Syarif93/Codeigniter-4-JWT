<?php namespace App\Controllers;

use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use \Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;

// Header
// header("Access-Control-Alow-Origin: * ");
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With");

class Auth extends ResourceController
{
	protected $modelName = 'App\Models\UserModel';
	
	public function index()
	{
		$token = null;
		$authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
		if(!$authHeader) {
			$token = null;
		} else {
			$arr = explode(" ", $authHeader);
			$token = $arr[1];
		}

		if($token) {
			try {
				$verifyToken = $this->model->verifyToken($token);

				if ($verifyToken) {
					$data = [
						"message" => "Access acepted",
						"users" => $this->model->findAll()
					];
	
					return $this->respond($data, 200);
				}
			} catch(\Exception $e) {
				return $this->respond(["error" => $e->getMessage()], 401);
			}
		}

		return $this->respond(["error" => "Unauthorized"], 401);
	}
	
	public function register()
	{
		$validation = \Config\Services::validation();

		// get request body
		$body = $this->request->getJSON();
		$name = $body->name;
		$email = $body->email;
		$password = $body->password;
		$pass_confirm = $body->pass_confirm;

		$dataValidate = [
			'name' => $name,
			'email' => $email,
			'password' => $password,
			'pass_confirm' => $pass_confirm
		];

		// Validate data register
		if($validation->run($dataValidate, 'user') === false) {
			return $this->respond($validation->getErrors(), 400);
		}

		// Hash password
		$password_hash = password_hash($password, PASSWORD_BCRYPT);

		// Generate data register
		$dataRegister = [
			'id' => Uuid::uuid4()->toString(),
			'name' => $name,
			'email' => $email,
			'password' => $password === "" | $password === null ? $password : $password_hash,
			'created_at' => Time::now(app_timezone(), "id_ID"),
			'updated_at' => Time::now(app_timezone(), "id_ID")
		];

		// Insert to database
		$register = $this->model->register($dataRegister);

		// if success return message success
		if($register === true) {
			$data = [
				"message" => "Registrasi Berhasil"
			];

			return $this->response->setStatusCode(200)->setJSON($data);
		}
		
		// if fail return message fail
		return $this->respond(["message" => "Internal server error"], 500);
	}

	public function login()
	{
		$body = $this->request->getJSON();
		$email = $body->email;
		$password = $body->password;
		
		$loginCheck = $this->model->loginCheck($email);

		if(!empty($loginCheck)) {
			if(password_verify($password, $loginCheck['password'])) {
				$payload = [
					"jti" => $loginCheck['id'],
					"iss" => base_url(),
					"aud" => base_url(),
					"iat" => time(),
					"nbf" => time() + 10
				];

				$token = JWT::encode($payload, $this->model->privateKey());

				return $this->respond([
					"message" => "Login berhasil",
					"token" => $token
				], 200);
			}
		}

		return $this->respond(["message" => "Email atau password salah"], 401);
	}

	//--------------------------------------------------------------------

}