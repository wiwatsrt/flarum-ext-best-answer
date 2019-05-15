import SettingsModal from 'flarum/components/SettingsModal';

export default class BestAnswerSettingsModal extends SettingsModal {
    className() {
        return 'BestAnswerSettingsModal Modal--medium';
    }

    title() {
        return app.translator.trans('flarum-best-answer.admin.settings.title');
    }

    form() {
        return [
            <div className="Form-group">
                <label className="checkbox">
                    <input type="checkbox" bidi={this.setting('flarum-best-answer.allow_select_own_post')} />
                    {app.translator.trans('flarum-best-answer.admin.settings.allow_select_own_post')}
                </label>
            </div>,
            <div className="Form-group">
                <label>{app.translator.trans('flarum-best-answer.admin.settings.select_best_answer_reminder_days')}</label>
                <input
                    className="FormControl"
                    type="number"
                    min="0"
                    placeholder="0"
                    bidi={this.setting('flarum-best-answer.select_best_answer_reminder_days')}
                />
            </div>,
        ];
    }
}
