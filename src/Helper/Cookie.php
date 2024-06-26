<?php


namespace Brace\Core\Helper;


use Psr\Http\Message\ResponseInterface;

class Cookie
{
    /**
     * Set a cookie in an HTTP response with various options including SameSite for CORS.
     *
     * The SameSite attribute is crucial in CORS contexts as it allows you to assert
     * if your cookie should be restricted to a first-party or same-site context.
     * Setting SameSite to 'None' allows the cookie to be sent in cross-origin requests,
     * providing that the cookie is also set with the Secure attribute.
     *
     * @param ResponseInterface $response The response object to which the cookie will be added.
     * @param string $name The name of the cookie.
     * @param string|null $value The value of the cookie (default null).
     * @param int $expire The expiration time of the cookie as a Unix timestamp (default 0).
     * @param string $path The path on the server where the cookie will be available (default '').
     * @param string|null $domain The domain that the cookie is available to (default null).
     * @param bool $secure Indicates that the cookie should only be transmitted over a secure HTTPS connection (default false).
     * @param bool $httpOnly When TRUE the cookie will be made accessible only through the HTTP protocol (default true).
     * @param string $sameSite The SameSite attribute of the cookie (None, Lax, Strict). Defaults to 'Lax'.
     *
     * @return ResponseInterface
     */
    public static function setCookie(
        ResponseInterface $response,
        string $name,
        string $value = null,
        int $expire = 0,
        string $path = '',
        string $domain = null,
        bool $secure = false,
        bool $httpOnly = true,
        string $sameSite = 'Lax' // Default to 'Lax' which is commonly a safe default
    ): ResponseInterface {
        if (preg_match("/[=,; \t\r\n\013\014]/", $name)) {
            throw new \InvalidArgumentException(sprintf('The cookie name "%s" contains invalid characters.', $name));
        }
        if (empty($name)) {
            throw new \InvalidArgumentException('The cookie name cannot be empty.');
        }

        if ($expire instanceof \DateTimeInterface) {
            $expire = $expire->getTimestamp();
        } elseif (!is_numeric($expire)) {
            $expire = strtotime($expire);
        }
        if ($expire === false) {
            throw new \InvalidArgumentException('The cookie expiration time is not valid.');
        }

        $str = urlencode($name) . '=' . rawurlencode($value ?? 'deleted');
        if ($expire !== 0)
            $str .= '; expires=' . gmdate('D, d-M-Y H:i:s T', $expire);
        $str .= $path ? "; path=$path" : '';
        $str .= $domain ? "; domain=$domain" : '';
        $str .= $secure ? '; secure' : '';
        $str .= $httpOnly ? '; httponly' : '';
        $str .= "; SameSite=$sameSite"; // Append SameSite attribute

        return $response->withHeader('Set-Cookie', $str);
    }
}
