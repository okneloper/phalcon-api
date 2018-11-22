<?php 

class LoginCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function testEmptyLoginOrPasswordReturns401(ApiTester $I)
    {
        $I->sendPOST('/login', [
            'username' => 'user@exmaple.com',
            'password' => '',
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(401);
    }

    public function testCanLogIn(ApiTester $I)
    {
        $I->sendPOST('/login', [
            'username' => 'user@exmaple.com',
            'password' => 'password1',
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('token');
    }

    public function testCliFolderIsNotAccessible(ApiTester $I)
    {
        $I->sendGET('/cli/seed.php');

        $I->seeResponseCodeIs(404);
    }
}
