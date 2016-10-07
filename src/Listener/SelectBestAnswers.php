<?php

namespace WiwatSrt\BestAnswer\Listener;

use Flarum\Event\PostWillBeSaved;
use Flarum\Event\PostWasHidden;
use Flarum\Event\PostWasRestored;
use Illuminate\Contracts\Events\Dispatcher;

class SelectBestAnswers
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(PostWillBeSaved::class, [$this, 'whenPostWillBeSaved']);
        $events->listen(PostWasHidden::class, [$this, 'whenPostWasHidden']);
        $events->listen(PostWasRestored::class, [$this, 'whenPostWasRestored']);
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
     * @param PostWasHidden $event
     */
    public function whenPostWasHidden(PostWasHidden $event)
    {
        $post = $event->post;

        if ($post->exists) {
            $isBestAnswer = (bool) $post->is_best_answer;

            if ($isBestAnswer) {
                $post->discussion->has_best_answer = false;
                $post->discussion->save();
            }
        }
    }

    /**
     * @param PostWasRestored $event
     */
    public function whenPostWasRestored(PostWasRestored $event)
    {
        $post = $event->post;

        if ($post->exists) {
            $isBestAnswer = (bool) $post->is_best_answer;

            if ($isBestAnswer) {
                $post->discussion->has_best_answer = true;
                $post->discussion->save();
            }
        }
    }
}