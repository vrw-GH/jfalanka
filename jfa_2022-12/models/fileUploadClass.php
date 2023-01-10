<?php

class fileUploadClass {

    private $uploaddir;
    private $filename;

    public function getUploaddir() {
        return $this->uploaddir;
    }

    public function setUploaddir($uploaddir) {
        $this->uploaddir = $uploaddir;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function setFilename($filename) {
        $this->filename = $filename;
    }

    function checkWidthHeight($filestemp, $width_m, $height_m, $param, $condition) {
        /*
         * param can be : 0(width only), 1(height only), 2(both)
         * condition can be : 0(excatly), 1(minimum), 2(maximum)
         */
        $filesize = filesize($filestemp);
        if ($filesize > 8000 * 1024) {
            throw new Exception('You have exceeded the size limit! (file cannot exceed 8 Mb).');
        }

        list($width, $height, $type, $attr) = getimagesize($filestemp);
        if ($param == 0) {
            if ($condition == 0) {
                if ($width != $width_m) {
                    throw new Exception('Image Width should be Equel to ' . $width_m);
                }
            } else if ($condition == 1) {
                if ($width < $width_m) {
                    throw new Exception('Image Minimum Width should be ' . $width_m);
                }
            } else {
                if ($width > $width_m) {
                    throw new Exception('Image Maximum Width should be ' . $width_m);
                }
            }
        } else if ($param == 1) {
            if ($condition == 0) {
                if ($height != $height_m) {
                    throw new Exception('Image Height should be Equel to ' . $height_m);
                }
            } else if ($condition == 1) {
                if ($height < $height_m) {
                    throw new Exception('Image Minimum Height should be ' . $height_m);
                }
            } else {
                if ($height > $height_m) {
                    throw new Exception('Image Maximum Height should be ' . $height_m);
                }
            }
        } else {
            if ($condition == 0) {
                if ($width != $width_m || $height != $height_m) {
                    throw new Exception('Image Width should be Equel to ' . $width_m . ' and Height should be Equel to ' . $height_m);
                }
            } else if ($condition == 1) {
                if ($width < $width_m || $height < $height_m) {
                    throw new Exception('Image Minimum Width should be ' . $width_m . ' and Height should be ' . $height_m);
                }
            } else {
                if ($width > $width_m || $height > $height_m) {
                    throw new Exception('Image Maximum Width should be ' . $width_m . ' and Height should be' . $height_m);
                }
            }
        }
        return true;
    }

    function generateRandomString() {
        $dataetag = date("YmdHis");
        $length = 30;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        $randomString .=$dataetag;
        return $randomString;
    }

    public function singleFileUpload($files, $filestemp, $uploaddir) {
        $filename = basename($files);
        $filesize = filesize($filestemp);
        $extension = strtolower(pathinfo($files, PATHINFO_EXTENSION));

        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif") && ($extension != "pdf") && ($extension != "doc") && ($extension != "docx") && ($extension != "xls") && ($extension != "xlsx")) {
            throw new Exception('Unknown file extension (Allowed file extensions:  .pdf, .doc*, .xls*, .jpg, .png, .gif).');
        } else if ($filesize > 8000 * 1024) {
            throw new Exception('You have exceeded the size limit! (file cannot exceed 8 Mb).');
        } else {
            //generate name as random String with date tag
            $randstring = $this->generateRandomString();
            $uploadfile = $uploaddir . $randstring . '.' . $extension;

            $this->setFilename($randstring . '.' . $extension);
            $this->setUploaddir($uploadfile);

            if (move_uploaded_file($filestemp, $uploadfile)) {
                //echo "File is valid, and was successfully uploaded.";
            } else {
                throw new Exception('File uploading failed. Please try again later');
            }
        }
        return true;
    }

