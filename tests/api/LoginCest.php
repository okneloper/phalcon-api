<?php 

class LoginCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function testCanLogIn(ApiTester $I)
    {
        $I->sendPOST('/login', [
            'username' => 'user',
            'password' => 'password',
        ]);

        $I->seeResponseCodeIs(200);
        #$I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('token');
    }
}
