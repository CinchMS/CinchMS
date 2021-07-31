<?php

namespace CinchMS\Core;

use \ORM, \DateTime;

class ACPAuthentication {

    private $config;
    protected $authenticated = false;

    public function __construct($config) {
        $this->config = $config;
        if(isset($_COOKIE['auth_tkn'])) {
            if(isset($_SESSION['token']) && $_SESSION['token'] === $_COOKIE['auth_tkn']) {
                $now = new DateTime();
                $interval = DateTime::createFromFormat('Y-m-d H:i:s', $_SESSION['start'])->diff(DateTime::createFromFormat('Y-m-d H:i:s',$now->format('Y-m-d H:i:s')));
                if($interval->d < 30) {
                    $this->authenticated = true;
                } else {
                    $this->destroySession();
                }
            } else {
                $this->destroySession();
            }
        }
    }

    /**
     * Return if an user is logged or not
     * @return bool
     */
    public function isAuthenticated() {
        return $this->authenticated;
    }

    /**
     * Verify the correction of an user password upon login
     * @param $email
     * @param $password
     * @return bool
     */
    public function comparePassword($email, $password) {
        $user = ORM::forTable('admin')->where(array('email' => $email))->findOne();
        if($user){
            return password_verify($password, $user->get('password'));
        }
        return false;
    }

    /*
     * The client side session is currently detected using:
     *   - Token (stored in the 'auth_tkn' cookie)
     *   - IP (implicitly detected by Cinch)
     *
     * The session is stored using:
     *   - Admin ID
     *   - Session ID
     *   - Starting date-time
     *   - IP (using during logon)
     *   - Token (the sme stored client side)
     */

    /**
     * Create a session given the user id (and a boolean used to remember the session client-side)
     * @param $admin_id
     * @param $rememberme
     */
    public function createSession($admin_id, $rememberme) {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $now = date('Y-m-d H:i:s');

        if($rememberme === "true") {
            setcookie('auth_tkn', $token, time() + (86400 * 30),"/".$this->config["paths"]["admin"], "", $this->config["https"]);
        } else {
            setcookie('auth_tkn', $token, 0,"/".$this->config["paths"]["admin"], "", $this->config["https"]);
        }

        $user = ORM::forTable('admin')->findOne($admin_id);

        $_SESSION['admin_id'] = $user->get('id');
        $_SESSION['start'] = $now;
        $_SESSION['token'] = $token;
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['username'] = $user->get('nickname');
    }

    /**
     * Completely delete a session (db entry included)
     */
    public function destroySession() {
        session_destroy();
        $this->unsetCookie();
    }

    /**
     * Unset 'auth_tkn' cookies
     */
    public function unsetCookie() {
        setcookie ('auth_tkn', "", time() - 3600);
        unset($_COOKIE['auth_tkn']);
    }
}
