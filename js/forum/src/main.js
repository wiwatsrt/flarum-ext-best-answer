import app from 'flarum/app';
import { extend } from 'flarum/extend';
import addBadgeBestAnswer from 'wiwatsrt/best-answer/addBadgeBestAnswer';
import addBestAnswerAttribute from 'wiwatsrt/best-answer/addBestAnswerAttribute';
import addBestAnswerAction from 'wiwatsrt/best-answer/addBestAnswerAction';

app.initializers.add('wiwatSrt-bestAnswer', function() {
    addBadgeBestAnswer();
    addBestAnswerAttribute();
    addBestAnswerAction();
});