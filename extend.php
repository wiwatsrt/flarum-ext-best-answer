<?php

use FoF\Console\Extend\EnableConsole;
use Flarum\Extend;
use Flarum\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;
use WiwatSrt\BestAnswer\Listener;
use WiwatSrt\BestAnswer\Provider;
use WiwatSrt\BestAnswer\Access;

return [
    new EnableConsole,

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__ . '/less/forum/extension.less'),

    new Extend\Locales(__DIR__ . '/locale'),

    function (Application $app, Dispatcher $events) {
        $events->subscribe(Listener\SelectBestAnswers::class);
        $events->subscribe(Listener\AddBestAnswerRelationship::class);
        $events->subscribe(Listener\RegisterConsoleCommand::class);
        $events->subscribe(Listener\RegisterNotification::class);

        $events->subscribe(Access\DiscussionPolicy::class);

        $app->register(Provider\ConsoleProvider::class);
    },
];
