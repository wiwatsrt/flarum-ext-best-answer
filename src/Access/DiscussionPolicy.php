<?php


namespace WiwatSrt\BestAnswer\Access;

use Flarum\Discussion\Discussion;
use Flarum\User\AbstractPolicy;
use Flarum\User\User;

class DiscussionPolicy extends AbstractPolicy
{
    protected $model = Discussion::class;

    public function selectBestAnswerInDiscussion(User $user, Discussion $discussion) {
        return $user->id == $discussion->user_id
            ? $user->can('selectBestAnswer', $discussion)
            : $user->can('selectBestAnswerNotOwnDiscussion', $discussion);
    }
}