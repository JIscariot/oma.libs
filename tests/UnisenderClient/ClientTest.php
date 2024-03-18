<?php

declare(strict_types=1);

namespace Tests\UnisenderClient;

use Libs\Unisender\Client;
use Libs\Unisender\ContactData;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    const UNISENDER_API_KEY = '68xexp9m4738yfd8tog59bp83hzy98tjw73rktna';

    #https://cp.unisender.com/ru/v5/lists/20772703
    const UNISENDER_MAILING_NEWSLETTER_DY_LIST = 20772703;
    #https://cp.unisender.com/ru/v5/lists/20744011
    const UNISENDER_MAILING_NEWSLETTER_OMA_LIST = 20744011;
    #https://cp.unisender.com/ru/v5/lists/20772808
    const UNISENDER_MAILING_NEWSLETTER_PROMO_LIST = 20772808;

    public function testSubscribe()
    {
        $email = $name = (string)mt_rand() . '@test.com';

        $result = (new Client(self::UNISENDER_API_KEY))
            ->subscribe(new ContactData(
                email: $email,
                name: $name,
                lists: [
                    self:: UNISENDER_MAILING_NEWSLETTER_DY_LIST,
                    self:: UNISENDER_MAILING_NEWSLETTER_OMA_LIST,
                    self:: UNISENDER_MAILING_NEWSLETTER_PROMO_LIST,
                ],
            ));
        $this->assertIsNumeric($result);

        $unisenderClient = (new Client(self::UNISENDER_API_KEY));
        $result = $unisenderClient->isContactExists(new ContactData(
            email: $email,
        ));
        $this->assertTrue($result);
    }

    public function testUnsubscribe()
    {
        $email = (string)mt_rand() . '@test.com';

        $result = (new Client(self::UNISENDER_API_KEY))
            ->subscribe(new ContactData(
                email: $email,
                lists: [
                    self:: UNISENDER_MAILING_NEWSLETTER_DY_LIST,
                    self:: UNISENDER_MAILING_NEWSLETTER_OMA_LIST,
                    self:: UNISENDER_MAILING_NEWSLETTER_PROMO_LIST,
                ],
            ));
        $this->assertTrue($result);

        $result = (new Client(self::UNISENDER_API_KEY))
            ->unsubscribe(new ContactData(
                email: $email,
                lists: [
                    self:: UNISENDER_MAILING_NEWSLETTER_DY_LIST,
                    self:: UNISENDER_MAILING_NEWSLETTER_OMA_LIST,
                    self:: UNISENDER_MAILING_NEWSLETTER_PROMO_LIST,
                ],
            ));
        $this->assertTrue($result);
    }

    public function testCanDeleteContact()
    {
        $email = (string)mt_rand() . '@test.com';

        $unisenderClient = (new Client(self::UNISENDER_API_KEY));
        $result = $unisenderClient->subscribe(new ContactData(
            email: $email,
            lists: [
                self:: UNISENDER_MAILING_NEWSLETTER_DY_LIST,
                self:: UNISENDER_MAILING_NEWSLETTER_OMA_LIST,
                self:: UNISENDER_MAILING_NEWSLETTER_PROMO_LIST,
            ],
        ));
        $this->assertTrue($result);

        $unisenderClient = (new Client(self::UNISENDER_API_KEY));
        $result = $unisenderClient->deleteContact(new ContactData(
            email: $email,
        ));
        $this->assertTrue($result);

        $unisenderClient = (new Client(self::UNISENDER_API_KEY));
        $result = $unisenderClient->isContactExists(new ContactData(
            email: $email,
        ));
        $this->assertFalse($result);
    }

    public function testIsContactExists()
    {
        $email = (string)mt_rand() . '@test.com';

        $unisenderClient = (new Client(self::UNISENDER_API_KEY));
        $result = $unisenderClient->subscribe(new ContactData(
            email: $email,
            lists: [
                self:: UNISENDER_MAILING_NEWSLETTER_DY_LIST,
                self:: UNISENDER_MAILING_NEWSLETTER_OMA_LIST,
                self:: UNISENDER_MAILING_NEWSLETTER_PROMO_LIST,
            ],
        ));
        $this->assertTrue($result);


        $unisenderClient = (new Client(self::UNISENDER_API_KEY));
        $result = $unisenderClient->isContactExists(new ContactData(
            email: $email,
        ));
        $this->assertTrue($result);

        $unisenderClient = (new Client(self::UNISENDER_API_KEY));
        $result = $unisenderClient->deleteContact(new ContactData(
            email: $email,
        ));
        $this->assertTrue($result);

        $unisenderClient = (new Client(self::UNISENDER_API_KEY));
        $result = $unisenderClient->isContactExists(new ContactData(
            email: $email,
        ));
        $this->assertFalse($result);
    }
}