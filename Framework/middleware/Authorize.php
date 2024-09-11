<?php

namespace Framework\Middleware;

use Framework\Session;



class Authorize
{

    /**
     * Check if the user is authenticated
     *
     * @param string $role
     * @return boolean
     */

    public function isAuthenticated()
    {
        return Session::has('user');
    }


    /**
     * Check if the user is authenticated
     * role can be guest or admin or whatever
     * @param string $role
     * @return boolean
     */

    //  we use this in the router 
    // here we can add more roles for admins in here 
    public function handle($role)
    {
        // LOGIN FORM: if the role is guest and authenticates we need to re-direct
        if ($role === 'guest' && $this->isAuthenticated()) {
            return redirect('/');
        } elseif ($role === 'auth' && !$this->isAuthenticated()) {
            return redirect('/auth/login');
        }
    }
}
