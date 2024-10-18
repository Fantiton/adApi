<?php 
    Class Token {
        static function new(string $ip, int $userId) : string {
            $hash = hash('sha256', $ip . $userId . time());
        }
    }
?>