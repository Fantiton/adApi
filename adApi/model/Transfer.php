<?php
    namespace AdApi;
/**
 * Class Transfer for handling transfers
 */
class Transfer {
    /**
     * Create a new transfer
     * @param int $source Source account number
     * @param int $target Target account number
     * @param int $amount Amount to transfer
     * @param mysqli $db Database connection
     * 
     * @return void
     */
    public static function new(int $source, int $target, int $amount, $db) : void {
        $db->begin_transaction();
        try {
            $sql = "UPDATE accounts SET amount = amount - ? WHERE accountNo = ?";
            $query = $db->prepare($sql);
            $query->bind_param('ii', $amount, $source);
            $query->execute();

            $sql = "UPDATE accounts SET amount = amount + ? WHERE accountNo = ?";
            $query = $db->prepare($sql);
            $query->bind_param('ii', $amount, $target);
            $query->execute();

            $sql = "INSERT INTO transfer (source, target, amount) VALUES (?, ?, ?)";
            $query = $db->prepare($sql);
            $query->bind_param('iii', $source, $target, $amount);
            $query->execute();
            $db->commit();
        } catch (mysqli_sql_exception $e) {
            $db->rollback();
            throw new Exception('Transfer failed');
        }
    }

    public static function getTransfers(int $accountNo, $db) : array {
        $sql = "SELECT * FROM transfer WHERE source = ? OR target = ?";
        $query = $db->prepare($sql);
        $query->bind_param('ii', $accountNo, $accountNo);
        $query->execute();
        $result = $query->get_result();
        $transfers = [];
        while($row = $result->fetch_assoc()){
            $transfers[] = $row;
        }
        return $transfers;
    }
}
?>