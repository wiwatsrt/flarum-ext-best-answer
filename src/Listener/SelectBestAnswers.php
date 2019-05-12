<?php

namespace WiwatSrt\BestAnswer\Listener;

use Flarum\Discussion\Event\Saving;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class SelectBestAnswers
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Saving::class, [$this, 'whenSaving']);
    }

    /**
     * @param Saving $event
     */
    public function whenSaving(Saving $event)
    {
        $discussion = $event->discussion;
        $data = $event->data;
        $id = (int) Arr::get($data, 'attributes.bestAnswerPostId');

        if ($discussion->exists && isset($id) && $event->actor->can('selectBestAnswerInDiscussion', $discussion)) {
            $discussion->best_answer_post_id = $id;
            $discussion->best_answer_user_id = $event->actor->id;
        }
    }
}
