import SettingsModal from 'flarum/components/SettingsModal';

export default class BestAnswerSettingsModal extends SettingsModal {
  className() {
    return 'BestAnswerSettingsModal Modal--small';
  }

  title() {
    return app.translator.trans('flarum-best-answer.admin.settings.title');
  }

  form() {
    return [
      <div className="Form-group">
        <label className="checkbox">
          <input type="checkbox" bidi={this.setting('flarum-best-answer.allow_select_own_post')}/>
          {app.translator.trans('flarum-best-answer.admin.settings.allow_select_own_post')}
        </label>
      </div>
    ];
  }
}
