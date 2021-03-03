<?php

namespace App\Console\Commands;

use App\Challange;
use App\Service\ChallengeFunctions;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateChallengeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'challenge:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update challenges status every minute';

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
        $status = ['featured', 'Upcoming'];
        $current = Carbon::now(new \DateTimeZone(env('DEFAULT_USER_TIMEZONE')));
        $allChallenges = Challange::whereIn('status', $status)->whereNull('close')->get();
        if (count($allChallenges)) {
            foreach ($allChallenges as $challenge) {

                $in = $current->between($challenge->start_date, $challenge->end_date);
                if ($in && $challenge->status == 'Upcoming') {
                    //update status to be featured
                    $challenge->status = "featured";
                    $challenge->featured_status = "submit";
                    $challenge->update();
                    continue;
                }


                $vote = $current->between($challenge->end_date, $challenge->voting_date);
                $check_players_count = ChallengeFunctions::checkPlayersCount($challenge->id);
                if ($vote && $challenge->status == 'featured' && $challenge->featured_status = "submit") {
                    if ($check_players_count) {
                        //update featured status to be vote
                        $challenge->featured_status = "vote";
                        $challenge->update();

                    } else {
                        //close challenge
                        $challenge->close = 1;
                        $challenge->update();
                    }
                    continue;
                }

                $after_voting = $current > $challenge->voting_date;
                if ($after_voting && $challenge->status == 'featured') {
                    //update status to be completed
                    $challenge->status = "completed";
                    $challenge->update();
                    continue;
                }

            }
        }
    }
}
