<?php

namespace Vinkas\Cda\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Vinkas\Cda\Client;

class ClientController extends Controller {
    public function show(Request $request, string $key) {
        $client = Client::where('key', $key)->first();
        if (!$client) {
            return response()->json(['error' => 'CDA client not found'], 404);
        }
        
        $payload = $request->input('payload');
        $signature = $request->input('signature');
        if (!$payload || !$signature) {
          return response()->json(['error' => 'Invalid request'], 400);
        }

        session([
            'cda_client_id' => $client->id,
            'cda_payload'   => $payload,
            'cda_signature' => $signature
        ]);

        if (!$client->isValid()) {
          return response()->json(['error' => 'Bad request'], 403);
        }

        $user = $request->user();
        if (!$user) {
          return redirect('/login');
        }

        return $client->getRedirectResponse();
    }
}
