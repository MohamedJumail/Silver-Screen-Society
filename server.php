<?php
set_time_limit(0);
ob_implicit_flush();

$address = '127.0.0.1';
$port = 8080;
$clients = [];

$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($server, $address, $port);
socket_listen($server);

while (true) {
    $read = array_merge([$server], $clients);
    $write = [];
    $except = [];
    socket_select($read, $write, $except, 0, 10);

    if (in_array($server, $read)) {
        $client = socket_accept($server);
        $clients[] = $client;
        $key = array_search($server, $read);
        unset($read[$key]);
    }

    foreach ($read as $client) {
        $data = @socket_read($client, 1024, PHP_BINARY_READ);
        if ($data === false) {
            $key = array_search($client, $clients);
            unset($clients[$key]);
            socket_close($client);
            continue;
        }

        if (!empty(trim($data))) {
            $data = trim($data);
            if (preg_match('/Sec-WebSocket-Key: (.*)\r\n/', $data, $matches)) {
                $key = base64_encode(pack(
                    'H*',
                    sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
                ));
                $headers = "HTTP/1.1 101 Switching Protocols\r\n";
                $headers .= "Upgrade: websocket\r\n";
                $headers .= "Connection: Upgrade\r\n";
                $headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
                socket_write($client, $headers, strlen($headers));
            } else {
                $decoded_data = decode($data);
                $msg = json_decode($decoded_data['payload'], true);
                if (isset($msg['action']) && $msg['action'] === 'add_movie') {
                    $response = [
                        'name' => $msg['name'],
                        'genre' => $msg['genre']
                    ];
                    foreach ($clients as $send_client) {
                        socket_write($send_client, encode(json_encode($response)));
                    }
                }
            }
        }
    }
}

socket_close($server);

function decode($data) {
    $bytes = unpack('C*', $data);
    $mask = array_slice($bytes, 3, 4);
    $payload = '';
    for ($i = 7; $i < count($bytes); $i++) {
        $payload .= chr($bytes[$i] ^ $mask[($i - 7) % 4]);
    }
    return ['payload' => $payload];
}

function encode($payload) {
    $frame = [];
    $frame[0] = 129;
    $length = strlen($payload);
    if ($length <= 125) {
        $frame[1] = $length;
    } else {
        $frame[1] = 126;
        $frame[2] = ($length >> 8) & 255;
        $frame[3] = $length & 255;
    }
    $frame = array_merge($frame, unpack('C*', $payload));
    return call_user_func_array('pack', array_merge(['C*'], $frame));
}
?>
