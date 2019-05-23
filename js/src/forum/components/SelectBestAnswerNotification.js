import Notification from 'flarum/components/Notification';

export default class SelectBestAnswerNotification extends Notification {
    icon() {
        return 'fas fa-check';
    }

    href() {
        const notification = this.props.notification;
        const discussion = notification.subject();

        return app.route.discussion(discussion, 1);
    }

    content() {
        return app.translator.trans('flarum-best-answer.forum.notification.content');
    }
}
