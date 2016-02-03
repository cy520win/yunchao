<?php 
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */ 
//清空文件缓存，方法：删除runtime文件夹
	error_reporting(E_ALL^E_NOTICE^E_WARNING); 
	$path = 'Runtime/';
	delDirAndFile($path, $path);
	echo '没有生成缓存！'; 

	function delDirAndFile($dirName, $root) {
		$rootfolder = $root;
		if ($handle = opendir("$dirName")) {
			while (false !== ( $item = readdir($handle) )) {
				if ($item != "." && $item != "..") {
					if (is_dir("$dirName/$item")) {
							delDirAndFile("$dirName/$item", $root);
					} else {
						if (unlink("$dirName/$item"))
							echo "成功删除文件： $dirName/$item\</br>";
					}
				}
			}
			closedir($handle);
			if ($dirName != $rootfolder . '/Cache' && $dirName != $rootfolder . '/Temp' && $dirName != $rootfolder . '/Data') {
				if (rmdir($dirName)) {
					echo "成功删除目录： $dirName\n";
				}
			}
		}
	}


?>