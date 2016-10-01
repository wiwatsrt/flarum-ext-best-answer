<?php

use Flarum\Database\Migration;

return Migration::addColumns('discussions', [
    'has_best_answer' => ['boolean', 'default' => 0]
]);