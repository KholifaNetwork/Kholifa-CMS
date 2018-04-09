<?php
/**
 * @package Kholifa CMS
 *
 *
 */

namespace including;

/**
 * Logging in a user and checking login status
 *
 */
class User
{

    /**
     * Alias of isLoggedIn
     * @private
     * @return bool true if user is logged in
     */
    function loggedIn()
    {
        return $this->isLoggedIn();
    }

    /**
     * @return bool true if user is logged in
     */
    function isLoggedIn()
    {
        return isset($_SESSION['knUserId']);
    }

    /**
     * Logout current user
     * @return void
     */
    function logout()
    {
        if (isset($_SESSION['knUserId'])) {
            knEvent('knBeforeUserLogout', array('userId' => $this->userId()));
            unset($_SESSION['knUserId']);
            knEvent('knUserLogout', array('userId' => $this->userId()));
        }
    }

    /**
     * Get current user ID
     * @return int Logged in user ID or false, if user is not logged in
     */
    function userId()
    {
        if (isset($_SESSION['knUserId'])) {
            return $_SESSION['knUserId'];
        } else {
            return false;
        }
    }

    /**
     * Set user as logged in
     * @param int $id User id
     * @return void
     */
    function login($id)
    {
        knEvent('knUserLogin', array('userId' => $id));
        $_SESSION['knUserId'] = $id;
    }


    /**
     * Get all user info collected from all user specific plugins.
     * @param int $userId
     * @return array
     */
    function data($userId = null)
    {
        if ($userId === null) {
            $userId = $this->userId();
        }
        if (!$userId) {
            return array();
        }
        $info = array(
            'userId' => $userId
        );
        $data = array(
            'id' => $userId
        );
        return knFilter('knUserData', $data, $info);
    }

}
