<?php
namespace AdApi;

/**
 * Class AccountListRequest for handling account list requests
 */
Class AccountListRequest {
    private $input;

    /**
     * Create a new AccountListRequest
     */
    public function __construct(){
        $data = file_get_contents('php://input');
        $dataArary = json_decode($data, true);
        $this->input = $dataArary['input'];
    }

    public function getInput(){
        return $this->input;
    }
}
?>