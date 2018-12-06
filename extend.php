<?php

use Flarum\Extend;
use Illuminate\Contracts\Events\Dispatcher;
use WiwatSrt\BestAnswer\Listener;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->content(Listener\SelectBestAnswers::class)
        ->content(Listener\AddBestAnswerRelationship::class)
        ->css(__DIR__ . '/less/forum/extension.less'),
    new Extend\Locales(__DIR__ . '/locale'),
];
