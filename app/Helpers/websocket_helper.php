<?php 
    use WebSocket\Client;

    function send($msg, $action = 'A'){
        try{
            $msgObj = [
                'user_id'=> 2,
                'recipient_id'=> null,
                'type'=> 'socket',
                'token'=> null,
                'message'=> null
            ];
            $msgJson = json_encode($msgObj);
            $client = new Client("ws://localhost:8282");
            $client->send($msgJson);
            $msgObj['recipient_id'] = 1;
            $msgObj['type'] = 'chat';
            $msgObj['action'] = $action;
            $msgObj['message'] = $msg;
            $msgJson = json_encode($msgObj);
            $client->send($msgJson);
            
        }catch(Exception $e){
            output('error', $e->getMessage());
        }
        
    }
?>