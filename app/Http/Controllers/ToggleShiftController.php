<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ToggleShiftController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();
        if (! ($user instanceof User)) {
            throw new \Exception('No user', 401);
        }
        if ($user->openShift) {
            $user->openShift->stop();
            return ['status' => 'stopped'];
        } else {
            $user->startShift();
            return ['status' => 'started'];
        }
    }
}
