<?php

namespace App\Utils;

use App\Entity\User;

class Sender
{
    public function sendNewUserNotificationToAdmin(User $user): void
    {
        file_put_contents("notif.txt", $user->getEmail());

    }
}