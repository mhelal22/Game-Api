<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChallangeVideos extends Model
{
    protected $fillable = [
        'challenge_id','player_id','video_url','rate'
    ];

}
