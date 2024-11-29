<?php
    require_once("Route.php");
    require_once("model\Account.php");
    require_once("model\User.php");
    require_once("model\Token.php");
    require_once("model\Transfer.php");
    require_once("class\LoginRequest.php");
    require_once("class\LoginResponse.php");
    require_once("class\AccountDetailsRequest.php");
    require_once("class\AccountDetailsResponse.php");
    require_once("class\TransferHistoryRequest.php");
    require_once("class\TransferHistoryResponse.php");

    use Steampixel\Route;
    use AdApi\Account;
    use AdApi\User;
    use AdApi\Token;
    use AdApi\Transfer;
    use AdApi\LoginRequest;
    use AdApi\LoginResponse;
    use AdApi\AccountDetailsRequest;
    use AdApi\AccountDetailsResponse;
    use AdApi\TransferHistoryRequest;
    use AdApi\TransferHistoryResponse;  
    
    $db = new mysqli('localhost', 'root', '', 'filip_ad_api');

    Route::add('/', function() {
        echo 'rizz';
    });

    Route::add('/login', function() use($db) {
        $data = file_get_contents('php://input');
        $request = new LoginRequest($data);
        try{
            $id = User::login($request->getLogin(), $request->getPassword(), $db);
            $ip = $_SERVER['REMOTE_ADDR'];
            $token = Token::new($id, $ip, $db);
            $response = new LoginResponse($token, "");
            $response->send();
        } catch(Exception $e){
            $response = new LoginResponse("", $e->getMessage());
            $response->send();
            return;
        }
    }, 'post');

    Route::add('/account/details', function() use($db) {
        $request = new AccountDetailsRequest();
        $response = new AccountDetailsResponse();
        if(!Token::check($request->getToken(), $_SERVER['REMOTE_ADDR'], $db)){
            $response->setError('Invalid token');
        }

        $userId = Token::getUserId($request->getToken(), $db);
        $accountNo = Account::getAccountNo($userId, $db);
        $account = Account::getAccount($accountNo, $db);

        $response->setAccount($account->getArray());
        $response->send();
    }, 'post');

    Route::add('/transfer/new', function() use($db) {
        $data = file_get_contents('php://input');
        $dataArray = json_decode($data, true);
        $token = $dataArray['token']; 

        if(!Token::check($token, $_SERVER['REMOTE_ADDR'], $db)){
            header('HTTP/1.1 401 Unauthorized');
            return json_encode(['error' => 'Invalid token']);
        }


        $userId = Token::getUserId($token, $db);
        $sourceAccount = Account::getAccount(Account::getAccountNo($userId, $db), $db);
        $sourceArray = $sourceAccount->getArray();
        
        $target = $dataArray['target'];
        $amount = $dataArray['amount'];

        if($amount <= 0){
            header('HTTP/1.1 400 Bad Request');
            return json_encode(['error' => 'Invalid amount']);
        }

        if($sourceArray['amount'] < $amount){
            header('HTTP/1.1 400 Bad Request');
            return json_encode(['error' => 'Insufficient funds']);
        }

        Transfer::new($sourceArray['accountNo'], $target, $amount, $db);
        header('Status: 200');
        return json_encode(['status' => 'ok']);
    }, 'post');

    Route::add('/transfer/history', function() use($db) {
        $request = new TransferHistoryRequest();
        $response = new TransferHistoryResponse();
        if(!Token::check($request->getToken(), $_SERVER['REMOTE_ADDR'], $db)){
            header('HTTP/1.1 401 Unauthorized');
            return json_encode(['error' => 'Invalid token']);
        }

        $userId = Token::getUserId($request->getToken(), $db);
        $accountNo = Account::getAccountNo($userId, $db);

        $response->setTransfers(Transfer::getTransfers($accountNo, $db));
        $response->send();
    }, 'post');

    Route::add('/account/([0-9]*)', function($accountNo) use($db) {
        $account = Account::getAccount($accountNo, $db);
        header('Content-Type: application/json');
        return json_encode($account->getArray());
    });

    Route::run('/adApi/adApi');
    $db->close();
?>