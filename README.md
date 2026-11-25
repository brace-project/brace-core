# brace-core

## Application URLs

Configure the browser application mount and API base URL once on the application:

```php
$app = new BraceApp(
    appBasePath: '/demo',
    apiBaseUrl: 'https://api.example.test/v1',
);
```

The normalized `BraceApp::$appBasePath` is either an empty string or a path such as `/demo`. Modules such as `brace/mod-router` and `brace/mod-spaserve` use it as their default public mount. `BraceApp::$apiBaseUrl` is independent and may point to another path or server. When omitted, it defaults to `appBasePath`.

## Dependency Injection

Within the container these Dependencies are available by default

| Name                | Interface                   | Description        |
|---------------------|-----------------------------| -------------------|
| `serverRequest`     | ServerRequestInterface      |   |


## Bridges

Brace

## NDJSON streaming

Use `$app->responseFactory->createNdjsonResponse($callback)` to return a
deferred stream of JSON objects. The callback receives
`Brace\Core\NdjsonStream`; call `send()` for each record.

The response factory and emitter must support streaming, as implemented by
[brace/mod-request-laminas](https://github.com/brace-project/brace-mod-request-laminas).
- [PHP controller](examples/ndjson-handler.php)
- [JavaScript client](examples/ndjson-client.js)
- [Server configuration](examples/NDjson%20Config.md)
