<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

/*定义路由后，不允许在使用原地址*/

//两种方式

//admin 模块
//Route::rule('admin','admin/index/index');

//index 模块
//Route::rule('index','index/Index/index'); //定以后无法跳转别的模板文件

//api 接口模块的
Route::rule('xsindex','api/xcx/index');
Route::rule('xsdetail','api/xcx/conpanyDetail'); 

return [
	//'xcxindex'     => 'admin/xcx/index',
];

/*示例
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
];
*/

