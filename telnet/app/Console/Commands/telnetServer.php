<?php
/*
 * @Author: wangschang wangschang@126.com
 * @Date: 2023-11-09 10:38:36
 * @LastEditors: wangschang wangschang@126.com
 * @LastEditTime: 2023-11-09 10:44:13
 * @FilePath: /undefined/private/var/folders/0h/dbxfj4qd0019htj9gst9rnp00000gn/T/ch.sudo.cyberduck/42a7bc5e-8103-4cbe-af8d-ff5f176ac9de/root/telnet/telnet/app/Console/Commands/telnetServer.php
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class telnetServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telnet:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start tcp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
	$server = new \Swoole\Server('127.0.0.1', 8765);
        $server->on('start', function ($server) {
		    echo "TCP Server is started at tcp://127.0.0.1:8765\n";
	});

	$server->on('connect', function ($server, $fd){
		    echo "connection open: {$fd}\n";
	});

	$server->on('receive', function ($server, $fd, $reactor_id, $data) {
		    $server->send($fd, telnetServer::callServer($data));
	});

	$server->on('close', function ($server, $fd) {
		    echo "connection close: {$fd}\n";
	});

	$server->start();
        #return 0;
    }
    public static function callServer($data){
      $datas = explode(" ",$data);
      if($datas[0]=='div'){
	      if(isset($datas[1]) && isset($datas[2]) && intval($datas[2])!=0){
	          return round(intval($datas[1])/intval($datas[2]),3);
	      }else{
	          return 'data error';
	      }
      }elseif($datas[0]=='mul'){
	      if(isset($datas[1]) && isset($datas[2])){
	         return (int)$datas[1] * (int)$datas[2];
	      }else{
	         return 'data error';
	      } 
              
      }elseif($datas[0]=='incr'){
	      if(isset($datas[1]) ){
		   return intval($datas[1]) +1;
	      }else{
		      return 'data error';
	      }
	  }elseif($datas[0]=='conv_tree'){
          return  telnetServer::convTree(); 
      }
    }
    //convdaa
    public static function convTree(){
        
    }
}