    public function singleImageUpload($files, $filestemp, $uploaddir, $with_limit, $hight_limit) {
        $filesize = filesize($filestemp);
        $extension = strtolower(pathinfo($files, PATHINFO_EXTENSION));

        /* get image Width and Height in px */
        list($width, $height, $type, $attr) = getimagesize($filestemp);

        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
            throw new Exception('Unknown file extension (Allowed file extensions: .jpg, .png, .gif).');
        }
        if ($filesize > 8000 * 1024) {
            throw new Exception('You have exceeded the size limit! (file cannot exceed 8 Mb).');
        } else if ($with_limit != 'any' && $width != $with_limit) {
            throw new Exception('Image width should be ' . $with_limit . 'px');
        } else if ($hight_limit != 'any' && $height != $hight_limit) {
            throw new Exception('Image height should be ' . $hight_limit . 'px');
        } else {
            //generate name as random String with date tag
            $randstring = $this->generateRandomString();
            $uploadfile = $uploaddir . $randstring . '.' . $extension;

            $this->setFilename($randstring . '.' . $extension);
            $this->setUploaddir($uploadfile);

            if (move_uploaded_file($filestemp, $uploadfile)) {
                //echo "File is valid, and was successfully uploaded.";
            } else {
                throw new Exception('File uploading failed. Please try again later');
            }
        }
        return true;
    }

    public function imageCropped($files, $filestemp, $uploaddir, $crop_width, $crop_height, $thumb_true, $thumb_width, $thumb_height, $watermark_true, $larg_image_crop = true) {
        $filesize = filesize($filestemp);
        $extension = strtolower(pathinfo($files, PATHINFO_EXTENSION));
        /* get image Width and Height in px */
        list($width, $height, $type, $attr) = getimagesize($filestemp);

        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
            throw new Exception('Unknown file extension (Allowed file extensions: .jpg, .png, .gif).');
        }

        if ($filesize > 8000 * 1024) {
            throw new Exception('You have exceeded the size limit! (file cannot exceed 8 Mb).');
        } else if ($larg_image_crop == true && $width < $crop_width) {
            throw new Exception('Sorry your original image was too small. Image width should be at least ' . $crop_width . 'px');
        } else if ($larg_image_crop == true && $height < $crop_height) {
            throw new Exception('Sorry your original image was too small. Image Height should be at least ' . $crop_height . 'px');
        } else if ($larg_image_crop == false && $width < $thumb_width) {
            throw new Exception('Sorry your original image was too small. Image width should be at least ' . $thumb_width . 'px');
        } else if ($larg_image_crop == true && $height < $thumb_height) {
            throw new Exception('Sorry your original image was too small. Image Height should be at least ' . $thumb_height . 'px');
        } else {
            /* generate name as random String with date tag */
            $randstring = $this->generateRandomString();
            $uploadfile = $uploaddir . $randstring . '.' . $extension;
            $thumbpath = $uploaddir . '/thumbs/' . $randstring . '.' . $extension;

            $this->setFilename($randstring . '.' . $extension);
            $this->setUploaddir($uploadfile);

            if ($extension == "jpg" || $extension == "jpeg") {
                $src = imagecreatefromjpeg($filestemp);
            } else if ($extension == "png") {
                $src = imagecreatefrompng($filestemp);
            } else {
                $src = imagecreatefromgif($filestemp);
            }
        }

        $original_aspect = $width / $height;
        $crop_aspect = $crop_width / $crop_height;
        if ($thumb_true == true) {
            $crop_thumb_aspect = $thumb_width / $thumb_height;
        }

        if ($larg_image_crop == true) {
            /* large image */
            if ($original_aspect >= $crop_aspect) {
                /* If image is wider than cropp size (in aspect ratio sense) */
                $new_height = $crop_height;
                $new_width = $width / ($height / $crop_height);
            } else {
                /* if image is higher than cropp size  */
                $new_width = $crop_width;
                $new_height = $height / ($width / $crop_width);
            }
            /* thumb image */
            if ($thumb_true == true) {
                if ($original_aspect >= $crop_thumb_aspect) {
                    $new_height_thumb = $thumb_height;
                    $new_width_thumb = $width / ($height / $thumb_height);
                } else {
                    $new_width_thumb = $thumb_width;
                    $new_height_thumb = $height / ($width / $thumb_width);
                }
            }
            /* large image will not crop */
        } else {
            $crop_width = $width;
            $crop_height = $height;
            $new_height = $height;
            $new_width = $width;
            /* thumb image */
            if ($thumb_true == true) {
                if ($original_aspect >= $crop_thumb_aspect) {
                    $new_height_thumb = $thumb_height;
                    $new_width_thumb = $width / ($height / $thumb_height);
                } else {

                    $new_width_thumb = $thumb_width;
                    $new_height_thumb = $height / ($width / $thumb_width);
                }
            }
        }

        $cropped = imagecreatetruecolor($crop_width, $crop_height);
        if ($thumb_true == true) {
            $thumbed = imagecreatetruecolor($thumb_width, $thumb_height);
        }

        if ($watermark_true == true) {
            /* creating png image of watermark */
            $watermark = imagecreatefrompng('../resources/images/watermark.png');

            /* getting dimensions of watermark image */
            $watermark_width = imagesx($watermark);
            $watermark_height = imagesy($watermark);

            /* placing the watermark on center */
            $dest_x = (($width) / 2) - ($watermark_width / 2);
            $dest_y = (($height) / 2) - ($watermark_height / 2);

            /* blending the images together */
            imagealphablending($src, true);
            imagealphablending($watermark, true);

            /* creating the new image */
            imagecopy($src, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);
        }
        /* Resize and crop */
        imagecopyresampled($cropped, $src, 0 - ($new_width - $crop_width) / 2, // Center the image horizontally
                0 - ($new_height - $crop_height) / 2, // Center the image vertically
                0, 0, $new_width, $new_height, $width, $height);
        if ($thumb_true == true) {
            imagecopyresampled($thumbed, $src, 0 - ($new_width_thumb - $thumb_width) / 2, // Center the image horizontally
                    0 - ($new_height_thumb - $thumb_height) / 2, // Center the image vertically
                    0, 0, $new_width_thumb, $new_height_thumb, $width, $height);
        }
        if ($thumb_true == true) {
            if (imagejpeg($cropped, $this->getUploaddir(), 85) && imagejpeg($thumbed, $thumbpath, 85)) {
                imagedestroy($thumbed);
            } else {
                throw new Exception('File uploading failed. Please try again later');
            }
        } else {
            if (imagejpeg($cropped, $this->getUploaddir(), 85)) {
                
            } else {
                throw new Exception('File uploading failed. Please try again later');
            }
        }
        imagedestroy($src);
        imagedestroy($cropped);
    }

    public function singleImageWithThumb($files, $filestemp, $uploaddir, $with_limit, $hight_limit, $thumb_width) {
        $filesize = filesize($filestemp);
        $extension = strtolower(pathinfo($files, PATHINFO_EXTENSION));

        /* get image Width and Height in px */
        list($width, $height, $type, $attr) = getimagesize($filestemp);

        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
            throw new Exception('Unknown file extension (Allowed file extensions: .jpg, .png, .gif).');
        }
        if ($filesize > 8000 * 1024) {
            throw new Exception('You have exceeded the size limit! (file cannot exceed 8 Mb).');
        } else if ($with_limit != 'any' && $width != $with_limit) {
            throw new Exception('Image width should be ' . $with_limit . 'px');
        } else if ($hight_limit != 'any' && $height != $hight_limit) {
            throw new Exception('Image height should be ' . $hight_limit . 'px');
        } else {
            //generate name as random String with date tag
            $randstring = $this->generateRandomString();
            $uploadfile = $uploaddir . $randstring . '.' . $extension;
            $thumbpath = $uploaddir . '/thumbs/' . $randstring . '.' . $extension;

            $this->setFilename($randstring . '.' . $extension);
            $this->setUploaddir($uploadfile);

            if ($extension == "jpg" || $extension == "jpeg") {
                $src = imagecreatefromjpeg($filestemp);
            } else if ($extension == "png") {
                $src = imagecreatefrompng($filestemp);
            } else {
                $src = imagecreatefromgif($filestemp);
            }

            //original image size
            $newheight = $height;
            $newwidth = $width;
            $tmp = imagecreatetruecolor($newwidth, $newheight);

            //Thumb image size
            $newwidth1 = $thumb_width;
            $newheight1 = ($height / $width) * $newwidth1;
            $tmp1 = imagecreatetruecolor($newwidth1, $newheight1);

            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);

            if (imagejpeg($tmp, $this->getUploaddir(), 85) && imagejpeg($tmp1, $thumbpath, 85)) {
                //echo "File is valid, and was successfully uploaded.";
            } else {
                throw new Exception('File uploading failed. Please try again later');
            }
            imagedestroy($src);
            imagedestroy($tmp);
            imagedestroy($tmp1);
        }
        return true;
    }

    public function singleImageResizedWithThumb($files, $filestemp, $uploaddir, $resize_width, $resize_height, $thumb_width, $thumb_height) {
        $filesize = filesize($filestemp);
        $extension = strtolower(pathinfo($files, PATHINFO_EXTENSION));

        /* get image Width and Height in px */
        list($width, $height, $type, $attr) = getimagesize($filestemp);

        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
            throw new Exception('Unknown file extension (Allowed file extensions: .jpg, .png, .gif).');
        }
        if ($filesize > 8000 * 1024) {
            throw new Exception('You have exceeded the size limit! (file cannot exceed 8 Mb).');
        } else {
            /* generate name as random String with date tag */
            $randstring = $this->generateRandomString();
            $uploadfile = $uploaddir . $randstring . '.' . $extension;
            $thumbpath = $uploaddir . '/thumbs/' . $randstring . '.' . $extension;

            $this->setFilename($randstring . '.' . $extension);
            $this->setUploaddir($uploadfile);

            if ($extension == "jpg" || $extension == "jpeg") {
                $src = imagecreatefromjpeg($filestemp);
            } else if ($extension == "png") {
                $src = imagecreatefrompng($filestemp);
            } else {
                $src = imagecreatefromgif($filestemp);
            }


            if ($resize_width > 0 && $width > $resize_width) {
                $newwidth = $resize_width;
                $newheight = ($height / $width) * $newwidth;
                $tmp = imagecreatetruecolor($newwidth, $newheight);
            } else if ($resize_height > 0 && $height > $resize_height) {
                $newheight = $resize_height;
                $newwidth = ($width / $height) * $newheight;
                $tmp = imagecreatetruecolor($newwidth, $newheight);
            } else {
                $newwidth = $width;
                $newheight = $height;
                $tmp = imagecreatetruecolor($newwidth, $newheight);
            }

            /* Thumb image size */
            if ($thumb_width > 0) {
                $newwidth1 = $thumb_width;
                $newheight1 = ($height / $width) * $newwidth1;
                $tmp1 = imagecreatetruecolor($newwidth1, $newheight1);
            } else {
                $newheight1 = $thumb_height;
                $newwidth1 = ($width / $height) * $newheight1;
                $tmp1 = imagecreatetruecolor($newwidth1, $newheight1);
            }

            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);


            if (imagejpeg($tmp, $this->getUploaddir(), 85) && imagejpeg($tmp1, $thumbpath, 85)) {
                //echo "File is valid, and was successfully uploaded.";
            } else {
                throw new Exception('File uploading failed. Please try again later');
            }
            imagedestroy($src);
            imagedestroy($tmp);
            imagedestroy($tmp1);
        }
        return true;
    }

}

?>