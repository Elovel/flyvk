<?php

error_reporting(E_ALL);
ini_set('display_errors','1');
ini_set('memory_limit' , '-1');
ini_set('max_execution_time','0');
ini_set('display_startup_errors','1');
use \danog\MadelineProto\API;
use \danog\Loop\Generic\GenericLoop;
use \danog\MadelineProto\EventHandler;
use function Amp\File\{get, put, exists, unlink};
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';
!is_dir('data')?mkdir('data'):NULL;
if(!file_exists('data.json')){
file_put_contents('data.json','{"answering":[],"mentioned":[]}' );
} 

class XHandler extends EventHandler
{
    const Admins = [740910481];
    const Report = 'hrlot';
    
    public function getReportPeers()
    {
        return [self::Report];
    }
    
    public function genLoop()
    {
        yield $this->account->updateStatus([
            'offline' => false
        ]);
      
                
				
	            
        return 60000 ;
    }
    
    public function onStart()
    {
        $genLoop = new GenericLoop([$this, 'genLoop'], 'update Status');
        $genLoop->start();
    }
    
    public function onUpdateNewChannelMessage($update)
    {
        yield $this->onUpdateNewMessage($update);
    }
    
    public function onUpdateNewMessage($update)
    {
        if (time() - $update['message']['date'] > 2) {
            return;
        }
        try {
            $data = json_decode(file_get_contents("data.json"), true); 
            $msgOrig   = $update['message']['message']?? null;
            $messageId = $update['message']['id']?? 0;
            $fromId    = $update['message']['from_id']['user_id']?? 0;
            $replyToId = $update['message']['reply_to']['reply_to_msg_id']?? 0;
            $peer      = yield $this->getID($update);
                
foreach ($data['answering'] as $key => $val) {
    if (strpos($msgOrig, $key) !== false && $update['message']['out'] == false){
yield $this->messages->setTyping(['peer' => $peer, 'action' => ['_' => 'sendMessageTypingAction']]);
yield $this->sleep(6);
      yield $this->messages->sendMessage(['peer' =>$peer, 'message' => $val, 'reply_to_msg_id' =>$messageId]);
}
} 
foreach ($data['mentioned'] as $key => $val) {
    if (strpos($msgOrig, $key) !== false && $update['message']['out'] == false && $replyToId && $update['message']['mentioned']){
yield $this->messages->setTyping(['peer' => $peer, 'action' => ['_' => 'sendMessageTypingAction']]);
yield $this->sleep(6);
      yield $this->messages->sendMessage(['peer' =>$peer, 'message' => $val, 'reply_to_msg_id' =>$messageId ]);
}
} 


            if((in_array($fromId, self::Admins))) {
                if(preg_match('/^[\/\#\!\.]?(ping|ربات)$/si', $msgOrig)) {
                    yield $this->messages->sendMessage([
                        'peer'            => $peer,
                        'message'         => 'Pong !',
                        'reply_to_msg_id' => $messageId
                    ]);
                }
                elseif (preg_match('/^[\/\#\!]?(restart|ریستارت)$/si',$msgOrig)){
                    yield $this->messages->sendMessage([
                        'peer'            => $peer,
                        'message'         => 'Restarted ...',
                        'reply_to_msg_id' => $messageId
                    ]);
                    $this->restart();
                }
elseif(preg_match( "/^(j) (.*)$/i", $msgOrig , $t)){
$id = $t[ 2 ];
yield $this->channels->joinChannel( [ 'channel' => "$id" ] );
yield $this->messages->sendMessage( [ 'peer' => $peer,  'message' => '🔰 joined. ',
'reply_to_msg_id' => $messageId
] );
} 
elseif(preg_match("/^[\/\#\!]?(پ) (.*)$/i",$msgOrig)){
$ip = str_replace("پ ","",$msgOrig);
$ip = explode("+",$ip."+++++");
$txxt = $ip[0];
$answeer = trim($ip[1]);
if(!isset($data['answering'][$txxt])){
$data['answering'][$txxt] = $answeer;
file_put_contents("data.json", json_encode($data));
yield $this->messages->sendMessage(['peer' =>$peer, 'message' => "کلمه جدید به لیست پاسخ شما اضافه شد"]);
}else{
yield $this->messages->sendMessage(['peer' =>$peer, 'message' => "این کلمه از قبل موجود است "]);
}
}
elseif(preg_match("/^[\/\#\!]?(پا) (.*)$/i",$msgOrig)){
$ip = str_replace("پا ","",$msgOrig);
$ip = explode("+",$ip."+++++");
$txxt = $ip[0];
$answeer = trim($ip[1]);
if(!isset($data['mentioned'][$txxt])){
$data['mentioned'][$txxt] = $answeer;
file_put_contents("data.json", json_encode($data));
yield $this->messages->sendMessage(['peer' =>$peer, 'message' => "کلمه جدید به لیست پاسخ شما اضافه شد"]);
}else{
yield $this->messages->sendMessage(['peer' =>$peer, 'message' => "این کلمه از قبل موجود است "]);
}
}
elseif($msgOrig == 'خالی'){
$data['answering'] = [];
$data['mentioned'] = [];
file_put_contents("data.json", json_encode($data));
yield $this->messages->sendMessage(['peer' =>$peer, 'message' => "لیست پاسخها خالی شد 👌!"]);
}

if(preg_match("/^[\/\#\!]?(ح) (.*)$/i",$msgOrig) ){
$ip = str_replace("ح ","",$msgOrig);
$s=trim($ip,"+");
if(isset($data['answering'][$s])){
unset($data['answering'][$s]);
file_put_contents("data.json", json_encode($data));
yield $this->messages->sendMessage(['peer' =>$peer, 'message' => "
کلمه $s از لیست حذف شد 👌"]);
}else{
yield $this->messages->sendMessage(['peer' =>$peer, 'message' => "این کلمه از قبل موجود نبود😐"]);
}
}

if(preg_match("/^[\/\#\!]?(حذ) (.*)$/i",$msgOrig) ){
$ip = str_replace("حذ ","",$msgOrig);
$s=trim($ip,"+");
if(isset($data['mentioned'][$s])){
unset($data['mentioned'][$s]);
file_put_contents("data.json", json_encode($data));
yield $this->messages->sendMessage(['peer' =>$peer, 'message' => "
کلمه $s از لیست حذف شد 👌"]);
}else{
yield $this->messages->sendMessage(['peer' =>$peer, 'message' => "این کلمه از قبل موجود نبود😐"]);
}
}


elseif($msgOrig == 'لیست' ){
if(count($data['answering']) > 0){
$txxxt = "لیست پاسخ :
";
$counter = 1;
foreach($data['answering'] as $k =>$ans){
$txxxt .= "$counter • $k =>$ans\n";
$counter++;
} 
$i =0;
foreach($data['mentioned'] as $k =>$ans){
$txxxt .= "$i • $k =>$ans\n";
$i++;
}
yield $this->messages->sendMessage(['peer' =>$peer, 'message' =>$txxxt]);
}else{
yield $this->messages->sendMessage(['peer' =>$peer, 'message' => "پاسخی وجود ندارد!"]);
}
}
elseif(preg_match('/^[\/\#\!\.]?(status|وضعیت|وضع|مصرف|usage)$/si', $msgOrig)){
                    $answer = 'Memory Usage : ' . round(memory_get_peak_usage(true) / 1021 / 1024, 2) . ' MB';
                    yield $this->messages->sendMessage([
                        'peer'            => $peer,
                        'message'         => $answer,
                        'reply_to_msg_id' => $messageId
                    ]);
                }
            }
            
        } catch (\Throwable $e){
            
        }
    }
}
$settings = [
    'serialization' => [
        'cleanup_before_serialization' => true,
    ],
    'logger' => [
        'max_size' => 1*1024*1024,
    ],
    'peer' => [
        'full_fetch' => false,
        'cache_all_peers_on_startup' => false,
    ]
];

$bot = new \danog\MadelineProto\API('X.session', $settings);
$bot->startAndLoop(XHandler::class);
?>
