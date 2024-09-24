<?php
class imageResize { 
public function __construct($src, $width=190) { 
//$filename – полный путь к файлу.
$filename=$_SERVER['DOCUMENT_ROOT'].$src; 
//функция getimagesize() возвращает информацию о файле.
$size = getimagesize ($filename); 
//ширина изображения:
$w=$size['0']; 
//высота изображения:
$h=$size['1']; 
//mime тип файла:
$type = $size['mime']; 
//отношение ширины к высоте, далее будет использовано для пропорционального ресайза изображения
$ratio = $w/$h; 
//ширина превью:
$th_w = $width; 
//высота превью:
$th_h = $th_w/$ratio; 
//передаем браузеру заголовок типа контента:
header("Content-type: $type"); 
//переключатель типов, возможные варианты изображений: jpg, png и gif:
switch ($type) { 
case 'image/jpeg': 
//создаем изображение из исходного большого изображения:
$src_img = imagecreatefromjpeg($filename); 
//устанавливаем параметры наложения, в данном случае эта строка не пригодится, но она будет нужна, если нужно добавить водяной знак на картинку:
imagealphablending($src_img, true); 
//создаем пустое изображение нужной высоты и ширины, в которое будет скопировно исходное изображение:
$thumbImage = imagecreatetruecolor($th_w, $th_h); 
//копируем большое изображение в маленькое:
imagecopyresampled($thumbImage, $src_img, 0, 0, 0, 0, $th_w, $th_h, $w, $h); 
//выводим в браузер маленькое изображение:
imagejpeg($thumbImage, '', 100);          
break; 
//для остальных форматов все аналогично:
case 'image/png':                                                          
$src_img = imagecreatefrompng($filename); 
imagealphablending($src_img, true); 
$thumbImage = imagecreatetruecolor($th_w, $th_h); 
imagecopyresampled($thumbImage, $src_img, 0, 0, 0, 0, $th_w, $th_h, $w, $h); 
imagepng($thumbImage, '', 0);                
break;
case 'image/gif':
$src_img = imagecreatefromgif($filename); 
imagealphablending($src_img, true); 
$thumbImage = imagecreatetruecolor($th_w, $th_h); 
imagecopyresampled($thumbImage, $src_img, 0, 0, 0, 0, $th_w, $th_h, $w, $h); 
imagegif($thumbImage, '', 100);
break; 
default: 
//echo "Формат изображения не поддерживается.";
break;   
}
//удаляем изображение из памяти.
imagedestroy($thumbImage);
}
}