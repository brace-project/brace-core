# NDJSON configuration

Disable response buffering and compression along the entire HTTP path.
The following settings apply to the example endpoint `/api/stream`.

The application defines the record schema and should send an explicit final
success/failure record. The callback runs outside the middleware pipeline;
after output begins, the HTTP status cannot change.

## PHP

Configure the streaming endpoint with:

```ini
output_buffering = Off
zlib.output_compression = Off
display_errors = Off
max_execution_time = 600
```

Brace removes empty removable PHP buffers and disables zlib compression
where possible. Each open stream occupies a PHP worker. Adjust execution and proxy timeouts
to the actual workload; a heartbeat only helps if the process can emit it.

## PHP sessions

If a PHP session is active, release its lock after authentication and
session updates, before starting the long-running callback:

```php
if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}
```

Do not rely on subsequent session writes being persisted after closing it.

## Nginx reverse proxy (HTTP backend)

```nginx
location /api/stream {
    proxy_pass http://apache:80;
    proxy_http_version 1.1;
    proxy_set_header Connection "";
    proxy_buffering off;
    proxy_cache off;
    gzip off;
    proxy_set_header Accept-Encoding "";
    proxy_read_timeout 600s;
    send_timeout 600s;
}
```

The response also sends `X-Accel-Buffering: no` (unless Nginx is configured
to ignore that header). For Nginx directly connected to PHP-FPM instead,
use `fastcgi_buffering off`, `fastcgi_cache off` and
`fastcgi_read_timeout 600s` in the existing FastCGI location.
Do not manually set Transfer-Encoding or Content-Length.

## Apache

For mod_php and mod_proxy_fcgi, disable compression for the endpoint
and configure appropriate timeouts:

```apache
Timeout 600
SetEnvIf Request_URI "^/api/stream" no-gzip=1
```

For mod_proxy_fcgi only, enable immediate flushing on the existing
FastCGI worker (example worker address, adapt to your configuration):

```apache
ProxyTimeout 600
<Proxy "fcgi://php-fpm:9000/">
    ProxySet flushpackets=on timeout=600
</Proxy>
```

With PHP-FPM also adjust the pool's `request_terminate_timeout`, e.g.
`600s`. All timeout values are examples; the FPM termination timeout
limits the request itself, unlike Nginx's read-idle timeout.

## Verify through the actual proxy

```sh
curl --http1.1 -N -i https://example.org/api/stream
```

The log record should arrive before the final result (one second later
in the example), not together at the end. Repeat over HTTP/2 if enabled.
Network chunks are not JSON records; always split decoded data by newline.

References: [Nginx proxy module](https://nginx.org/en/docs/http/ngx_http_proxy_module.html),
[Apache mod_proxy](https://httpd.apache.org/docs/2.4/mod/mod_proxy.html),
[PHP flush](https://www.php.net/manual/en/function.flush.php).
