import app from 'flarum/app';
import { extend } from 'flarum/extend';
import Button from 'flarum/components/Button';
import CommentPost from 'flarum/components/CommentPost';

export default function() {
    extend(CommentPost.prototype, 'actionItems', function (items) {
        var post = this.props.post;
        var discussion = this.props.post.discussion();
        var startUserId = discussion.attribute('startUserId');
        var canSelectBestAnswer = discussion.attribute('canSelectBestAnswer');
        var isBestAnswer = post.attribute('isBestAnswer');

        if (post.isHidden() || !canSelectBestAnswer || post.number() == 1 || !app.session.user || startUserId != app.session.user.id()) return;

        items.add('bestAnswer', Button.component({
            children: app.translator.trans(isBestAnswer ? 'flarum-best-answer.forum.remove_best_answer' : 'flarum-best-answer.forum.this_best_answer'),
            className: 'Button Button--link',
            onclick: function onclick() {
                isBestAnswer = !isBestAnswer;
                post.save({isBestAnswer: isBestAnswer});
                discussion.pushAttributes({isBestAnswer: isBestAnswer});
            }
        }));
    });
}