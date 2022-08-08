<?php

namespace App\Services;
use App\Models\Category;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Table;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
/**
 * @see https://www.swoole.co.uk/docs/modules/swoole-websocket-server
 */
class WebSocketService implements WebSocketHandlerInterface
{
    /**@var Table $ws_table */
    public Table $ws_table;

    // Declare constructor without parameters
    public function __construct()
    {
        $this->ws_table = app('swoole')->cat_fdTable;
    }

    public function onOpen(Server $server, Request $request)
    {
        $category_id = request()->get('category_id') ?? null;
        $category = null;
        if($category_id) {
            /** @var Category $category */
            $category = Category::query()
                    ->with('twits', function($q) {
                        $q->orderBy('id', 'desc')->limit(10);
                    })
                    ->find($category_id);
        }

        if(!$category_id || !$category) {
            $server->disconnect($request->fd, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            return;
        }

        $this->ws_table->set('cat_id:' . $category_id . '_'. $request->fd, ['value' => $request->fd]);
        $this->ws_table->set('fd:' . $request->fd, ['value' => $category_id]);

        $server->push($request->fd, json_encode([
            'twits' => $category->twits
        ], JSON_UNESCAPED_UNICODE));
    }
    public function onMessage(Server $server, Frame $frame)
    {
        $server->push($frame->fd, date('Y-m-d H:i:s'));
    }
    public function onClose(Server $server, $fd, $reactorId)
    {
        $cat_id = $this->ws_table->get('fd:' . $fd);

        if ($cat_id !== false) {
            $this->ws_table->del('cat_id:' . $cat_id['value'] . '_' . $fd);
        }

        $this->ws_table->del('fd:' . $fd);
    }
}