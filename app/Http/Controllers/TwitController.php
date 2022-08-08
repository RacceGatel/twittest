<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwitSendRequest;
use App\Jobs\SendTwitJob;
use App\Models\Twit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TwitController extends Controller
{
    public function index() {

    }

    public function store(TwitSendRequest $request): JsonResponse {
        $twit = new Twit();
        $twit->category_id = $request->category_id;
        $twit->username = $request->username;
        $twit->content = $request->message;

        dispatch(new SendTwitJob($twit));

        return response()->json();
    }
}
