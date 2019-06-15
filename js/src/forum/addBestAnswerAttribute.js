import app from 'flarum/app';
import { extend } from 'flarum/extend';
import CommentPost from 'flarum/components/CommentPost';
import PostComponent from 'flarum/components/Post';
import icon from 'flarum/helpers/icon';

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
                <div className="Post--BestAnswer">
                    <span>
                        {icon('fas fa-check')}
                        {app.translator.trans('flarum-best-answer.forum.best_answer_button')}
                    </span>

                    <span className='BestAnswer--User'>
                        {app.translator.trans('flarum-best-answer.forum.best_answer_label', {
                            user: post.discussion().bestAnswerUser(),
                            a: <a onclick={() => m.route(app.route.user(post.discussion().bestAnswerUser()))} />
                        })}
                    </span>
                </div>
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
