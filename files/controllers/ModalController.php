<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Models\Shipyard\Modal;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function data(Request $rq)
    {
        $modal = Modal::where("name", $rq->name)->first();

        return response()->json($modal);
    }
}
