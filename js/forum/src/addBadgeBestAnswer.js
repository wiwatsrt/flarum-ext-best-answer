import app from 'flarum/app';
import { extend } from 'flarum/extend';
import Discussion from 'flarum/models/Discussion';
import Badge from 'flarum/components/Badge';

export default function() {
    extend(Discussion.prototype, 'badges', function (items) {
        if (this.attribute('isBestAnswer') && !items.has('hidden')) {
            items.add('bestAnswer', m(Badge, { type: 'bestAnswer', icon: 'check', label: app.translator.trans('flarum-best-answer.forum.best_answer') }));
        }
    });
}