<?php

use Flarum\Database\Migration;
use Flarum\Group\Group;
return Migration::addPermissions([
    'discussion.selectBestAnswer' => Group::MEMBER_ID
]);