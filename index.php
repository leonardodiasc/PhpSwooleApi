<?php

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Illuminate\Database\Capsule\Manager as Capsule;

require 'vendor/autoload.php';

// In Node.js, you start a server by calling the listen method of an Express application.
// In PHP, you instantiate a new Swoole\HTTP\Server object to create a server.
$server = new Server("127.0.0.1", 9501);

$server->on("start", function (Server $server) {
    echo "Swoole HTTP server started at http://127.0.0.1:9501\n";
});

$server->on("request", function (Request $request, Response $response) {
    $uri = $request->server['request_uri'];
    $method = $request->server['request_method'];

    // In Express.js, you define routes using app.get or app.post methods.
    // In PHP with Swoole, you handle routes by checking the request's URI and method inside a single "request" event handler.
    if ($method === 'GET' && $uri === '/download') {
        $filePath = $request->get['path'];
        if ($filePath) {
            $absolutePath = realpath(__DIR__ . '/' . $filePath);
            if ($absolutePath && is_file($absolutePath)) {
                // PHP's equivalent to Express.js's res.download method is manually setting Content-Type and Content-Disposition headers,
                // and then using the Swoole Response's sendfile method to send the file's contents.
                $response->header('Content-Type', 'application/octet-stream');
                $response->header('Content-Disposition', 'attachment; filename="' . basename($absolutePath) . '"');
                $response->sendfile($absolutePath);
            } else {
                $response->status(500);
                $response->end('An error occurred while downloading the file.');
            }
        } else {
            $response->status(400);
            $response->end('No file path provided.');
        }
    } elseif ($method === 'GET' && $uri === '/api/materiais') {
        try {
            // This is equivalent to Express.js's Material.find method.
            // It's using Laravel's Eloquent ORM to fetch all records from the materials table.
            $materiais = Capsule::table('materials')->get();
            $response->header('Content-Type', 'application/json');
            $response->end(json_encode($materiais));
        } catch (\Exception $error) {
            $response->status(500);
            $response->end(json_encode(['error' => 'An error occurred while fetching materials.']));
        }
    }
});

// This is equivalent to Express.js's app.listen method.
// It starts the Swoole HTTP server, which will then begin listening for requests.
$server->start();
