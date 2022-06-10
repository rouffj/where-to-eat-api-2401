<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    public function onUserCreated($event)
    {
        $mail = [
            'to' => $event->getUser()->getEmail(),
            'subject' => $event->getUser()->getFirstName() . ' your account has been created',
            'content' => 'Happy to see you on whereToEat ;)',
        ];

        dump($mail);
    }

    public static function getSubscribedEvents()
    {
        return [
            'user_created' => 'onUserCreated',
        ];
    }
}
