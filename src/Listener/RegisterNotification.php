<?php

namespace WiwatSrt\BestAnswer\Listener;

use Flarum\Api\Serializer\BasicDiscussionSerializer;
use Flarum\Event\ConfigureNotificationTypes;
use Illuminate\Contracts\Events\Dispatcher;
use WiwatSrt\BestAnswer\Notification\SelectBestAnswerBlueprint;

class RegisterNotification
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureNotificationTypes::class, [$this, 'addNotificationType']);
    }

    public function addNotificationType(ConfigureNotificationTypes $event)
    {
        $event->add(SelectBestAnswerBlueprint::class, BasicDiscussionSerializer::class, ['alert']);
    }
}