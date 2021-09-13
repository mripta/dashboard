<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Invite;
use Illuminate\Http\Request;

class HasInvite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('get'))
        {
            if (!$request->has('token')) {
                return redirect(route('invite.request'))->with('error', 'Existe um erro no URL');
            }

            $token = $request->get('token');

            // find token
            try {
                $invite = Invite::where('token', $token)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect(route('invite.request'))->with('error', 'Existe um erro no URL');
            }

            // check if user is registered
            if (!is_null($invite->registered_at)) {
                return redirect(route('login'))->with('error', 'O convite já foi utilizado');
            }
        }
        return $next($request);
    }
}
