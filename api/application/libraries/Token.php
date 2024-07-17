<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token {
	private $secret_key = "blitz_pos";
	private $encrypt = "HS256";

	public function set_token($args=[])
	{
		$data = array(
            'iat'  => time(),
            'exp'  => time()+7200,
            'data' => $args["data"]
        );

        return JWT::encode($data, $this->secret_key, $this->encrypt);
	}

	public function token_valido($token)
	{
		if (is_string($token) && !empty($token)) {
			try {
                $decoded = JWT::decode($token, new Key($this->secret_key, $this->encrypt));
                return true;
            } catch (Exception $e) {
                return false;
            }
		}

		return false;
	}
}

/* End of file Token.php */
/* Location: ./application/libraries/Token.php */ ?>