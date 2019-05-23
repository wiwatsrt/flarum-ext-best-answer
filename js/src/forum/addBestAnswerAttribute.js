import app from 'flarum/app';
import { extend } from 'flarum/extend';
import CommentPost from 'flarum/components/CommentPost';
import PostComponent from 'flarum/components/Post';

export default function() {
    extend(CommentPost.prototype, 'headerItems', function(items) {
        const post = this.props.post;

        if (
            post.discussion().bestAnswerPost() &&
            post
                .discussion()
                .bestAnswerPost()
                .id() === post.id() &&
            !post.isHidden()
        ) {
            items.add(
                'isBestAnswer',
                app.translator.trans('flarum-best-answer.forum.best_answer_button', { user: post.discussion().bestAnswerUser() })
            );
        }
    });

    extend(PostComponent.prototype, 'attrs', function(attrs) {
        const post = this.props.post;

        if (
            post.discussion().bestAnswerPost() &&
            post
                .discussion()
                .bestAnswerPost()
                .id() === post.id() &&
            !post.isHidden()
        ) {
            attrs.className += ' Post--bestAnswer';
        }
    });
}
