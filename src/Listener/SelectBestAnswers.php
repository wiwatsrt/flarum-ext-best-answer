<?php

namespace WiwatSrt\BestAnswer\Listener;

use Flarum\Event\PostWasDeleted;
use Flarum\Event\PostWillBeSaved;
use Illuminate\Contracts\Events\Dispatcher;

class SelectBestAnswers
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(PostWillBeSaved::class, [$this, 'whenPostWillBeSaved']);
        $events->listen(PostWasDeleted::class, [$this, 'whenPostWasDeleted']);
    }

    /**
     * @param PostWillBeSaved $event
     */
    public function whenPostWillBeSaved(PostWillBeSaved $event)
    {
        $post = $event->post;
        $data = $event->data;

        if ($post->exists && isset($data['attributes']['isBestAnswer']) && $post->number !== 1) {
            $isBestAnswer = (bool) $data['attributes']['isBestAnswer'];

            if ($isBestAnswer) {
                // Remove other best answer in same discussion
                $post->where([
                    'discussion_id' => $post->discussion->id,
                    'is_best_answer' => 1
                ])->update(['is_best_answer' => 0]);

                $post->discussion->has_best_answer = true;
                $post->discussion->save();
            } else {
                $post->discussion->has_best_answer = false;
                $post->discussion->save();
            }

            $post->is_best_answer = $isBestAnswer;
            $post->save();
        }
    }

    /**
     * @param PostWasDeleted $event
     */
    public function whenPostWasDeleted(PostWasDeleted $event)
    {
        $post = $event->post;
        $data = $event->data;

        if ($post->exists && isset($data['attributes']['isBestAnswer'])) {
            $isBestAnswer = (bool) $data['attributes']['isBestAnswer'];

            if ($isBestAnswer) {
                $post->discussion->has_best_answer = false;
                $post->discussion->save();
            }
        }
    }
}