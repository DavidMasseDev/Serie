<?php

namespace App\Tests\Utils;

use App\Entity\User;
use App\Utils\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    //Les noms des méthodes ont obligation de commencer par 'Test'
    public function testSendNewUserNotificationToAdmin(): void
    {
        $user = new User();
        $user->setEmail("mail@test.com");

        $sender = new Sender();
        $sender->sendNewUserNotificationToAdmin($user);

        $this->assertContains(file_get_contents('notif.txt'), ["mail@test.com"], "File must contain mail@test.com");
    }
}
