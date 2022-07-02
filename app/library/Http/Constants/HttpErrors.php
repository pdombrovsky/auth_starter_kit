<?php

namespace Library\Http\Constants;

class HttpErrors
{
    const NOT_FOUND              = 2000;
    const EMAIL_REQUIRED         = 2001;
    const PASSWORD_REQUIRED      = 2002;
    const FINGERPRINT_REQUIRED   = 2003;
    const REFRESH_TOKEN_REQUIRED = 2004;
    const NO_JSON                = 2005;
    const BAD_JSON               = 2006;
    const OBJECT_EXPECTED        = 2007;
    const AUTH_HEADER_REQUIRED   = 2008;
    const BEARER_REQUIRED        = 2009;

}
