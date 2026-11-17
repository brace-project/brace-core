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