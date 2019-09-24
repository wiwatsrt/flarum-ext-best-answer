import app from 'flarum/app';
import { extend } from 'flarum/extend';
import Button from 'flarum/components/Button';
import CommentPost from 'flarum/components/CommentPost';
import DiscussionPage from 'flarum/components/DiscussionPage';

export default function() {
  const commonItem = (items, post, discussion, isBestAnswer) => {
    items.add(
      'bestAnswer',
      Button.component({
        children: app.translator.trans(
          isBestAnswer ? 'flarum-best-answer.forum.remove_best_answer' : 'flarum-best-answer.forum.this_best_answer'
        ),
        className: 'Button Button--link',
        onclick: function onclick() {
          isBestAnswer = !isBestAnswer;
          discussion
            .save({
              bestAnswerPostId: isBestAnswer ? post.id() : 0,
              bestAnswerUserId: app.session.user.id(),
              relationships: isBestAnswer
                ? { bestAnswerPost: post, bestAnswerUser: app.session.user }
                : delete discussion.data.relationships.bestAnswerPost,
            })
            .then(() => {
              if (app.current instanceof DiscussionPage) {
                app.current.stream.update();
              }
              m.redraw();
            });
          if (isBestAnswer) {
            m.route(app.route.post(discussion.posts()[0]));
          }
        },
      })
    );
  };

  extend(CommentPost.prototype, 'actionItems', function(items) {
    const post = this.props.post;
    const discussion = this.props.post.discussion();
    let isBestAnswer = discussion.bestAnswerPost() && discussion.bestAnswerPost().id() === post.id();

    post.pushAttributes({ isBestAnswer });

    if (post.isHidden() || post.number() === 1 || !app.session.user) return;


    if (post.user().id() === app.session.user.id() && discussion.canSelectBestAnswerOwnPost()) {
      commonItem(items, post, discussion, isBestAnswer);
    }


    if (!discussion.canSelectBestAnswer() || post.user().id() === app.session.user.id()) return;

    commonItem(items, post, discussion, isBestAnswer);

  });
}
