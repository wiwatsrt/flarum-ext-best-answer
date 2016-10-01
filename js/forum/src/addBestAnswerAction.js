import app from 'flarum/app';
import { extend } from 'flarum/extend';
import Button from 'flarum/components/Button';
import CommentPost from 'flarum/components/CommentPost';

export default function() {
    extend(CommentPost.prototype, 'actionItems', function (items) {

        var post = this.props.post;
        if (post.isHidden() || post.attribute('number') == 1) return;

        var discussion = this.props.post.discussion();
        var isBestAnswer = post.attribute('isBestAnswer');

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