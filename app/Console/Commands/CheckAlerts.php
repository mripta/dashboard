<?php

namespace App\Console\Commands;

use App\Models\Data;
use App\Models\Alert;
use Illuminate\Console\Command;
use App\Notifications\AlertNotification;
use Illuminate\Support\Facades\Notification;

class CheckAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all the alerts and send email notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking Alerts');

        $alerts = Alert::where('enabled', true)->get();

        // actual time minus 5 minutes
        $alertTime = strtotime('-5 minutes');

        // iterate alterts
        foreach($alerts as $alert)
        {
            // converts vars
            $teamid = intval($alert->team_id);
            $alertTime = intval($alertTime);
            $min = floatval($alert->min);
            $max = floatval($alert->max);

            // select all the data from param in the time
            $data = Data::where('teamid', $teamid)
                ->where('ref', $alert->ref->ref)
                ->where('timestamp', '>', $alertTime);

            // number total of documents in time (not alerts)
            $totalc = $data->count();

            // get the data between the min and max limits
            $data->whereBetween($alert->param->param, [$min,$max]);

            $alertc = $totalc - $data->count();

            // check if there are alerts -> when the var is != 0
            if($alertc != 0)
            {
                $this->info($alertc.' Found alerts on sensor '.$alert->ref->ref.' - '.$alert->param->param.', sending emails to the team '.$alert->team->name);
                // iterate the users of the team
                foreach($alert->team->users as $user)
                {
                    Notification::route('mail', $user->email)->notify(new AlertNotification($user->name, $alert->team->name, $alert->ref->ref, $alert->param->param, $alertc));
                }
            } else {
                $this->info('No alerts found');
            }
        }
    }
}
