<?php

namespace Framework;

use Framework\Session;

class Authorization
{
    /**
     * Check if the currently logged-in user owns a resource.
     * 
     * @param int $resourceUserId
     * @return bool
     */

    //  -: does not have to be just for listings
    //  -: as resource has to be passed in as a param

    public static function isOwner($resourceUserId)
    {
        $sessionUser = Session::get('user');

        if ($sessionUser !== null && isset($sessionUser['id'])) {
            // cast to an int
            $sessionUserId = (int) $sessionUser['id'];
            // this equates to true
            return $sessionUserId === $resourceUserId;
        }
        // if not retrun false 
        return false;
    }
}
