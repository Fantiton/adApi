<?php 
    namespace AdApi;
    /**
     * Class Token for creating and checking tokens
     */
    Class Token {

        /**
         * Create a new token
         * @param int $userId User id
         * @param string $ip User ip
         * @param mysqli $db Database connection
         * 
         * @return string Token
         */
        static function new(int $userId, string $ip, $db) : string {
            $hash = hash('sha256', $ip . $userId . time());
            $sql = "INSERT INTO token (token, ip, user_id) VALUES (?, ?, ?)";
            $query = $db->prepare($sql);
            $query->bind_param('ssi', $hash, $ip, $userId);

            if (!$query->execute()) {
                throw new Exeption('Could not create token');
            } else {
                return $hash;
            }
        }

        /**
         * Check if token is valid
         * @param string $token Token
         * @param string $ip User ip
         * @param mysqli $db Database connection
         * 
         * @return bool True if token is valid, False if not
         */
        static function check(string $token, string $ip, $db) : bool{
            $sql = "SELECT * FROM token Where token = ? AND ip = ?";
            $query = $db->prepare($sql);
            $query->bind_param('ss', $token, $ip);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows == 0) {
                return false;
            }else{
                return true;
            }
        }

        /**
         * Get user id from token
         * @param string $token Token
         * @param mysqli $db Database connection
         * 
         * @return int User id
         */
        static function getUserId(string $token, $db) : int{
            $sql = "SELECT user_id FROM token WHERE token = ? ORDER BY id DESC LIMIT 1";
            $query = $db->prepare($sql);
            $query->bind_param('s', $token);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows == 0) {
                throw new Exception('Invalid token');
            } else{
                $row = $result->fetch_assoc();
                return $row['user_id'];
            }
        }
    }
?>