<?php

namespace App\Service;

use App\Challange;
use App\ChallangeVideos;


class ChallengeFunctions
{
  public function __construct()
  {
  }

   public static function checkPlayersCount($id){
      $challenge = Challange::find($id);
      $count_of_videos = ChallangeVideos::where('challenge_id',$id)->count();
      if($challenge->number_of_participants == $count_of_videos){
          return true;
      }else{
          return false;
      }
   }

   public static function getWinner($id){
      $max_rate = ChallangeVideos::where('challenge_id',$id)->max('rate');
      $winner = ChallangeVideos::leftjoin('users','challange_videos.player_id','users.id')->where('challange_videos.challenge_id',$id)->where('challange_videos.rate',$max_rate)
          ->select('users.id as player_id','users.name as player_name','users.email as player_email','challange_videos.rate')->first();
      return $winner;
   }

    public static function getWinners($id){
        $winners = ChallangeVideos::leftjoin('users','challange_videos.player_id','users.id')->where('challange_videos.challenge_id',$id)
            ->select('users.id as player_id','users.name as player_name','users.email as player_email','challange_videos.rate')->orderBy('challange_videos.rate','DESC')->get();
        return $winners;
    }

    public static function getVideos($id){
        $challenge_videos = ChallangeVideos::leftjoin('users','challange_videos.player_id','users.id')->where('challange_videos.challenge_id',$id)
            ->select('challange_videos.id as video_id','users.id as player_id','users.name as player_name','users.email as player_email','challange_videos.video_url')->inRandomOrder()->get();

        return $challenge_videos;
    }

}
