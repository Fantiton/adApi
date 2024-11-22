<?php 
    namespace AdApi;

    class LoginRequest {
        public $login;
        public $password;

        public function __construct(string $data){
            $data = json_decode($data, true);
            $this->login = $data['login'];
            $this->password = $data['password'];
        }
        public function getLogin(){
            return $this->login;
        }
        public function getPassword(){
            return $this->password;
        }
    }
?>