<?php namespace Claymm\ArrowDB;

use Exception;
use Session;

class ArrowDB
{
    /**
     * @var String
     */
    protected $apiUrl;

    /**
     * @var String
     */
    protected $appKey;

    /**
     * @var String
     */
    protected $cookiePath;

    public function __construct()
    {
        $this->apiUrl     = config("arrowdb.base_url");
        $this->appKey     = config("arrowdb.api_key");
        $this->cookiePath = config("session.files");
    }

    public function delete($url, $data = null, $secure = true)
    {
        return $this->send('DELETE', $url, $data, $secure);
    }

    public function get($url, $data = null, $secure = true)
    {
        return $this->send('GET', $url, $data, $secure);
    }

    public function post($url, $data = null, $secure = true)
    {
        return $this->send('POST', $url, $data, $secure);
    }

    public function put($url, $data = null, $secure = true)
    {
        return $this->send('PUT', $url, $data, $secure);
    }

    public function attempt($email, $password)
    {
        $userInfo = $this->authenticate($email, $password);

        if ($userInfo) {
            return $userInfo;
        } else {
            return false;
        }
    }

    protected function authenticate($email, $password)
    {
        if (Session::has('arrowdb_user')) {
            return Session::get('arrowdb_user');
        }

        $login = array(
            'login'    => $email,
            'password' => $password
        );

        $sessionId = Session::getId();

        $ch = curl_init($this->buildUrl('users/login.json'));

        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath.'/jar_'.$sessionId.'.data');
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath.'/jar_'.$sessionId.'.data');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $login);

        $login = curl_exec($ch);
        if (! $login) {
            throw new Exception(curl_error($ch));
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpcode === 200) {
            $response = json_decode($login, true);
            $user     = $response['response']['users'][0];
            Session::put('arrowdb_user', $user);
            return true;
        }

        return false;
    }

    protected function send($verb, $url, $data, $secure)
    {
        $baseUri = $this->buildUrl($url, $secure);

        if (! empty($data) && $verb === 'GET') {
            $uri = $baseUri . '&'. http_build_query($data);
        } else {
            $uri = $baseUri;
        }

        $sessionId = Session::getId();

        $ch = curl_init($uri);

        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath.'/jar_'.$sessionId.'.data');
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath.'/jar_'.$sessionId.'.data');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        switch ($verb) {
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
        }

        $output = curl_exec($ch);

        if ($output == false) {
            return curl_error($ch);
        }

        return json_decode($output, true);
    }

    protected function buildUrl($url, $secure = true)
    {
        $finalUrl  = $secure ? 'https://' : 'http://';
        $finalUrl .= $this->apiUrl . $url . '?key=' . $this->appKey;

        return $finalUrl;
    }
}
