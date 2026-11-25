// Consume GET /api/stream from ndjson-handler.php as records arrive.
async function readStream() {
    const response = await fetch('/api/stream');
    if (!response.ok || !response.body) {
        throw new Error(`HTTP ${response.status}: stream unavailable`);
    }

    const reader = response.body.pipeThrough(new TextDecoderStream()).getReader();
    let buffer = '';
    let finished = false;

    try {
        while (true) {
            const { value, done } = await reader.read();
            if (done) break;

            buffer += value;
            const lines = buffer.split('\n');
            buffer = lines.pop(); // Keep an incomplete line for the next chunk.

            for (const line of lines) {
                if (!line.trim()) continue;
                const event = JSON.parse(line);
                console.log(event);
                if (event.type === 'result') {
                    finished = true;
                    if (!event.success) throw new Error('Process failed');
                }
            }
        }

        if (buffer.length || !finished) throw new Error('Incomplete stream');
    } finally {
        await reader.cancel();
        reader.releaseLock();
    }
}

readStream();
