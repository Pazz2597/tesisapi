<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\CodeigniterWebsocket as CodeIgniterWebsocket;
use Exception;
use WebSocket\Client;
/**
 * @package   CodeIgniter WebSocket Library: Websocket Controller
 * @category  Libraries
 * @author    Taki Elias <taki.elias@gmail.com>
 * @license   http://opensource.org/licenses/MIT > MIT License
 * @link      https://github.com/takielias
 *
 * CodeIgniter WebSocket library. It allows you to make powerful realtime applications by using Ratchet Websocket
 */
class Websocket extends Controller
{
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var CodeIgniterWebsocket
     */
    private $ws ;

    public function __construct()
    {
        helper('codeigniter_websocket');
        $this->config = config('CodeigniterWebsocket');
    }

    public function start()
    { 
        output('info', 'Corriendo web socket');
        $this->ws = service('CodeigniterWebsocket');

        $this->ws->set_callback('auth', array($this, '_auth'));
        $this->ws->set_callback('event', array($this, '_event'));
        $this->ws->run();
    }

    public function user($user_id = null)
    {
        return view('Websocket/websocket_message', array('user_id' => $user_id));
    }

    public function _auth($datas = null)
    {
        // Here you can verify everything you want to perform user login.

        return (!empty($datas->user_id)) ? $datas->user_id : false;
    }

    public function _event($datas = null)
    {
        //$this->ws->
        // Here you can do everyting you want, each time message is received
        echo 'Hey ! I\'m an EVENT callback' . PHP_EOL;
    }
    public function send(){
        try{
            $msgObj = [
                'user_id'=> 2,
                'recipient_id'=> null,
                'type'=> 'socket',
                'token'=> null,
                'message'=> 'Holi !'
            ];
            $msgJson = json_encode($msgObj);
            //$client = new WebSocketClient("wss://marketdata.tradermade.com/feedadv");
            $client = new Client("ws://localhost:8282");
            $client->send($msgJson);
            $msgObj['recipient_id'] = 1;
            $msgObj['type'] = 'chat';
            $msgJson = json_encode($msgObj);
            $client->send($msgJson);
            
        }catch(Exception $e){
            output('error', $e->getMessage());
        }
        
    }
}
