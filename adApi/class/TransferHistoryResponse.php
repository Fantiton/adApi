<?php 
namespace AdApi;
use mysqli;

/**
 * Class TransferHistoryResponse
 * klasa do obsługi odpowiedzi historii transferów
 */
class TransferHistoryResponse {
    private array $transfers;
    private string $error;


    public function __construct() {
        $this->error = "";
    }
    public function getJSON() {
        $array = array();
        $array['transfers'] = $this->transfers;
        $array['error'] = $this->error;
        return json_encode($array);
    }
    public function setTransfers(array $transfers) {
        $this->transfers = $transfers;
    }
    public function setError(string $error) {
        $this->error = $error;
    }
    public function send() {
        if($this->error != "") {
            header('HTTP/1.1 401 Unauthorized');
        } else {
            header('HTTP/1.1 200 OK');
        }
        header('Content-Type: application/json');
        echo $this->getJSON();
    }
}
?>