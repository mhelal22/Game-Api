<?php

namespace App\Http\Controllers;

use App\Challange;
use App\ChallangeVideos;
use App\Service\ChallengeFunctions;
use App\VideosRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ChallengeController extends Controller
{
    public function __construct()
    {

    }

    public function createChallenge(Request $request)
    {
        //try catch
        $validation = Validator::make(
            $request->all(),
            [
                'game_id' => 'required|exists:App\Game,id',
                'name' => 'required',
                'description' => 'required',
                'rules' => 'required',
                'Rewards' => 'required',
                'Platform' => 'required',
                'number_of_participants' => 'required|numeric',
                'start_date' => 'required|date_format:Y-m-d H:i',
                'end_date' => 'required|date_format:Y-m-d H:i|after:start_date'
            ]
        );
        if ($validation->fails()) {

            $response = array("errors" => count($validation->getMessageBag()->all()), "code" => 422, "message" => $validation->getMessageBag()->all());

            return response($response, 200);
        } else {

            $end_dt = Carbon::parse($request->end_date);
            $voting_date = $end_dt->addHours(3); //voting date
            $status = 'Upcoming';

            $challenge = Challange::create([
                'name' => $request->name,
                'game_id' => $request->game_id,
                'description' => $request->description,
                'rules' => $request->rules,
                'Rewards' => $request->Rewards,
                'Platform' => $request->Platform,
                'number_of_participants' => $request->number_of_participants,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'voting_date' => $voting_date,
                'status' => $status,
            ]);

            return response(['error' => 0, "code" => 200, 'body' => trans("messages.challenge_added")]);
        }

    }


    public function getAllChallenge(Request $request)
    {

        $allUpcomingChallenge = Challange::with('game')->where('status', 'Upcoming')->whereNull('close')->get(); //upcoming
        $allFeaturedChallenge = Challange::with('game')->where('status', 'featured')->whereNull('close')->get(); //featured
        $allCompletedChallenge = Challange::with('game')->where('status', 'completed')->whereNull('close')->get(); //completed

        $response = array(
            'allUpcomingChallenges' => $allUpcomingChallenge,
            'allFeaturedChallenges' => $allFeaturedChallenge,
            'allCompletedChallenges' => $allCompletedChallenge,
        );

        return response(['error' => 0, "code" => 200, 'body' => $response]);
    }

    public function getChallenge(Request $request, $id)
    {

        $Challenge = Challange::with('game')->where('id', $id)->first();

        if ($Challenge) {

            switch ($Challenge->status) {
                case 'featured':

                    if ($Challenge->featured_status == 'vote') {

                        $Challenge->videos = ChallengeFunctions::getVideos($id); // attach challenge videos randomly
                    }
                    break;

                case 'completed':
                    $Challenge->winners = ChallengeFunctions::getWinners($id); // attach winners from max rate to low rate
                    $Challenge->winner = ChallengeFunctions::getWinner($id); // attach challenge winner
                    break;

                default:

            }

        } else {
            $response = array("error" => 1, "code" => 404, "message" => trans("messages.challenge_not_found"));
            return response($response, 200);
        }

        return response(['error' => 0, "code" => 200, 'body' => $Challenge]);
    }

    public function submitVideo(Request $request, $id)
    {
        $response = [];
        $validation = Validator::make(
            $request->all(),
            [
                'player_id' => 'required|exists:App\User,id',
                'video' => 'required|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi|max:20000',
            ]
        );
        if ($validation->fails()) {
            $response = array("errors" => count($validation->getMessageBag()->all()), "code" => 422, "message" => $validation->getMessageBag()->all());
        } else {
            //check if challenge is featured and submit
            $challenge = Challange::find($id);
            if ($challenge->status == 'featured' && $challenge->featured_status == 'submit') {
                //check if challenge is full or not
                if (!ChallengeFunctions::checkPlayersCount($id)) {
                    $videoPath = $request->video->store('videos_folder'); //store challenge video on storage

                    ChallangeVideos::create(['challenge_id' => $id, 'player_id' => $request->player_id, 'video_url' => $videoPath, 'rate' => 0]);

                    $response = array("error" => 0, "code" => 200, "body" => trans("messages.challenge_video_added"));
                } else {
                    $response = array("error" => 1, "code" => 422, "message" => trans("messages.full_challenge"));
                }
            } else {
                $response = array("error" => 1, "code" => 422, "message" => trans("messages.invalid_challenge_status"));
            }
        }
        return response($response, 200);
    }

    public function voteVideo(Request $request, $video_id){
        $response = [];
        $validation = Validator::make(
            $request->all(),
            [
                'rated_by' => 'required|exists:App\User,id',
                'rate' => 'required|numeric',
            ]
        );
        if ($validation->fails()) {
            $response = array("errors" => count($validation->getMessageBag()->all()), "code" => 422, "message" => $validation->getMessageBag()->all());
        } else {

            VideosRating::create(['video_challenge_id' => $video_id, 'rated_by' => $request->rated_by, 'rate' => $request->rate]);

            //update video rate on video_challenge (sum all rates which this video taken)
            $video_challenge = ChallangeVideos::find($video_id);
            $total_rate = $video_challenge->rate + (double)$request->rate;
            $video_challenge->rate = $total_rate;
            $video_challenge->update();

            $response = array("error" => 0, "code" => 200, "body" => trans("messages.video_rate_added"));

        }
        return response($response, 200);
    }
}
