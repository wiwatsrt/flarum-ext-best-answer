import app from 'flarum/app';
import Discussion from 'flarum/models/Discussion';
import Model from 'flarum/Model';
import addBestAnswerAction from './addBestAnswerAction';
import addBestAnswerAttribute from './addBestAnswerAttribute';
import addBestAnswerBadge from './addBestAnswerBadge';
import addBestAnswerFirstPost from './addBestAnswerFirstPost';

app.initializers.add('wiwatSrt-bestAnswer', function() {
    Discussion.prototype.bestAnswerPost = Model.hasOne('bestAnswerPost');
    Discussion.prototype.canSelectBestAnswer = Model.attribute('canSelectBestAnswer');
    Discussion.prototype.canSelectBestAnswerOwnPost = Model.attribute('canSelectBestAnswerOwnPost');

    addBestAnswerAction();
    addBestAnswerAttribute();
    addBestAnswerBadge();
    addBestAnswerFirstPost();
});
