<?php
    require_once("Route.php");
    require_once("model\Account.php");
    require_once("model\User.php");
    require_once("model\Token.php");
    require_once("model\Transfer.php");
    require_once("class\LoginRequest.php");
    $db = new mysqli('localhost', 'root', '', 'filip_ad_api');

    use Steampixel\Route;
    use AdApi\Account;

    Route::add('/', function() {
        echo 'rizz';
    });

    Route::add('/login', function() use($db) {
        $data = file_get_contents('php://input');
        $request = new LoginRequest($data);
        $ip = $_SERVER['REMOTE_ADDR'];
        try{
            $id = User::login($request->getLogin(), $request->getPassword, $db);
            $token = Token::new($id, $ip, $db);
            header('Content-Type: application/json');
            echo json_encode(['token' => $token]);
        } catch(Exception $e){
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }
    }, 'post');

    Route::add('/account/details', function() use($db) {
        $data = file_get_contents('php://input');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
        $dataArray = json_decode($data, true);
        $token = $dataArray['token'];
        if(!Token::check($token, $_SERVER['REMOTE_ADDR'], $db)){
            header('Content-Type: application/json');
            return json_encode(['error' => 'Invalid token']);
        }
        $userId = Token::getUserId($token, $db);
        $accountNo = Account::getAccountNo($userId, $db);
        $account = Account::getAccount($accountNo, $db);
        return json_encode($account->getArray());
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

    Route::add('/account/([0-9]*)', function($accountNo) use($db) {
        $account = Account::getAccount($accountNo, $db);
        header('Content-Type: application/json');
        return json_encode($account->getArray());
    });

    Route::run('/adApi/adApi');
    $db->close();
?>