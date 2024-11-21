<?php

namespace Vinkas\Cda\Server;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\Vinkas\Cda\Server\ClientFactory> */
    use HasFactory;

    protected $table = 'cda_clients';

    protected $fillable = [
        'name',
        'slug',
        'secret',
        'redirect_url'
    ];

    protected $nonce_key = 'nonce';

    private $nonce, $payload, $signature;

    public function isValid() {
        return ($this->getNonceFromPayload() && ($this->getRequestPayloadSignature() === $this->getSignature()));
    }

    public function getNonceFromPayload() {
        if ($this->nonce) {
            return $this->nonce;
        }

        $payloads = [];
        parse_str(base64_decode($this->getDecodedPayload()), $payloads);
        if (!array_key_exists($this->nonce_key, $payloads)) {
          return false;
        }

        $this->nonce = $payloads[$this->nonce_key];
        return $this->nonce;
    }

    protected function getDecodedPayload()
    {
        return urldecode($this->getPayload());
    }

    protected function getPayload() {
        if (! $this->payload) {
          $this->payload = session('cda_payload');
        }

        return $this->payload;
    }

    protected function getRequestPayloadSignature()
    {
        return hash_hmac('sha256', $this->getDecodedPayload(), $this->secret);
    }

    protected function getSignature() {
        if (! $this->signature) {
          $this->signature = session('cda_signature');
        }

        return $this->signature;
    }

    public function getResponseUrl()
    {
        return $this->redirect_url . '?' . $this->getResponseQuery();
    }

    protected function getResponseQuery()
    {
        $response = [
            'payload'   => $this->getResponsePayload(),
            'signature' => $this->getResponsePayloadSignature(),
        ];
        return http_build_query($response);
    }

    protected function getResponsePayload()
    {
        $params =  [$this->nonce_key => $this->getNonceFromPayload()];
        $params = array_merge($this->getUserParams(), $params);
        return base64_encode(http_build_query($params));
    }

    protected function getUserParams()
    {
      $user = auth()->user();

      $userParams = array(
        'id' => $user->id,
        'email'     => $user->email,
        'username' => $user->username,
        'name'     => $user->name
      );

      return $userParams;
    }

    protected function getResponsePayloadSignature()
    {
        return hash_hmac('sha256', $this->getResponsePayload(), $this->secret);
    }

    public function redirect() {
        $url = $this->getResponseUrl();
        session()->forget([
            'cda_client_id',
            'cda_payload',
            'cda_signature'
        ]);
        return redirect($url);
    }

    public static function findValid() {
        $clientId = session('cda_client_id');

        if (!$clientId || auth()->guest()) {
          return;
        }

        $client = self::find($clientId);
        if ($client && $client->isValid()) {
          return $client;
        }

        return;
    }
}
