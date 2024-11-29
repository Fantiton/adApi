<?php 
namespace AdApi;
use mysqli;

/**
 * Class TransferHistoryRequest
 * klasa do obsługi żądania historii transferów
 */
class TransferHistoryRequest{
    private $token;
    
    public function __construct(){
        $data = file_get_contents('php://input');
        $dataArray = json_decode($data, true);

        $this->token = $dataArray['token'];
    }

    public function getToken(){
        return $this->token;
    }
}
?>