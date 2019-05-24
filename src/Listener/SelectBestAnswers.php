<?php

namespace WiwatSrt\BestAnswer\Listener;

use Flarum\Discussion\Event\Saving;
use Illuminate\Contracts\Events\Dispatcher;

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

        if ($discussion->exists && isset($data['attributes']['bestAnswerPostId']) && $event->actor->id === $event->discussion->user_id) {
            $discussion->best_answer_post_id = $data['attributes']['bestAnswerPostId'];
            $discussion->save();
        }
    }
}
