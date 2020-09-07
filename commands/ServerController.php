<?php
namespace app\commands;

use consik\yii2websocket\WebSocketServer;
use yii\console\Controller;
use app\daemons\ChatServer;

class ServerController extends Controller
{
	public function actionStart()
	{
		$server = new ChatServer();
		$server->port = 8080;

		$server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN_ERROR, function($e) use($server) {
			echo "Error opening port " . $server->port . "\n";
			$server->port += 1;
			$server->start();
		});

		$server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN, function($e) use($server) {
			echo "Server started at port " . $server->port;
		});

		$server->start();
	}
}