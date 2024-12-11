<?php 
    namespace AdApi;

    use mysqli;

    /**
     * Class Account for handling accounts
     */
    Class Account {
        private $accountNo;
        private $amount;
        private $name;

        /**
         * Create a new account
         * @param int $accountNo Account number
         * @param int $amount Amount on account
         * @param string $name Account name
         */
        public function __construct($accountNo, $amount, $name){
            $this->accountNo = $accountNo;
            $this->amount = $amount;
            $this->name = $name;
        }

        /**
         * Get account number
         * @param int $userId User id
         * @param mysqli $db Database connection
         * 
         * @return int Account number
         */
        public static function getAccountNo(int $userId, $db) : int {
            $sql = "SELECT accountNo FROM accounts WHERE user_id = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->bind_param('i', $userId);
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc();
            return $row['accountNo'];
        }

        /**
         * Get account object by account number
         * @param int $accountNo Account number
         * @param mysqli $db Database connection
         * 
         * @return Account Account object
         */
        public static function getAccount(int $accountNo, $db) : Account {
            $result = $db->query("SELECT * FROM accounts WHERE accountNo = $accountNo");
            $account = $result->fetch_assoc();
            $account = new Account($account['accountNo'], $account['amount'], $account['name']);
            return $account;
        }

        /**
         * Get account as array
         * 
         * @return array Account as array
         */
        public function getArray() : array {
            $array = [
                'accountNo' => $this->accountNo,
                'amount' => $this->amount,
                'name' => $this->name
            ];
            return $array;
        }

        public static function ifExists(int $accountNo, $db) : bool {
            $sql = "SELECT * FROM accounts WHERE accountNo = ?";
            $db->prepare($sql);
            $sql->bind_param('i', $accountNo);
            $sql->execute();
            $result = $sql->get_result();
            if($result->num_rows > 0){
                return true;
            } else {
                return false;
            }
        }

        public static function listAccounts($db, $input) : array {
            $sql = "SELECT accountNo, name FROM accounts JOIN user ON accounts.user_id = user.id WHERE user.email LIKE ?";
            $query = $db->prepare($sql);  
            $email = '%' . $input . '%';  
            $query->bind_param('s', $email);
            $query->execute();
            $result = $query->get_result();
            $accounts = [];
            while($row = $result->fetch_assoc()){
                $accounts[] = $row;
            }
            return $accounts;
        }
    }
?>