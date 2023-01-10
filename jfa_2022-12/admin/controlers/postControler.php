<?php

if (file_exists('admin_config.php')) {
    include_once 'admin_config.php';
} else if (file_exists('../admin_config.php')) {
    include_once '../admin_config.php';
} else {
    include_once '../../admin_config.php';
}

/* Check posting data ------------------------------------------------------------ */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'models/postClass.php';
    include_once 'models/urlSlugClass.php';
    include_once '../models/dbConfig.php';
    include_once '../models/fileUploadClass.php';
    include_once '../models/fileUploadDBClass.php';

    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'insert') {
        if (trim($_POST['post_name']) == null || trim($_POST['post_details']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $postObj = new postClass();
            $urlSlug = new urlSlugClass();
            $myCon = new dbConfig();
            $myCon->connect();

            $postObj->setActive($_POST['active']);
            $postObj->setAdd_by($_POST['user_name']);
            $postObj->setAdd_date(date('Y-m-d H:i:s'));
            /* App code */
            if ($website['config']['apps'] == true && $_POST['app_code'] != 0) {
                $postObj->setApp_code($_POST['app_code']);
            } else {
                $postObj->setApp_code(0);
            }
            /* Brand code */
            if ($website['config']['brands'] == true && $_POST['brand_code'] != 0) {
                $postObj->setBrand_code($_POST['brand_code']);
            } else {
                $postObj->setBrand_code(0);
            }
            /* District & city */
            if ($website['feature']['district'] == true) {
                $postObj->setD_id($_POST['d_id']);
            } else {
                $postObj->setD_id(0);
            }
            if ($website['feature']['city'] == true) {
                $postObj->setC_id($_POST['c_id']);
            } else {
                $postObj->setC_id(0);
            }

            $postObj->setFeatured($_POST['featured']);
            if ($website['descp']['post_data1_entry'] == true) {
                $postObj->setData1($myCon->escapeString($_POST['data1']));
            }
            if ($website['descp']['post_data2_entry'] == true) {
                $postObj->setData2($myCon->escapeString($_POST['data2']));
            }
            if ($website['descp']['post_data3_entry'] == true) {
                $postObj->setData3($myCon->escapeString($_POST['data3']));
            }

            /* Stock */
            if ($website['feature']['stock'] == true && $_POST['init_stock'] != null && $_POST['now_stock'] != null) {
                $postObj->setInit_stock($_POST['init_stock']);
                $postObj->setNow_stock($_POST['now_stock']);
            } else {
                $postObj->setInit_stock(0);
                $postObj->setNow_stock(0);
            }
            if ($website['descp']['post_policies_entry'] == true) {
                $postObj->setPolicies($myCon->escapeString($_POST['policies']));
            }

            $postObj->setPost_details($myCon->escapeString($_POST['post_details']));
            $postObj->setPost_name($myCon->escapeString($_POST['post_name']));
            $postObj->setPost_url_slug($urlSlug->urlSlug($postObj->getPost_name()));
            /* price */
            if ($website['feature']['cell_price']) {
                if ($_POST['selling_price'] != null) {
                    $postObj->setSelling_price($_POST['selling_price']);
                } else {
                    $postObj->setSelling_price(0);
                }
                if ($_POST['off_presentage'] != null) {
                    $postObj->setOff_presentage($_POST['off_presentage']);
                } else {
                    $postObj->setOff_presentage(0);
                }
                if ($_POST['off_value'] != null) {
                    $postObj->setOff_value($_POST['off_value']);
                } else {
                    $postObj->setOff_value(0);
                }
            } else {
                $postObj->setSelling_price(0);
                $postObj->setOff_presentage(0);
                $postObj->setOff_value(0);
            }

            /* terms */
            if ($website['descp']['post_terms_entry'] == true) {
                $postObj->setTerms($myCon->escapeString($_POST['terms']));
            }
            /* Unit */
            if ($website['config']['units'] == true && $_POST['unit_code'] != 0) {
                $postObj->setUnit_code($_POST['unit_code']);
            } else {
                $postObj->setUnit_code(0);
            }
            $myCon->closeCon();
            try {
                /* saving post --- */
                $postObj->postSave();
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Post Name <strong>' . $postObj->getPost_name() . '</strong> has been added!</div>');

                /* post category START --- */
                if (isset($_POST['sub_code']) && $_POST['sub_code'] != null) {
                    /* get selected category codes on sub categories */
                    $cat_code_array = array();
                    foreach ($_POST['sub_code'] as $md => $sc) {
                        if ($_POST['sub_code'][$md] != null) {
                            $cat_code_array[$md] = substr($_POST['sub_code'][$md], 0, 2);
                        }
                    }
                    /* loop selected sub categories */
                    foreach ($_POST['sub_code'] as $md => $sc) {
                        if ($_POST['sub_code'][$md] != null) {
                            $cat_code = substr($_POST['sub_code'][$md], 0, 2);
                            $sub_code = substr($_POST['sub_code'][$md], 2, 2);
                            /* identify only selectd catcodes */
                            foreach ($_POST['cat_code'] as $mc => $mcd) {
                                /* check user selected categories are in sub category list  */
                                if (in_array($_POST['cat_code'][$mc], $cat_code_array)) {
                                    $postObj->setCat_code($cat_code);
                                    $postObj->setSub_code($sub_code);
                                    $postObj->postSubCatSave();
                                } else {
                                    $postObj->setSub_code(0);
                                    $postObj->setCat_code($_POST['cat_code'][$mc]);
                                    $postObj->postSubCatSave();
                                }
                            }
                        }
                    }
                } else {
                    if (isset($_POST['cat_code']) && $_POST['cat_code'] != null) {
                        $postObj->setSub_code(0);
                        foreach ($_POST['cat_code'] as $md => $cd) {
                            if ($_POST['cat_code'][$md] != null) {
                                $postObj->setCat_code($_POST['cat_code'][$md]);
                                $postObj->postSubCatSave();
                            }
                        }
                    }
                }
                /* post category END --- */
                if (isset($_FILES['post_img']['name']) && $_FILES['post_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    /* Image crop options */
                    if ($_POST['post_img_crop'] == 'width') {
                        $fileObj->singleImageResizedWithThumb($_FILES['post_img']['name'], $_FILES['post_img']['tmp_name'], '../uploads/', $website['images']['post_width'], 0, $website['images']['post_small_width'], 0);
                    } else if ($_POST['post_img_crop'] == 'height') {
                        $fileObj->singleImageResizedWithThumb($_FILES['post_img']['name'], $_FILES['post_img']['tmp_name'], '../uploads/', 0, $website['images']['post_height'], 0, $website['images']['post_small_height']);
                    } else if ($_POST['post_img_crop'] == 'both') {
                        $fileObj->imageCropped($_FILES['post_img']['name'], $_FILES['post_img']['tmp_name'], '../uploads/', $website['images']['post_width'], $website['images']['post_height'], true, $website['images']['post_small_width'], $website['images']['post_small_height'], $website['images']['post_watermark']);
                    } else {
                        $fileObj->imageCropped($_FILES['post_img']['name'], $_FILES['post_img']['tmp_name'], '../uploads/', $website['images']['post_width'], $website['images']['post_height'], true, $website['images']['post_small_width'], $website['images']['post_small_height'], $website['images']['post_watermark'], $larg_image_crop = false);
                    }

                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref((int) $postObj->getPost_code());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(6);/** Post Image */
                    $fileDbObj->uploadFile();

                    echo('<div class="alert alert-success" role="alert">  '
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                    . '<span aria-hidden="true">&times;</span></button>New image has been added!</div>');
                }
                unset($_POST);
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Database UPDATE -------------------- */
    } else if ($_POST['action'] == 'update') {
        if (trim($_POST['post_code']) == null || trim($_POST['post_name']) == null ||
                trim($_POST['post_details']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $postObj = new postClass();
            $urlSlug = new urlSlugClass();
            $myCon = new dbConfig();
            $myCon->connect();

            $postObj->setPost_code($_POST['post_code']);
            $postObj->setActive($_POST['active']);
            $postObj->setUpd_by($_POST['user_name']);
            $postObj->setUpd_date(date('Y-m-d H:i:s'));
            /* App code */
            if ($website['config']['apps'] == true && $_POST['app_code'] != 0) {
                $postObj->setApp_code($_POST['app_code']);
            } else {
                $postObj->setApp_code(0);
            }
            /* Brand code */
            if ($website['config']['brands'] == true && $_POST['brand_code'] != 0) {
                $postObj->setBrand_code($_POST['brand_code']);
            } else {
                $postObj->setBrand_code(0);
            }
            /* District & city */
            if ($website['feature']['district'] == true) {
                $postObj->setD_id($_POST['d_id']);
            } else {
                $postObj->setD_id(0);
            }
            if ($website['feature']['city'] == true) {
                $postObj->setC_id($_POST['c_id']);
            } else {
                $postObj->setC_id(0);
            }

            $postObj->setFeatured($_POST['featured']);
            if ($website['descp']['post_data1_entry'] == true) {
                $postObj->setData1($myCon->escapeString($_POST['data1']));
            }
            if ($website['descp']['post_data2_entry'] == true) {
                $postObj->setData2($myCon->escapeString($_POST['data2']));
            }
            if ($website['descp']['post_data3_entry'] == true) {
                $postObj->setData3($myCon->escapeString($_POST['data3']));
            }

            /* Stock */
            if ($website['feature']['stock'] == true && $_POST['init_stock'] != null && $_POST['now_stock'] != null) {
                $postObj->setInit_stock($_POST['init_stock']);
                $postObj->setNow_stock($_POST['now_stock']);
            } else {
                $postObj->setInit_stock(0);
                $postObj->setNow_stock(0);
            }
            if ($website['descp']['post_policies_entry'] == true) {
                $postObj->setPolicies($myCon->escapeString($_POST['policies']));
            }

            $postObj->setPost_details($myCon->escapeString($_POST['post_details']));
            $postObj->setPost_name($myCon->escapeString($_POST['post_name']));
            $postObj->setPost_url_slug($urlSlug->urlSlug($postObj->getPost_name()));
            /* price */
            if ($website['feature']['cell_price']) {
                if ($_POST['selling_price'] != null) {
                    $postObj->setSelling_price($_POST['selling_price']);
                } else {
                    $postObj->setSelling_price(0);
                }
                if ($_POST['off_presentage'] != null) {
                    $postObj->setOff_presentage($_POST['off_presentage']);
                } else {
                    $postObj->setOff_presentage(0);
                }
                if ($_POST['off_value'] != null) {
                    $postObj->setOff_value($_POST['off_value']);
                } else {
                    $postObj->setOff_value(0);
                }
            } else {
                $postObj->setSelling_price(0);
                $postObj->setOff_presentage(0);
                $postObj->setOff_value(0);
            }

            /* terms */
            if ($website['descp']['post_terms_entry'] == true) {
                $postObj->setTerms($myCon->escapeString($_POST['terms']));
            }
            /* Unit */
            if ($website['config']['units'] == true) {
                $postObj->setUnit_code($_POST['unit_code']);
            } else {
                $postObj->setUnit_code(0);
            }
            $myCon->closeCon();
            try {
                /* saving post --- */
                $postObj->postUpdate();

                /* delete image on tick. this part should come first, because this may delete updated image as well */
                if (isset($_POST['post_img_delete']) && $_POST['post_img_delete'] == 'true') {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();

                    $fileDbObj->setUpload_id($_POST['upload_id']);
                    try {
                        $fileDbObj->removeFileAndRecordWithID();
                        echo('<div class="alert alert-success" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>Post image has been deleted!</div>');
                    } catch (Exception $ex) {
                        echo('<div class="alert alert-danger" role="alert">  '
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                    }
                }


                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Post Name <strong>' . $postObj->getPost_name() . '</strong> has been updated!</div>');
                if (isset($_FILES['post_img']['name']) && $_FILES['post_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    /* Image crop options */
                    if ($_POST['post_img_crop'] == 'width') {
                        $fileObj->singleImageResizedWithThumb($_FILES['post_img']['name'], $_FILES['post_img']['tmp_name'], '../uploads/', $website['images']['post_width'], 0, $website['images']['post_small_width'], 0);
                    } else if ($_POST['post_img_crop'] == 'height') {
                        $fileObj->singleImageResizedWithThumb($_FILES['post_img']['name'], $_FILES['post_img']['tmp_name'], '../uploads/', 0, $website['images']['post_height'], 0, $website['images']['post_small_height']);
                    } else if ($_POST['post_img_crop'] == 'both') {
                        $fileObj->imageCropped($_FILES['post_img']['name'], $_FILES['post_img']['tmp_name'], '../uploads/', $website['images']['post_width'], $website['images']['post_height'], true, $website['images']['post_small_width'], $website['images']['post_small_height'], $website['images']['post_watermark']);
                    } else {
                        $fileObj->imageCropped($_FILES['post_img']['name'], $_FILES['post_img']['tmp_name'], '../uploads/', $website['images']['post_width'], $website['images']['post_height'], true, $website['images']['post_small_width'], $website['images']['post_small_height'], $website['images']['post_watermark'], $larg_image_crop = false);
                    }

                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref((int) $postObj->getPost_code());
                    $fileDbObj->setFeatured('1');
                    $fileDbObj->setUpload_type_id(6);/** Post Image */
                    /* If file Uploaded */
                    if ($fileDbObj->checkFeatured()) {
                        /* Method should be called after the file upload */
                        $fileDbObj->updateFile();
                    } else {
                        $fileDbObj->uploadFile();
                    }

                    echo('<div class="alert alert-success" role="alert">  '
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                    . '<span aria-hidden="true">&times;</span></button>New image has been added!</div>');
                }
                unset($_POST);
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Database DELETE -------------------- */
    } else if ($_POST['action'] == 'delete') {
        if ($_POST['post_code'] == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $postObj = new postClass();
            $myCon = new dbConfig();
            $myCon->connect();

            $postObj->setPost_code($_POST['post_code']);

            $myCon->closeCon();
            try {
                $postObj->postDelete();
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Post has been deleted!</div>');

                unset($_POST);
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }

        /* ------------------ Display/Hide -------------------- */
    } else if ($_POST['action'] == 'active') {
        if ($_POST['post_code'] == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $postObj = new postClass();
            $myCon = new dbConfig();
            $myCon->connect();

            $postObj->setPost_code($_POST['post_code']);
            $postObj->setActive($_POST['active']);

            $myCon->closeCon();
            try {
                $postObj->postDisplay();
                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Post display status has been updated!</div>');

                unset($_POST);
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }

        /* ------------------ Post Gallery Image INSERT -------------------- */
    } else if ($_POST['action'] == 'add_image') {
        if (trim($_POST['post_code']) == null || !isset($_FILES['post_img']['name'])) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {

            $post_code = $_POST['post_code'];

            try {
                if (isset($_FILES['post_img']['name']) && $_FILES['post_img']['name'] != null) {
                    $fileObj = new fileUploadClass();
                    $fileDbObj = new fileUploadDBClass();
                    $fileObj->singleImageResizedWithThumb($_FILES['post_img']['name'], $_FILES['post_img']['tmp_name'], '../uploads/', $website['images']['post_gal_width'], $website['images']['post_gal_height'], $website['images']['post_thumb_width'], $website['images']['post_thumb_height']);
                    $fileDbObj->setUpload_path($fileObj->getFilename());
                    $fileDbObj->setUpload_ref((int) $post_code);
                    $fileDbObj->setUpload_type_id(6);/** Post Image */
                    $fileDbObj->uploadFile();

                    echo('<div class="alert alert-success" role="alert">  '
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                    . '<span aria-hidden="true">&times;</span></button>New image has been added!</div>');
                }
                unset($_POST);
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Post Gallery Image DELETE -------------------- */
    } else if ($_POST['action'] == 'delete_image') {
        if (trim($_POST['upload_id']) == null || trim($_POST['post_code']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
        } else {
            $fileDbObj = new fileUploadDBClass();

            $fileDbObj->setUpload_id($_POST['upload_id']);
            $post_code = $_POST['post_code'];
            try {
                $fileDbObj->removeImageAndRecordWithID();
                echo('<div class="alert alert-success" role="alert">  ' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button>Post image has been deleted!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
            unset($_POST);
        }
        /* ------------------ Change Category -------------------- */
    } else if ($_POST['action'] == 'edit_cat') {
        if (trim($_POST['post_code']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Post Id is missing</div>');
        } else {
            $postObj = new postClass();
            $myCon = new dbConfig();
            $myCon->connect();

            $postObj->setPost_code($_POST['post_code']);
            try {
                /* deleting all category data on selected post --- */
                $postObj->postSubCatdeleteAll();

                /* post category START --- */
                if (isset($_POST['sub_code']) && $_POST['sub_code'] != null) {
                    /* get selected category codes on sub categories */
                    $cat_code_array = array();
                    foreach ($_POST['sub_code'] as $md => $sc) {
                        if ($_POST['sub_code'][$md] != null) {
                            $cat_code_array[$md] = substr($_POST['sub_code'][$md], 0, 2);
                        }
                    }
                    /* loop selected sub categories */
                    foreach ($_POST['sub_code'] as $md => $sc) {
                        if ($_POST['sub_code'][$md] != null) {
                            $cat_code = substr($_POST['sub_code'][$md], 0, 2);
                            $sub_code = substr($_POST['sub_code'][$md], 2, 2);
                            /* identify only selectd catcodes */
                            foreach ($_POST['cat_code'] as $mc => $mcd) {
                                /* check user selected categories are in sub category list  */
                                if (in_array($_POST['cat_code'][$mc], $cat_code_array)) {
                                    $postObj->setCat_code($cat_code);
                                    $postObj->setSub_code($sub_code);
                                    $postObj->postSubCatSave();
                                } else {
                                    $postObj->setSub_code(0);
                                    $postObj->setCat_code($_POST['cat_code'][$mc]);
                                    $postObj->postSubCatSave();
                                }
                            }
                        }
                    }
                } else {
                    if (isset($_POST['cat_code']) && $_POST['cat_code'] != null) {
                        $postObj->setSub_code(0);
                        foreach ($_POST['cat_code'] as $md => $cd) {
                            if ($_POST['cat_code'][$md] != null) {
                                $postObj->setCat_code($_POST['cat_code'][$md]);
                                $postObj->postSubCatSave();
                            }
                        }
                    }
                }
                /* post category END --- */

                echo('<div class="alert alert-success" role="alert">  ' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button>Post category updated!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
    }
}
/* END posting data ------------------------------------------------------------ */
?>