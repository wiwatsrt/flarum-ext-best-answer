import app from 'flarum/app';
import { extend } from 'flarum/extend';
import CommentPost from 'flarum/components/CommentPost';
import icon from 'flarum/helpers/icon';
import PostMeta from 'flarum/components/PostMeta';
import username from 'flarum/helpers/username';
import userOnline from 'flarum/helpers/userOnline';

export default function() {
    extend(CommentPost.prototype, 'footerItems', function(items) {
        const thisPost = this.props.post;
        const discussion = thisPost.discussion();

        if (discussion.bestAnswerPost() && thisPost.number() === 1 && !thisPost.isHidden()) {
            const post = discussion.bestAnswerPost();

            if (post.isHidden()) return;

            const user = post.user();

            items.add(
                'bestAnswerPost',
                <div className="CommentPost">
                    <div className="Post-header">
                        <ul>
                            <li className="item-user">
                                <div className="PostUser">
                                    {userOnline(user)}
                                    <h3>
                                        <a href={app.route.user(user)} config={m.route}>
                                            {username(user)}
                                        </a>
                                    </h3>
                                </div>
                            </li>
                            <li className="item-meta">{PostMeta.component({ post })}</li>
                            <li className="Post--BestAnswer">
                                <a href={app.route.post(post)} config={m.route} data-number={post.number()}>
                                    {icon('fas fa-check')}
                                    {app.translator.trans('flarum-best-answer.forum.best_answer_button')}
                                </a>

                                <span className="BestAnswer--User">
                                    {app.translator.trans('flarum-best-answer.forum.best_answer_label', {
                                        user: discussion.bestAnswerUser(),
                                        a: <a onclick={() => m.route(app.route.user(discussion.bestAnswerUser()))} />
                                    })}
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div className="Post-body">{m.trust(post.contentHtml())}</div>
                </div>,
                -10
            );
        }
    });
}
