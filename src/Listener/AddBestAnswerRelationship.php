<?php

namespace WiwatSrt\BestAnswer\Listener;

use Flarum\Api\Controller;
use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Api\Serializer\PostSerializer;
use Flarum\Discussion\Discussion;
use Flarum\Post\Post;
use Flarum\Api\Event\WillGetData;
use Flarum\Event\GetApiRelationship;
use Flarum\Event\GetModelRelationship;
use Flarum\Api\Event\Serializing;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Events\Dispatcher;

class AddBestAnswerRelationship
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(GetModelRelationship::class, [$this, 'getModelRelationship']);
        $events->listen(GetApiRelationship::class, [$this, 'getApiAttributes']);
        $events->listen(Serializing::class, [$this, 'prepareApiAttributes']);
        $events->listen(WillGetData::class, [$this, 'includeBestAnswerPost']);
    }

    /**
     * @param GetModelRelationship $event
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function getModelRelationship(GetModelRelationship $event)
    {
        if ($event->isRelationship(Discussion::class, 'bestAnswerPost')) {
            return $event->model->belongsTo(Post::class, 'best_answer_post_id');
        }
    }

    /**
     * @param GetApiRelationship $event
     * @return \Tobscure\JsonApi\Relationship|null
     */
    public function getApiAttributes(GetApiRelationship $event)
    {
        if ($event->isRelationship(DiscussionSerializer::class, 'bestAnswerPost')) {
            return $event->serializer->hasOne($event->model, PostSerializer::class, 'bestAnswerPost');
        }
    }

    /**
     * @param Serializing $event
     */
    public function prepareApiAttributes(Serializing $event)
    {
        if ($event->isSerializer(DiscussionSerializer::class)) {
            $event->attributes['canSelectBestAnswer'] = (bool) $event->actor->can('selectBestAnswer', $event->model);
            $event->attributes['canSelectBestAnswerOwnPost'] = (bool) $this->settings->get('flarum-best-answer.allow_select_own_post');
            $event->attributes['startUserId'] = $event->model->user_id;
        }
    }

    /**
     * @param WillGetData $event
     */
    public function includeBestAnswerPost(WillGetData $event)
    {
        if ($event->isController(Controller\ListDiscussionsController::class) || $event->isController(Controller\ShowDiscussionController::class)) {
            $event->addInclude('bestAnswerPost');
        }
    }
}
