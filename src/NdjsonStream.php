<?php

namespace Brace\Core;

/**
 * Writes newline-delimited JSON objects to an active HTTP response.
 */
class NdjsonStream
{
    /**
     * Serialize one object and flush it to the web server.
     *
     * @param array|object $data Object properties or an object to serialize.
     * @throws \JsonException If the data cannot be serialized.
     * @throws \UnexpectedValueException If serialization does not yield an object.
     * @see BraceResponseFactoryInterface::createNdjsonResponse()
     *
     * Example: $stream->send(['type' => 'log', 'message' => 'Started']);
     */
    public function send(array|object $data): void
    {
        $json = json_encode((object) $data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        if (!str_starts_with($json, '{')) {
            throw new \UnexpectedValueException('NDJSON requires a JSON object.');
        }

        echo $json . "\n";
        flush();
    }

    /**
     * Check whether PHP has detected a client disconnect.
     *
     * @return bool True until PHP detects that the connection was aborted.
     * @see connection_aborted()
     *
     * Example: if (!$stream->isConnected()) { return; }
     */
    public function isConnected(): bool
    {
        return connection_aborted() === 0;
    }
}
