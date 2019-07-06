<?php

namespace WiwatSrt\BestAnswer\Notification;

use Flarum\Notification\Blueprint\BlueprintInterface;
use Flarum\Discussion\Discussion;

class SelectBestAnswerBlueprint implements BlueprintInterface
{
    public $discussion;

    public function __construct(Discussion $discussion)
    {
        $this->discussion = $discussion;
    }

    /**
     * Get the user that sent the notification.
     *
     * @return \Flarum\User\User|null
     */
    public function getFromUser()
    {
        return $this->discussion->user;
    }

    /**
     * Get the model that is the subject of this activity.
     *
     * @return \Flarum\Database\AbstractModel|null
     */
    public function getSubject()
    {
        return $this->discussion;
    }

    /**
     * Get the data to be stored in the notification.
     *
     * @return array|null
     */
    public function getData()
    {
//        return ['discussion_id']
    }

    /**
     * Get the serialized type of this activity.
     *
     * @return string
     */
    public static function getType()
    {
        return 'selectBestAnswer';
    }

    /**
     * Get the name of the model class for the subject of this activity.
     *
     * @return string
     */
    public static function getSubjectModel()
    {
        return Discussion::class;
    }
}