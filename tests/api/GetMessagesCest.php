<?php 

class GetMessagesCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function accessIsRestricted(ApiTester $I)
    {
        $I->sendGET('/messages');
        $I->seeResponseCodeIs(401);
    }

    public function returnsMessageHistory(ApiTester $I)
    {
        $I->authenticate();

        $I->sendGET('/messages');
        $I->seeResponseCodeIs(200);

        $I->seeResponseIsJson();

        $I->seeResponseJsonMatchesJsonPath('data');
        #$I->seeResponseJsonMatchesJsonPath('data.0');
    }
}
