<?php

use WiwatSrt\BestAnswer\Listener;
use Illuminate\Contracts\Events\Dispatcher;

return function (Dispatcher $events) {
    $events->subscribe(Listener\AddClientAssets::class);
    $events->subscribe(Listener\AddPostBestAnswerAttributes::class);
    $events->subscribe(Listener\SelectBestAnswers::class);
};