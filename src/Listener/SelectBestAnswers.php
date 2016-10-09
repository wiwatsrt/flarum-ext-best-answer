<?php

namespace WiwatSrt\BestAnswer\Listener;

use Flarum\Event\DiscussionWillBeSaved;
use Flarum\Event\PostWillBeSaved;
use Flarum\Event\PostWasHidden;
use Illuminate\Contracts\Events\Dispatcher;

class SelectBestAnswers
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(DiscussionWillBeSaved::class, [$this, 'whenDiscussionWillBeSaved']);
        $events->listen(PostWillBeSaved::class, [$this, 'whenPostWillBeSaved']);
        $events->listen(PostWasHidden::class, [$this, 'whenPostWasHidden']);
    }

    /**
     * @param PostWillBeSaved $event
     */
    public function whenPostWillBeSaved(PostWillBeSaved $event)
    {
        $post = $event->post;
        $data = $event->data;

        if ($post->exists && isset($data['attributes']['isBestAnswer'])) {
            $isBestAnswer = (bool) $data['attributes']['isBestAnswer'];

            if ($isBestAnswer) {
                $post->discussion->best_answer_post_id = $post->id;
                $post->discussion->save();
            } else {
                $post->discussion->best_answer_post_id = 0;
                $post->discussion->save();
            }
        }
    }

    /**
     * @param DiscussionWillBeSaved $event
     */
    public function whenDiscussionWillBeSaved(DiscussionWillBeSaved $event)
    {
        $discussion = $event->discussion;
        $data = $event->data;

        if ($discussion->exists && isset($data['attributes']['hasBestAnswerPost'])) {
            $hasBestAnswerPost = (bool) $data['attributes']['hasBestAnswerPost'];
            $bestAnswerPostId = $data['attributes']['bestAnswerPostId'];

            if ($hasBestAnswerPost) {
                $discussion->best_answer_post_id = $bestAnswerPostId;
            } else {
                $discussion->best_answer_post_id = 0;
            }

            $discussion->save();
        }
    }

    /**
     * @param PostWasHidden $event
     */
    public function whenPostWasHidden(PostWasHidden $event)
    {
        $post = $event->post;

        if ($post->exists) {
            if ($post->discussion->best_answer_post_id == $post->id) {
                $post->discussion->best_answer_post_id = 0;
                $post->discussion->save();
            }
        }
    }
}