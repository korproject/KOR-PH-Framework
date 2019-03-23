<?php
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class ClientAuth
{
    protected $maxInterval = null;
    protected $relativeInterval = 10;
    protected $iss = 'http://example.com';
    protected $aud = 'http://example.org';
    protected $jti = null;

    public function __construct()
    {
        $this->maxInterval = strtotime('+1 week', time());
        $this->jti = hash('sha256', 'my_private_key');
    }

    /**
     * Custom set cookie function
     *
     * @param string $key: cookie key name
     * @param mixed $value: cookie data
     * @param int $expire: cookie expire days
     */
    public function setCookie($key, $value = null, $expire = null)
    {
        if ($expire === 0) {
            unset($_COOKIE[$key]);
        } else {
            setcookie($key, $value, $this->maxInterval, '/');
        }
    }
    
    /**
     * Get cookie
     * 
     * @param string $key: cookie key
     * @return mixed
     */
    public function getCookie($key)
    {
        if (isset($_COOKIE[$key]) && !empty($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }

        return false;
    }

    public function checkJwtToken($token){
        try {
            $token = (new Parser())->parse($token);

            $data = new ValidationData(); // iat, nbf and exp
            $data->setIssuer($this->iss);
            $data->setAudience($this->aud);
            $data->setId($this->jti);
        
            // validate $token with $data (iss, aud and jti)
            if ($token->validate($data)){
                $expire_date = new DateTime(date('Y-m-d H:i:s', $token->getClaim('exp')));
                $current_date = new DateTime(date('Y-m-d H:i:s', time()));
                $interval = $current_date->diff($expire_date);
        
                if ($interval->y >= 0 && $interval->m >= 0 && $interval->d >= 0 && $interval->h >= 0 && $interval->m >= $this->relativeInterval){
                    return 'renew';
                }
    
                $userData = $token->getClaim('user_data');
    
                if ($userData){
                    return json_decode(json_encode($userData), true);
                }

                return false;
            }
        } catch (Exception $exp) {
            return false;
        }
    }
    
    public function newJwtToken($userid, $username, $userLevel, $userAvatar = null){
        $user_data = [
            'userid' => $userid,
            'username' => $username,
            'user_level' => $userLevel,
            'profile_image' => $userAvatar
        ];

        return (string)(new Builder())->setIssuer($this->iss)
                ->setAudience($this->aud)
                ->setId($this->jti, true)
                ->setIssuedAt(time())
                ->setNotBefore(time() + 0)
                ->setExpiration($this->maxInterval)
                ->set('user_data', $user_data)
                ->getToken();
    }

    public function renewJwtToken($token){
        $old_token = (new Parser())->parse($token);

        $user_data = [
            'userid' => $old_token->getClaim('userid'),
            'username' => $old_token->getClaim('egoist'),
            'user_level' => $old_token->getClaim('user_level'),
            'profile_image' => $old_token->getClaim('profile_image')
        ];

        return (string)(new Builder())->setIssuer($this->iss)
                ->setAudience($this->aud)
                ->setId($this->jti, true)
                ->setIssuedAt(time())
                ->setNotBefore(time() + 0)
                ->setExpiration($this->maxInterval)
                ->set('user_data', $user_data)
                ->getToken();
    }
}
