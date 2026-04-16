<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Scaffolds\Modal;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function data(Request $rq)
    {
        $modal = Modal::get($rq->name, json_decode($rq->overrides, true));

        return response()->json($modal);
    }
}
