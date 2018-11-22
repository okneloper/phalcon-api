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

    public function testAcceptsMessage(ApiTester $I)
    {
        $I->authenticate();

        $now = date('Y-m-d H:i:s');
        $posted_text = 'Message @' . $now;
        $I->sendPOST('/messages', [
            'text' => $posted_text,
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);

        $I->sendGET('/messages');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $I->seeResponseJsonMatchesJsonPath('data.0');
        #$I->seeResponseJsonMatchesJsonPath('data.0');

        $messages = $I->grabDataFromResponseByJsonPath('data')[0];

        $found = false;
        foreach ($messages as $message) {
            if ($message['text'] === $posted_text) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $I->fail("Message not returned");
        }
    }

    public function testValidatesMessages(ApiTester $I)
    {
        $I->authenticate();

        $I->sendPOST('/messages', [
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(422);
    }
}
