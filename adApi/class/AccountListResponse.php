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
    
    public function send(){
        echo json_encode(array('accounts' => $this->accounts, 'error' => $this->error));
    }
}
?>