<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challange extends Model
{
    protected $fillable = [
    'name','game_id','description','rules','Rewards','Platform','number_of_participants','start_date','end_date','voting_date','status','featured_status','close'
    ];

    public static function getPlayersCount(){

    }

    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    public static function isFull($challenge_id){

    }
}
