<?php

namespace WiwatSrt\BestAnswer\Listener;

use Flarum\Discussion\Event\Saving;
use Flarum\Notification\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class SelectBestAnswers
{
    private $key = 'attributes.bestAnswerPostId';

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
        if (!Arr::has($event->data, $this->key)) return;

        $discussion = $event->discussion;
        $id = (int) Arr::get($event->data, $this->key);

        if (!isset($id) || !$discussion->exists || $discussion->best_answer_post_id == $id || $event->actor->cannot('selectBestAnswerInDiscussion', $discussion)) {
            return;
        }

        if ($id > 0) {
            $discussion->best_answer_post_id = $id;
            $discussion->best_answer_user_id = $event->actor->id;

            Notification::where('type', 'selectBestAnswer')->where('subject_id', $discussion->id)->delete();
        } else if ($id == 0) {
            $discussion->best_answer_post_id = null;
            $discussion->best_answer_user_id = null;
        }
    }
}
