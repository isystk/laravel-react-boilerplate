<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class BaseService
{

    protected Request $_request;

    protected Authenticatable|null $_user;

    public function __construct(Request $request)
    {
        $this->_request = $request;
    }

    /**
     * @return Request
     */
    public function request(): Request
    {
        return $this->_request;
    }

    /**
     * @return string
     */
    public function gate(): string
    {
        if (!isset($this->request()->route()->action['prefix'])) {
            return '';
        }
        return trim($this->request()->route()->action['prefix'], '/');
    }

    /**
     * @return bool
     */
    public function isGuest(): bool
    {
        return Auth::guest();
    }

    /**
     * @return Authenticatable|null
     */
    public function user(): ?Authenticatable
    {
        if (!$this->_user) {
            $this->_user = Auth::user();
        }
        return $this->_user;
    }

}
