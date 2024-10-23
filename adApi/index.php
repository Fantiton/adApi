<?php
    require_once("Route.php");
    require_once("model\Account.php");
    require_once("model\User.php");
    require_once("model\Token.php");
    $db = new mysqli('localhost', 'root', '', 'filip_ad_api');

    use Steampixel\Route;
    use AdApi\Account;

    Route::add('/', function() {
        echo 'rizz';
    });

    Route::add('/login', function() use($db) {

        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $ip = $_SERVER['REMOTE_ADDR'];
        try{
            $id = User::login($data['login'], $data['password'], $db);
            $token = Token::new($id, $ip, $db);
            header('Content-Type: application/json');
            return json_encode(['token' => $token]);
        } catch(Exception $e){
            header('Content-Type: application/json');
            return json_encode(['error' => $e->getMessage()]);
        }
        var_dump($data);
    }, 'post');

    Route::add('/account/([0-9]*)', function($accountNo) use($db) {
        $account = Account::getAccount($accountNo, $db);
        header('Content-Type: application/json');
        return json_encode($account->getArray());
    });

    Route::run('/adApi/adApi');
    $db->close();
?>
 