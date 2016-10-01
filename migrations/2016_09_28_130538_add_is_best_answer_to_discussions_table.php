<?php

use Flarum\Database\Migration;

return Migration::addColumns('discussions', [
    'is_best_answer' => ['boolean', 'default' => 0]
]);