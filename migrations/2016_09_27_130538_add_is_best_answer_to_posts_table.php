<?php

use Flarum\Database\Migration;

return Migration::addColumns('posts', [
    'is_best_answer' => ['boolean', 'default' => 0]
]);