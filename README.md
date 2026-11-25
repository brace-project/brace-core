# brace-core

Brace Core provides the application framework and abstract HTTP interfaces, not a concrete HTTP backend. It is normally used together with [brace/mod-request-laminas](https://github.com/brace-project/brace-mod-request-laminas), which provides PSR-7 requests, query parameters, response factories and response emission.

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


## Streaming

Use `$app->responseFactory->createStreamedResponse($callback)` for general
streaming of raw output. The callback runs when the response is emitted.

For NDJSON streaming, use `$app->responseFactory->createNdjsonResponse($callback)` to return a
deferred stream of JSON objects. The callback receives
`Brace\Core\NdjsonStream`; call `send()` for each record.

- [PHP controller](https://github.com/brace-project/brace-mod-request-laminas/blob/main/examples/ndjson-handler.php)
- [JavaScript client](https://github.com/brace-project/brace-mod-request-laminas/blob/main/examples/ndjson-client.js)
- [Server configuration](https://github.com/brace-project/brace-mod-request-laminas/blob/main/examples/NDjson%20Config.md)
