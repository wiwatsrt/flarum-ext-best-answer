import { extend } from 'flarum/extend';
import app from 'flarum/app';
import PermissionGrid from 'flarum/components/PermissionGrid';
import BestAnswerSettingsModal from './components/BestAnswerSettingsModal';

app.initializers.add('wiwatsrt-best-answer', () => {
    app.extensionSettings['wiwatsrt-best-answer'] = () => app.modal.show(new BestAnswerSettingsModal());

    extend(PermissionGrid.prototype, 'replyItems', function(items) {
        items.add('selectBestAnswer', {
            icon: 'far fa-comment',
            label: app.translator.trans('flarum-best-answer.admin.permissions.best_answer'),
            permission: 'discussion.selectBestAnswer',
        });

        items.add('selectBestAnswerNotAuthor', {
            icon: 'far fa-comment',
            label: app.translator.trans('flarum-best-answer.admin.permissions.best_answer_not_own_discussion'),
            permission: 'discussion.selectBestAnswerNotOwnDiscussion',
        });
    });
});
