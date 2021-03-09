<?php


namespace Brace\Core\Helper;


use Psr\Http\Message\ResponseInterface;

class Cookie
{
    public static function setCookie(
        ResponseInterface $response,
        string $name,
        string $value = null,
        int $expire = 0,
        string $path = '',
        string $domain = null,
        bool $secure = false,
        bool $httpOnly = true
    ): ResponseInterface {
        // from PHP source code
        if (preg_match("/[=,; \t\r\n\013\014]/", $name)) {
            throw new \InvalidArgumentException(sprintf('The cookie name "%s" contains invalid characters.', $name));
        }
        if (empty($name)) {
            throw new \InvalidArgumentException('The cookie name cannot be empty.');
        }

        // convert expiration time to a Unix timestamp
        if ($expire instanceof \DateTimeInterface) {
            $expire = $expire->format('U');
        } elseif (!is_numeric($expire)) {
            $expire = strtotime($expire);
        }
        if (false === $expire) {
            throw new \InvalidArgumentException('The cookie expiration time is not valid.');
        }

        $str = urlencode($name) . '=';

        if ('' === (string)$value) {
            $str .= 'deleted; expires=' . gmdate('D, d-M-Y H:i:s T', time() - 31536001);
        } else {
            $str .= rawurlencode($value);

            if (0 !== $expire) {
                $str .= '; expires=' . gmdate('D, d-M-Y H:i:s T', $expire);
            }
        }

        if ($path !== null) {
            $str .= '; path=' . $path;
        }

        if ($domain !== null) {
            $str .= '; domain=' . $domain;
        }

        if (true === $secure) {
            $str .= '; secure';
        }

        if (true === $httpOnly) {
            $str .= '; httponly';
        }
        return $response->withHeader('Set-Cookie', $str);
    }
}