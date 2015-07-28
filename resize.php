<?php 
	/**
	 * Generate a thumb of a picture
	 * @param  string  $from_file   [source picture to thumb]
	 * @param  integer $to_width   	[thumb width]
	 * @param  boolean $constain    [optional:whether lock resolution;default:true]
	 * @param  integer $to_height   [optional:thumb height(disabled if constain=true);default:0]
	 * @param  string  $to_file     [optional:output to which file(if exist overwrite, if to_file=false print out the thumb; file extension is not essential);default:out]
	 * @param  integer $to_type     [optional:define output file type(if 0 the same as source) Possible Values: IMAGETYPE_GIF IMAGETYPE_PNG IMAGETYPE_JPEG ;default:0]
	 * @return void              
	 */
	function resize($from_file, $to_width, $constain=true, $to_height=0, $to_file="out", $to_type=0) {

		//获得文件基本信息，获得图片类型
		$from_info=getimagesize($from_file);
		$from_type=$from_info[2];
		$from_width=$from_info[0];
		$from_height=$from_info[1];
		$radio=$from_width/$from_height;
		$from_mime=$from_info['mime'];
		if($to_type==0) {
			$to_type=$from_type;
		}
		$to_mime=$from_mime;
		$to_extension=image_type_to_extension($to_type);
		preg_match('/\w+\.\w+/', $to_file, $result);
		if(!$result[0]) {
			$to_file=$to_file.$to_extension;
		}

		//根据图片格式导入图片资源
		switch($from_type) {
			case 1: $from_img=imagecreatefromgif($from_file); break;
			case 2: $from_img=imagecreatefromjpeg($from_file); break;
			case 3: $from_img=imagecreatefrompng($from_file); break;
			default: exit;
		}

		//若等比例缩放，保持比例不变，计算缩略图高度
		if($constain) {
			$to_height=floor($to_width/$radio);
		}

		//创建缩略图资源，开始缩放
		$to_img=imagecreatetruecolor($to_width, $to_height);
		imagecopyresampled($to_img, $from_img, 0, 0, 0, 0, $to_width, $to_height, $from_width, $from_height);

		if($to_file != 'out'.$to_extension) {
			switch ($to_type) {
				case 1:
					imagegif($to_img, $to_file);
					break;
				
				case 2:
					imagejpeg($to_img, $to_file);
					break;
				
				case 3:
					imagepng($to_img, $to_file);
					break;
				
				default:
					break;
			}
		}else {	
			header("content-type: {$to_mime}");
			switch ($to_type) {
				case 1:
					imagegif($to_img);
					break;
				
				case 2:
					imagejpeg($to_img);
					break;
				
				case 3:
					imagepng($to_img);
					break;
				
				default:
					break;
			}
		}


	}

	resize('./img/src.png', 90);    //测试直接输出缩略图

 ?>
