<?php
return array(
	/* 数据库设置 */
    'DB_TYPE'               =>  'mysqli',     // 数据库类型
    'DB_HOST'               =>  'm.db.gaodun.com', // 服务器地址
    'DB_NAME'               =>  'yc_shixi',          // 数据库名
    'DB_USER'               =>  'gdtest',      // 用户名
    'DB_PWD'                =>  'gdmysql_221',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  '',    // 数据库表前缀
		/* 允许访问的模块列表 */
	'MODULE_ALLOW_LIST'    =>    array('Frontend','Admin','Wechat'),

	/* 模块相关配置 */
	'DEFAULT_MODULE'     => 'Frontend',

	/* 系统数据加密设置 */
	'DATA_AUTH_KEY' => 'cLQixSyTZ*"8=eAol2|GWV;>,gO#Xs5Nd])4mMvU', //默认数据加密KEY

	/* URL配置 */
	'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL'            => 2, //URL模式
	'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
	'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
	
    'MEMCACHE_HOST' => '127.0.0.1:11211', // 默认连接ip
    'MEMCACHE_PORT' => '11211', // 默认链接端口
    'DATA_CACHE_TYPE' => 'Memcache', // 数据缓存方式memcache440000, // 数据缓存有效期 10 秒
    'DATA_CACHE_TIME' => 1440000, // 数据缓存有效期 10 秒
);