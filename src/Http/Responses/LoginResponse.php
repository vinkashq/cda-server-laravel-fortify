<?php

namespace Vinkas\Cda\Server\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;
use Vinkas\Cda\Server\Client;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param $request
     *
     * @return mixed
     */
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        $response = Client::findValid()?->redirect();
        if ($response) {
            return $response;
        }

        return redirect()->intended(Fortify::redirects('login'));
    }
}
