<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideosRating extends Model
{

    protected $fillable = [
        "video_challenge_id" , "rated_by" , "rate"
    ];
}
