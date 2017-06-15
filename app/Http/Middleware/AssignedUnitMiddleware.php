<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Model\LecturerUnit;

class AssignedUnitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $parameters = $request->route()->parameters();
        $unit = $parameters['unit'];
        $auth = Auth::user();

        // go through for each units. if assigned, show unit
        $assignedunits = LecturerUnit::where('user_id', $auth->id)->get();
        foreach ($assignedunits as $assignedunit) {
            if ($assignedunit->unit_id == $unit) {
                return $next($request);
            }
        }

        // if the unit is not assigned, redirect to home
        // TODO: redirect to a proper error page
        return redirect('/home');
    }
}
