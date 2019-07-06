<?php

namespace WiwatSrt\BestAnswer\Listener;

use Flarum\Api\Controller;
use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Api\Serializer\PostSerializer;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\Discussion\Discussion;
use Flarum\Post\Post;
use Flarum\Api\Event\WillGetData;
use Flarum\Event\GetApiRelationship;
use Flarum\Event\GetModelRelationship;
use Flarum\Api\Event\Serializing;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
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

        if ($event->isRelationship(Discussion::class, 'bestAnswerUser')) {
            return $event->model->belongsTo(User::class, 'best_answer_user_id');
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

        if ($event->isRelationship(DiscussionSerializer::class, 'bestAnswerUser')) {
            return $event->serializer->hasOne($event->model, UserSerializer::class, 'bestAnswerUser');
        }
    }

    /**
     * @param Serializing $event
     */
    public function prepareApiAttributes(Serializing $event)
    {
        if ($event->isSerializer(DiscussionSerializer::class)) {
            $event->attributes['canSelectBestAnswer'] = $event->actor->can('selectBestAnswerInDiscussion', $event->model);

            $event->attributes['startUserId'] = $event->model->user_id;
            $event->attributes['firstPostId'] = $event->model->first_post_id;
        }

        if ($event->isSerializer(ForumSerializer::class)) {
            $event->attributes['canSelectBestAnswerOwnPost'] = app('flarum.settings')->get('flarum-best-answer.allow_select_own_post');
        }
    }

    /**
     * @param WillGetData $event
     */
    public function includeBestAnswerPost(WillGetData $event)
    {
        if ($event->isController(Controller\ListDiscussionsController::class) || $event->isController(Controller\ShowDiscussionController::class)) {
            $event->addInclude('bestAnswerPost');
            $event->addInclude('bestAnswerUser');
        }
    }
}
