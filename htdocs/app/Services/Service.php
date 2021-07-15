<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class Service
{
    /**
     * @var Request
     */
    protected $_request;

    protected $_user;

    public function __construct(Request $request)
    {
        $this->_request = $request;
    }

    /**
     * @return Request
     */
    public function request()
    {
        return $this->_request;
    }

    public function gate()
    {
        if (!isset($this->request()->route()->action['prefix'])) {
            return '';
        }
        return trim($this->request()->route()->action['prefix'], '/');
    }

    public function isGuest()
    {
        return Auth::guest();
    }

    public function user()
    {
        if (!$this->_user) {
            $this->_user = Auth::user();
        }
        return $this->_user;
    }

}
