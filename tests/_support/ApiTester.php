<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    public function authenticate()
    {
        $this->loginWith('username', 'password');
    }

   /**
    * Define custom actions here
    */
    public function loginWith($username, $password)
    {
        $this->sendPOST('/login', compact('username', 'password'));
        $token = $this->grabDataFromResponseByJsonPath('token')[0];
        $this->amBearerAuthenticated($token);
    }
}
