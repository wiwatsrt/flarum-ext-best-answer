<?php

namespace WiwatSrt\BestAnswer\Console;

use Carbon\Carbon;
use Error;
use Exception;
use Flarum\Discussion\Discussion;
use Flarum\Notification\NotificationSyncer;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Console\Command;
use Throwable;
use WiwatSrt\BestAnswer\Notification\SelectBestAnswerBlueprint;

class NotifyCommand extends Command
{
    /**
     * @var SettingsRepositoryInterface
     */
    private $settings;

    /**
     * @var NotificationSyncer
     */
    private $notifications;

    public function __construct(SettingsRepositoryInterface $settings, NotificationSyncer $notifications)
    {
        parent::__construct();

        $this->settings = $settings;
        $this->notifications = $notifications;
    }


    /**
     * @var string
     */
    protected $signature = 'wiwatsrt-best-answer:notify';

    /**
     * @var string
     */
    protected $description = 'After a configurable number of days, notifies OP of discussions with no post selected as best answer to select one.';

    public function handle()
    {
        $days = (int) $this->settings->get('flarum-best-answer.select_best_answer_reminder_days');
        $time = Carbon::today()->addDay(-$days);

        if ($days <= 0) {
            $this->info("Reminders are disabled");
            return;
        }

        $this->info("Looking at discussions before " . $time->toDateString());

        $query = Discussion::whereNull('best_answer_post_id')
            ->where('best_answer_notified', false)
            ->where('comment_count', '>', 0)
            ->whereDate('created_at', '<', $time);

        $count = $query->count();

        if ($count == 0) {
            $this->info("Nothing to do");
            return;
        }

        $errors = [];

        $query->chunk(20, function ($discussions) use (&$errors) {
            /**
             * @var $discussions Discussion[]
             */

            $this->output->write("<info>Sending " . sizeof($discussions) . " notifications </info>");

            foreach ($discussions as $d) {
                try {
                    $this->notifications->sync(
                        new SelectBestAnswerBlueprint($d),
                        [$d->user]
                    );

                    $this->output->write("<info>#</info>");

                    $d->best_answer_notified = true;
                    $d->save();
                } catch (Throwable $e) {
                    $this->output->write("<error>#</error>");
                    $errors[] = $e;
                }
            }

            $this->line("");
        });

        $query->update(['best_answer_notified' => true]);

        if (sizeof($errors) > 0) {
            $this->line("\n");
            $this->alert("Failed to send " . sizeof($errors) . " notifications");
            $this->warn("");

            foreach ($errors as $i => $e) {
                $n = $i + 1;

                $this->output->writeln("<warning>$n >>>>>></warning> <error>$e</error>");
                $this->line("");
            }
        }
    }
}