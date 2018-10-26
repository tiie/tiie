<?php
namespace Topi\Http\Headers;


use Topi\Data\Adapters\Commands\SQL\Select;

class Parser
{
    public function parse(string $encoded)
    {
        $headers = array();

        foreach (explode("\n", $encoded) as $line) {
            $line = explode(':', $line);

            if (count($line) != 2) {
                continue;
            }

            // $header = strtolower(trim($line[0]));
            $header = trim($line[0]);
            $value = trim($line[1]);

            if (0) {
            } else if ($header =='Content-Type') {
                $header = new ContentType($value);

            // } else if ($header =='Accept') {
            // } else if ($header =='Accept-Charset') {
            // } else if ($header =='Accept-Encoding') {
            // } else if ($header =='Accept-Language') {
            // } else if ($header =='Authorization') {
            // } else if ($header =='Cache-Control') {
            // } else if ($header =='Connection') {
            // } else if ($header =='Content-Length') {
            // } else if ($header =='Content-MD5') {

            // } else if ($header =='Date') {
            // } else if ($header =='Expect') {
            // } else if ($header =='From') {
            // } else if ($header =='Host') {
            // } else if ($header =='If-Match') {
            // } else if ($header =='If-Modified-Since') {
            // } else if ($header =='If-None-Match') {
            // } else if ($header =='If-Range') {
            // } else if ($header =='If-Unmodified-Since') {
            // } else if ($header =='Max-Forwards') {
            // } else if ($header =='Pragma') {
            // } else if ($header =='Proxy-Authorization') {
            // } else if ($header =='Range') {
            // } else if ($header =='Referer') {
            // } else if ($header =='The') {
            // } else if ($header =='TE') {
            // } else if ($header =='User-Agent') {
            // } else if ($header =='Via') {
            // } else if ($header =='Warning') {
            // } else if ($header =='Cookie') {
            // } else if ($header =='Origin') {
            // } else if ($header =='Accept-Datetime') {
            // } else if ($header =='X-Requested-With') {
            // } else if ($header =='Accept') {
            // } else if ($header =='Access-Control-Allow-Origin') {
            // } else if ($header =='Used') {
            // } else if ($header =='Refresh') {
            // } else if ($header =='Expires') {
            // } else if ($header =='Set-Cookie') {
            // } else if ($header =='Strict-Transport-Security') {
            // } else if ($header =='Accept-Patch') {
            // } else if ($header =='Accept-Ranges') {
            // } else if ($header =='Age') {
            // } else if ($header =='Allow') {
            // } else if ($header =='Cache-Control') {
            // } else if ($header =='Connection') {
            // } else if ($header =='Content-Encoding') {
            // } else if ($header =='Content-Language') {
            // } else if ($header =='Content-Length') {
            // } else if ($header =='Content-Location') {
            // } else if ($header =='Content-MD5') {
            // } else if ($header =='Content-Disposition') {
            // } else if ($header =='Content-Range') {
            // } else if ($header =='Content-Type') {
            // } else if ($header =='Date') {
            // } else if ($header =='ETag') {
            // } else if ($header =='Last-Modified') {
            // } else if ($header =='Link') {
            // } else if ($header =='Location') {
            // } else if ($header =='This') {
            // } else if ($header =='P3P') {
            // } else if ($header =='Pragma') {
            // } else if ($header =='Proxy-Authenticate') {
            // } else if ($header =='Retry-After') {
            // } else if ($header =='Server') {
            // } else if ($header =='Trailer') {
            // } else if ($header =='The') {
            // } else if ($header =='Transfer-Encoding') {
            // } else if ($header =='Upgrade') {
            // } else if ($header =='Vary') {
            // } else if ($header =='Warning') {
            // } else if ($header =='WWW-Authenticate') {
            // } else if ($header =='Clickjacking') {
            // } else if ($header =='X-Frame-Options') {
            // } else if ($header =='X-XSS-Protection') {
            // } else if ($header =='X-WebKit-CSP') {
            // } else if ($header =='X-Content-Type-Options') {
            // } else if ($header =='X-Powered-By') {
            // } else if ($header =='X-UA-Compatible') {
            } else {
                $header = new Header($header, $value);
            }

            $headers[] = $header;
        }

        return new Headers($headers);
    }
}
