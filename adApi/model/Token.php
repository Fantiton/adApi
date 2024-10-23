<?php 
    Class Token {
        static function new(int $userId, string $ip, mysqli $db) : string {
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

        static function check(string $token, string $ip, mysqli $db) : bool{
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
    }
?>