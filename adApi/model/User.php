<?php
    namespace AdApi;
    use mysqli;
    use Exception;
/**
 * Class User for handling user login
 */
class User {
    /**
     * Login user
     * @param string $login User login
     * @param string $password User password
     * @param mysqli $db Database connection
     * 
     * @return int User id
     */
    static function login(string $login, string $password, $db) : int {
        $sql = "SELECT id, passwordHash FROM user WHERE email = ?";
        $query = $db->prepare($sql);
        $query->bind_param('s', $login);
        $query->execute();
        $result = $query->get_result();
        if($result->num_rows == 0) {
            throw new Exception('Invalid login or password');
        } else {
            $user = $result->fetch_assoc();
            $id = $user['id'];
            $hash = $user['passwordHash'];
            if(password_verify($password, $hash)) {
                return $id;
            } else {
                throw new Exception('Invalid login or password');
            }
        }
    }
}
?>