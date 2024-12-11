<?php 
namespace AdApi;    

Class AccountListResponse{
    private $accounts = [];
    private $error = "";
    
    public function setAccounts($accounts){
        $this->accounts = $accounts;
    }
    
    public function setError($error){
        $this->error = $error;
    }

    public function getJSON() {
        $array = array();
        $array['accounts'] = $this->accounts;
        $array['error'] = $this->error;
        return json_encode($array);
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