<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Scaffolds\Modal;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function data(Request $rq)
    {
        $modal = Modal::get($rq->name);

        return response()->json($modal);
    }
}
