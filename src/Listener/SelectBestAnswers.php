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
        $data = $event->data;
        $id = (int) Arr::get($data, $this->key);

        if ($id > 0 && $discussion->exists && isset($id) && $event->actor->can('selectBestAnswerInDiscussion', $discussion) && $discussion->best_answer_post_id != $id) {
            $discussion->best_answer_post_id = $id;
            $discussion->best_answer_user_id = $event->actor->id;

            Notification::where('type', 'selectBestAnswer')->where('subject_id', $discussion->id)->delete();
        }
    }
}
