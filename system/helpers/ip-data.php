<?php

class IPData
{
    /**
     * Get client real IP
     * 
     * @return string
     * @see soruce: anonymous
     */
    public function getIP()
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && filter_var($_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
            return $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}

eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIiwianRpIjoiOGYyMTlkMjlmYTU0YzdiY2U2ZDM2YjQ2ODRhOTAyODQ3MjZlYjczNGJhYWMxMWRmZDIxNjAyZDEwZDdiMGE0NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLmNvbSIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUub3JnIiwianRpIjoiOGYyMTlkMjlmYTU0YzdiY2U2ZDM2YjQ2ODRhOTAyODQ3MjZlYjczNGJhYWMxMWRmZDIxNjAyZDEwZDdiMGE0NiIsImlhdCI6MTU1MzA2NDk5MSwibmJmIjoxNTUzMDY0OTkxLCJleHAiOjE1NTM2Njk3OTEsInVzZXJfZGF0YSI6eyJ1c2VyaWQiOjEsInVzZXJuYW1lIjoiZWdvc2l0IiwidXNlcl9sZXZlbCI6MTAwLCJwcm9maWxlX2ltYWdlIjoidXBcL3BhdGgifX0.