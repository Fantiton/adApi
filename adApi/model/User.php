<?php 
    namespace AdApi;

    use mysqli;

    Class User {
        private $id;
        private $email;
        private $passwordHash;

        static function login(string $login, string $password, mysqli $db) : int{
            $sql = "SELECT id FROM user WHERE email = ? AND passwordHash = ?";
            $query = $db->prepare($sql);
            $query->bind_param('ss', $login, $password);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows == 0) {
                throw new Exeption('Invalid login or poassword');
            }else{
                $user = $result->fetch_assoc();
                return $user['id'];
            }
        }
    }
?>