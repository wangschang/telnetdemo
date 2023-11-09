<?php
/*
 * @Author: wangschang wangschang@126.com
 * @Date: 2023-11-09 10:38:36
 * @LastEditors: wangschang wangschang@126.com
 * @LastEditTime: 2023-11-09 12:03:05
 * @FilePath: /undefined/private/var/folders/0h/dbxfj4qd0019htj9gst9rnp00000gn/T/ch.sudo.cyberduck/42a7bc5e-8103-4cbe-af8d-ff5f176ac9de/root/telnet/telnet/app/Console/Commands/telnetServer.php
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Library\Helper;

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
       if(trim($data) =='conv_tree'){
            return  telnetServer::convTree();
       }  
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
	  }else{//'conv_tree'
          return "nodata";  
      }
    }
    /**
     * conv tree
     * 蔬菜/豆制品,叶菜类,空心菜类
     * 转成三级目录  namePath 1 蔬菜/豆制品 , 二级 叶菜类, 三级 空心菜类
     * @return void
     */
    public static function convTree(){
        $jsondata = '[{"id": 200002538,
            "name": "空心菜类",
            "level": 3,
            "namePath": "蔬菜/豆制品,叶菜类,空心菜类"
            },
            {"id": 200002537,
                "name": "香菜类",
                "level": 3,
                "namePath": "蔬菜/豆制品,葱姜蒜椒/调味菜,香菜类"
            },
            {"id": 200002536,
                "name": "紫苏/苏子叶",
                "level": 3,
                "namePath": "蔬菜/豆制品,叶菜类,紫苏/苏子叶"
            },
            { "id": 200002543,
                "name": "乌塌菜/塌菜/乌菜",
                "level": 3,
                "namePath": "蔬菜/豆制品,叶菜类,乌塌菜/塌菜/乌菜"
            },
            {"id": 200002542,
                "name": "菜心/菜苔类",
                "level": 3,
                "namePath": "蔬菜/豆制品,叶菜类,菜心/菜苔类"
            },
            { "id": 200002540,
                "name": "马兰头/马兰/红梗菜",
                "level": 3,
                "namePath": "蔬菜/豆制品,叶菜类,马兰头/马兰/红梗菜"
            },
            {"id": 200002531,
                "name": "苋菜类",
                "level": 3,
                "namePath": "蔬菜/豆制品,叶菜类,苋菜类"
            },
            {"id": 200002528,
                "name": "其他叶菜类",
                "level": 3,
                "namePath": "蔬菜/豆制品,叶菜类,其他叶菜类"
            }
        ]';
        $init_data = json_decode($jsondata,true);
        
        $root_path = [];//第一层对应的id和名称
        $middle_path = [];//中间层对应的id和名称
        #$leaf = [];//最低层的叶子节点
        foreach($init_data as $k=>$sub_data){
            $paths = explode(",",$sub_data['namePath']);
            $root_name = $paths[0];
            $middle_name = $paths[1];
            $leaf_name = $paths[2];
            //一级
            if(!array_key_exists($root_name,$root_path)){
                $root_id = Helper::genRandStr($k);//通过自增的id保持唯一
                $root_path[$root_name] = [
                    "id"=>$root_id,
                    "id_path"=>",".$root_id.",",
                    "level"=>1,
                    "name"=>$root_name,
                    "name_path"=>$root_name,
                    "parent_id"=>0,
                    "children"=>[]
                ];
            }else{//查询rootid
                $root_info = $root_path[$root_name];
                $root_id = $root_info['id'];
            }
            //二级  is_leaf 没明白意思没实现
            if(!array_key_exists($middle_name,$middle_path)){
                $k1 = "m".$k;
                $middle_id = Helper::genRandStr($k1);
                $middle_path[$middle_name] = [
                    "id"=>$middle_id,
                    "id_path"=>",".$root_id.",".$middle_id,
                    "is_leaf"=>2,
                    "level"=>2,
                    "name"=>$middle_name,
                    "name_path"=>$root_name.",".$middle_name,
                    "parent_id"=>$root_id,
                    "parent_name"=>$root_name,
                    "children"=>[]
                ];
            }else{
                $middle_info = $middle_path[$middle_name];
                $middle_id = $middle_info['id'];
            }
            //最底层
            $leafdata = [
                "id"=> $sub_data['id'],
                "id_path"=> ",".$root_id.",".$middle_id.",".$sub_data['id'],
                "is_leaf"=> 1,
                "level"=> $sub_data['level'],
                "name"=> $sub_data['name'],
                "name_path"=>$sub_data['namePath'],
                "parent_id"=>$middle_id

            ];
            $middle_path[$middle_name]['children'][] = $leafdata;
        }
        //循环二级附加到一级上面
        foreach($middle_path as $middle){
            $root_path[$middle['parent_name']]['children'][] = $middle;
        }
        return json_encode(array_values($root_path),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        
    }
    
}


