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
    include_once ('../models/dbConfig.php');
    include_once ('models/memberClass.php');
    include_once ('../models/encryption.php');
    include_once ('../models/emailTemplates.php');

    /* ------------------ Database INSERT -------------------- */
    if ($_POST['action'] == 'insert') {
        if (trim($_POST['mem_fname']) == null || trim($_POST['mem_lname']) == null || trim($_POST['mem_mobile']) == null || trim($_POST['mem_email']) == null || trim($_POST['mem_add_line1']) == null || trim($_POST['mem_country']) == null || trim($_POST['user_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button> Please enter required feilds</div>');
        } else {
            $memObj = new memberClass();
            $ecryptObj = new encryption();
            $mailObj = new emailTemplates();
            $dbObj = new dbConfig();
            $dbObj->connect();
            try {
                $insert_user_name = $_POST['insert_user_name'];
                $mem_fname = $dbObj->escapeString($_POST['mem_fname']);
                $mem_lname = $dbObj->escapeString($_POST['mem_lname']);
                $mem_gender = $_POST['mem_gender'];
                $mem_dob = $_POST['mem_dob'];
                $mem_phone = $_POST['mem_phone'];
                $mem_mobile = $_POST['mem_mobile'];
                $mem_email = $dbObj->escapeString($_POST['mem_email']);
                $mem_add_line1 = $dbObj->escapeString($_POST['mem_add_line1']);
                $mem_add_line2 = $dbObj->escapeString($_POST['mem_add_line2']);
                $mem_country = $dbObj->escapeString($_POST['mem_country']);
                $mem_city = ''; //$dbObj->escapeString($_POST['mem_city']);
                $mem_state = ''; //$dbObj->escapeString($_POST['mem_state']);
                $mem_pst_code = $_POST['mem_pst_code'];
                $mem_type_id = $_POST['mem_type_id'];

                $user_email = $mem_email;
                $user_name = $dbObj->escapeString($_POST['user_name']);
                $user_pword = $memObj->randomPassword();
                $verified_once = $ecryptObj->encode('yes');
                $active = $ecryptObj->encode('yes');

                $dbObj->closeCon();

                $memObj->setMem_id($user_name);
                $memObj->setMem_fname($mem_fname);
                $memObj->setMem_lname($mem_lname);
                $memObj->setMem_gender($mem_gender);
                $memObj->setMem_dob($mem_dob);
                $memObj->setMem_phone($mem_phone);
                $memObj->setMem_mobile($mem_mobile);
                $memObj->setMem_email($mem_email);
                $memObj->setMem_add_line1($mem_add_line1);
                $memObj->setMem_add_line2($mem_add_line2);
                $memObj->setMem_city($mem_city);
                $memObj->setMem_country($mem_country);
                $memObj->setMem_state($mem_state);
                $memObj->setMem_pst_code($mem_pst_code);
                $memObj->setMem_type_id($mem_type_id);


                $memObj->setUser_email($user_email);
                $memObj->setgetUser_id($user_name);
                $memObj->setUser_pword($ecryptObj->encode($user_pword));
                $memObj->setVerified_once($verified_once);
                $memObj->setActive($active);

                $memObj->insertMember();
                /* Send Welcome Email */
                $mailObj->sendWelcomeEmail($user_name, $user_pword, $user_email);

                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>New staff member has been Added!<br/>Registration email has been sent!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        /* ------------------ Database UPDATE -------------------- */
    } else if ($_POST['action'] == 'update') {
        if (trim($_POST['mem_id']) == null || trim($_POST['mem_type_id']) == null || trim($_POST['active']) == null) {
            //printing error message
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Please enter required feilds</div>');
            ?>
            <script>
                $(document).ready(function () {
                    //need small delay
                    setTimeout("editMember('<?php echo($_POST['mem_id']); ?>')", 500);
                });
            </script>
            <?php
        } else {
            $ecryptObj = new encryption();
            $memObj = new memberClass();
            $dbObj = new dbConfig();
            $dbObj->connect();
            try {
                $mem_id = $dbObj->escapeString($_POST['mem_id']);
                $mem_type_id = $_POST['mem_type_id'];
                $active = $ecryptObj->encode($_POST['active']);

                $dbObj->closeCon();

                $memObj->setMem_id($mem_id);
                $memObj->setgetUser_id($mem_id);
                $memObj->setMem_type_id($mem_type_id);
                $memObj->setActive($active);

                $memObj->updateMember();

                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Member has been Updated!</div>');
                ?>
                <script>
                    $(document).ready(function () {
                        //need small delay
                        setTimeout("editsupp('<?php echo($_POST['mem_code']); ?>')", 500);
                    });
                </script>
                <?php
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  ' . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
                ?>
                <script>
                    $(document).ready(function () {
                        //need small delay
                        setTimeout("editMember('<?php echo($_POST['mem_id']); ?>')", 500);
                    });
                </script>
                <?php
            }
        }
        //Database Update part End
    } else if ($_POST['action'] == 'delete') {
        $memObj = new memberClass();
        try {
            $mem_id = $_POST['mem_id'];

            $memObj->setMem_id($mem_id);
            $memObj->setgetUser_id($mem_id);
            $memObj->deleteMember();

            echo('<div class="alert alert-success" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>Member ID  "' . $mem_id . '" has been Deleted!</div>');
        } catch (Exception $ex) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
        }
        //Database Delete part End
    } else if ($_POST['action'] == 'change') {
        if (trim($_POST['mem_fname']) == null || trim($_POST['mem_lname']) == null ||
                trim($_POST['mem_title']) == null || trim($_POST['mem_add_line1']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button> Please enter required feilds</div>');
        } else {
            $memObj = new memberClass();
            $dbObj = new dbConfig();
            $dbObj->connect();
            try {
                $mem_fname = $dbObj->escapeString($_POST['mem_fname']);
                $mem_lname = $dbObj->escapeString($_POST['mem_lname']);
                $mem_title = $_POST['mem_title'];
                $mem_phone = $_POST['mem_phone'];
                $mem_fax = $_POST['mem_fax'];
                $mem_email = $dbObj->escapeString($_POST['user_email']);
                $mem_add_line1 = $dbObj->escapeString($_POST['mem_add_line1']);
                $mem_add_line2 = $dbObj->escapeString($_POST['mem_add_line2']);
                $mem_city = ''; // $dbObj->escapeString($_POST['mem_city']);
                $mem_state = ''; //$dbObj->escapeString($_POST['mem_state']);
                $mem_pst_code = $_POST['mem_pst_code'];

                $user_email = $mem_email;
                $user_name = $dbObj->escapeString($_POST['user_name']);
                $dbObj->closeCon();

                $memObj->setMem_id($user_name);
                $memObj->setUser_id($user_name);
                $memObj->setMem_fname($mem_fname);
                $memObj->setMem_lname($mem_lname);
                $memObj->setMem_title($mem_title);
                $memObj->setMem_phone($mem_phone);
                $memObj->setMem_fax($mem_fax);
                $memObj->setMem_email($mem_email);
                $memObj->setMem_add_line1($mem_add_line1);
                $memObj->setMem_add_line2($mem_add_line2);
                $memObj->setMem_city($mem_city);
                $memObj->setMem_state($mem_state);
                $memObj->setMem_pst_code($mem_pst_code);

                $memObj->updateMemberSave();

                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Your details has been updated!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        //Database Update part Start
    } else if ($_POST['action'] == 'password') {
        if (trim($_POST['old_user_pword']) == null || trim($_POST['user_pword']) == null || trim($_POST['user_pword2']) == null || trim($_POST['user_email']) == null || trim($_POST['user_name']) == null) {
            echo('<div class="alert alert-danger" role="alert">  '
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span></button> Please enter required feilds</div>');
        } else {
            $memObj = new memberClass();
            $ecryptObj = new encryption();
            $dbObj = new dbConfig();
            $dbObj->connect();
            try {
                $user_email = $dbObj->escapeString($_POST['user_email']);
                $user_name = $dbObj->escapeString($_POST['user_name']);
                $old_user_pword = $_POST['old_user_pword'];
                $user_pword = $_POST['user_pword'];

                $dbObj->closeCon();

                $memObj->setUser_email($user_email);
                $memObj->setUser_id($user_name);
                $memObj->setUser_pword($ecryptObj->encode($user_pword));
                $memObj->setOld_user_pword($ecryptObj->encode($old_user_pword));

                $memObj->changePassword();

                echo('<div class="alert alert-success" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>Your password has been changed!</div>');
            } catch (Exception $ex) {
                echo('<div class="alert alert-danger" role="alert">  '
                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                . '<span aria-hidden="true">&times;</span></button>' . $ex->getMessage() . '</div>');
            }
        }
        //Database Update part Start
    }
}
/* end of post -------------------------------------------------------------------- */
?>