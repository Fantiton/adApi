<?php 
    Class Transfer {
        public static function new(int $source, int $target, int $amount) : void {
            try{
                $db->begin_transaction();
                $sql = "UPDATE account SET amount = amount - ? WHERE accountNo = ?";
                $query = $db->prepare($sql);
                $query->bind_param('ii', $amount, $source);
                $query->execute();}
                $sql = "INSERT INTO transfer (source, target, amount) VALUES (?, ?, ?)";
                $query = $db->prepare($sql);
                $query->bind_param('iii', $source, $target, $amount);
                $query->execute();
                $db->commit();
            catch(mysqli_sql_exception $e){
                $db->rollback();
                throw new Exception('Transfer failed');
            }
        }
    }
?>