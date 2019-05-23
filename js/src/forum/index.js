import app from 'flarum/app';
import Discussion from 'flarum/models/Discussion';
import Model from 'flarum/Model';
import addBestAnswerAction from './addBestAnswerAction';
import addBestAnswerAttribute from './addBestAnswerAttribute';
import addBestAnswerBadge from './addBestAnswerBadge';
import addBestAnswerFirstPost from './addBestAnswerFirstPost';
import addBestAnswerNotification from './addBestAnswerNotification';

app.initializers.add('wiwatSrt-bestAnswer', function() {
    Discussion.prototype.bestAnswerPost = Model.hasOne('bestAnswerPost');
    Discussion.prototype.bestAnswerUser = Model.hasOne('bestAnswerUser');
    Discussion.prototype.startUserId = Model.attribute('startUserId', Number);
    Discussion.prototype.firstPostId = Model.attribute('firstPostId', Number);
    Discussion.prototype.canSelectBestAnswer = Model.attribute('canSelectBestAnswer');

    addBestAnswerAction();
    addBestAnswerAttribute();
    addBestAnswerBadge();
    addBestAnswerFirstPost();
    addBestAnswerNotification();
});
