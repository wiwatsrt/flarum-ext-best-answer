<?php

use Flarum\Extend;
use Illuminate\Contracts\Events\Dispatcher;
use WiwatSrt\BestAnswer\Listener;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__ . '/less/forum/extension.less'),

    new Extend\Locales(__DIR__ . '/locale'),

    function (Dispatcher $events) {
        $events->subscribe(Listener\SelectBestAnswers::class);
        $events->subscribe(Listener\AddBestAnswerRelationship::class);
    },
];
