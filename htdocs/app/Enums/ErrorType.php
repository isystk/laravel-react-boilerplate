<?php

namespace App\Enums;

enum ErrorType: String
{

    case SUCCESS = 'Success';
    case NOT_FOUND = 'NotFound';
    case DATABASE = 'DB';
    case FATAL = 'Fatal';
    case UNKNOWN = 'Unknown';

}
