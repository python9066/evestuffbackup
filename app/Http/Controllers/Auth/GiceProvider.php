<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Illuminate\Support\Arr;

class GiceProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'openid',
        // 'groups-limited'
    ];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        dd($state);
        return $this->buildAuthUrlFromBase('https://esi.goonfleet.com/oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {

        return 'https://esi.goonfleet.com/oauth/token';
    }


    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)],
            'body'    => $this->getTokenFields($code),
        ]);

        dd($response->getBody());
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param  string  $code
     * @return array
     */
    protected function getTokenFields($code)
    {

        return Arr::add(
            parent::getTokenFields($code),
            'grant_type',
            $code
        );
    }



    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        // dd($token);
        $response = $this->getHttpClient()->get('https://esi.goonfleet.com/oauth/userinfo', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        // dd(json_decode($response->getBody(), true));

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {

        // dd($user);
        // Deprecated: Fields added to keep backwards compatibility in 4.0. These will be removed in 5.0
        // $user['id'] = Arr::get($user, 'sub');
        // $user['verified_email'] = Arr::get($user, 'email_verified');
        // $user['link'] = Arr::get($user, 'profile');

        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'sub'),
            'nickname' => Arr::get($user, 'username'),
            'name' => Arr::get($user, 'name'),
        ]);
    }
}
