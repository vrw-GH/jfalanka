<?php
// include_once 'site_config.php';
if (!isset($_SESSION)) {
   session_start();
}

include_once "../" . $website['emailer_php'];

include_once "../" . $website['captcha_php'];
$PHPCAP = new Captcha(CAPTCHA_LEN);

// include_once '../models/dbConfig.php';
// $myCon = new dbConfig();
$myCon->connect();

function is_valid_email($email)
{
   if (preg_match("/[.+a-zA-Z0-9_-]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $email) > 0) {
      return true;
   } else {
      return false;
   }
}

function sendContactUsEmail($message, $fname, $lname, $email, $phone, $subject)
{

   /* message (to use single quotes in the 'message' variable, supercede them with a back slash like this-->&nbsp; \' */
   $body = '
                <!doctype html>
                 <html>
                 <head>
                 <meta charset="utf-8">
                     <title>Customer request</title>
                 </head>
                 <body>
                     <div style="border:none;
                         margin:5px auto;
                         background:#fff;
                         padding:20px 10px 20px 10px;
                         width:100%;
                         max-width:600px;
                         height:auto;
                         overflow:hidden;
                         font-size:13px;
                         color:#000;
                         text-align:left;
                         line-height:24px;">
                         <br>
                         <br/>
                                     Request : ' . $subject . '<br/>
                                     Sender Name : ' . $fname . ' ' . $lname . '<br/>
                                     Sender Email : ' . $email . '<br/>
                                     Sender Phone : ' . $phone . '<br/><br/>
                         ' . $message . '
                         <br/><br/>
                     </div>
                 </body>
                 </html>
                ';

   /* To send HTML mail, the Content-type header must be set */
   $headers = 'MIME-Version: 1.0' . "\r\n";
   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

   /* Additional headers */
   /* $headers .= 'To: test <test@example.com>, test2 <test2@example.com>' . "\r\n"; */
   $headers .= "From: \"" . EMAIL_FROM . "\"\n"; // $headers .= 'From:' . $from . "\r\n";
   /* $headers .= 'Cc: birthdayarchive@example.com' . "\r\n"; */
   /* $headers .= 'Bcc: admin@yahoo.com' . "\r\n"; */

   /* Send the email */
   // mail($to, $subject, $message, $headers);
   if (!ISSMTP) {  //default True
      if (!mail(EMAIL_TO, $subject, $body, $headers)) {
         cLog(json_encode(error_get_last()));
         return false;
      }
      return true;
   } else {
      return sendSMTP($message, $subject, $fname, $lname, $email, $phone, EMAIL_TO, EMAIL_FROM, $body);
   }
}

?>


<div class="container">
   <div class="row">
      <div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="contactLabel" aria-hidden="true">
         <div class="modal-dialog">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="panel-title" id="contactLabel"><span class="glyphicons glyphicons-message-plus"></span>&nbsp; If you would like more information?
                     Feel free to contact us.</h4>
               </div>
               <div class="row">
                  <div class="col-xs-12 voffset-1 voffset-b-1">
                     <div class="container-fluid">
                        <?php
                        if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['quick-form']) &&
                           $_POST['quick-form'] == 'products'
                        ) {
                           if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                              if (
                                 trim($_POST['firstname']) == null || trim($_POST['lastname']) == null ||
                                 trim($_POST['phone']) == null || trim($_POST['email']) == null ||
                                 trim($_POST['subject']) == null || trim($_POST['captchabox']) == null
                              ) {
                                 echo ('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Please Enter Required Details</div>');
                              } else if (!is_valid_email($_POST['email'])) {
                                 echo ('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Please Enter a Valied E-mail</div>');
                                 // } else if ($_POST['captchabox'] != $captcha_code) {
                              } else if (!$PHPCAP->verify($_POST["captchabox"], 1)) {
                                 echo ('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Please Enter Correct Image value</div>');
                              } else {
                                 if (sendContactUsEmail($_POST['comment'], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['phone'], $_POST['subject'])) {
                                    unset($_POST['firstname']);
                                    unset($_POST['lastname']);
                                    unset($_POST['email']);
                                    unset($_POST['phone']);
                                    unset($_POST['comment']);
                                    unset($_POST['subject']);
                                    echo ('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Thank you for contacting us.</strong> We have received your enquiry and will respond to you shortly.</div>');
                                 } else {
                                    echo ('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Sorry, Message not sent</div>');
                                 }
                              }
                           }
                        }
                        ?>
                     </div>
                  </div>
               </div>
               <form id="prd_form_id" action="#" method="post" accept-charset="utf-8" class="text-left">
                  <input type="hidden" name="quick-form" value="products" />
                  <div class="modal-body">
                     <div class="fetched-data"></div>
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 voffset-2">
                           <label><span class="text-danger">*</span> First Name</label>
                           <input class="form-control" name="firstname" placeholder="Firstname" type="text" required autofocus value="<?php
                                                                                                                                       if (isset($_POST['firstname'])) {
                                                                                                                                          echo $_POST['firstname'];
                                                                                                                                       }
                                                                                                                                       ?>" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 voffset-2">
                           <label><span class="text-danger">*</span> Last Name</label>
                           <input class="form-control" name="lastname" placeholder="Lastname" type="text" required value="<?php
                                                                                                                           if (isset($_POST['lastname'])) {
                                                                                                                              echo $_POST['lastname'];
                                                                                                                           }
                                                                                                                           ?>" />
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 voffset-2">
                           <label><span class="text-danger">*</span> Phone No</label>
                           <input class="form-control" name="phone" placeholder="Phone" type="text" required value="<?php
                                                                                                                     if (isset($_POST['phone'])) {
                                                                                                                        echo $_POST['phone'];
                                                                                                                     }
                                                                                                                     ?>" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 voffset-2">
                           <label><span class="text-danger">*</span> Your Email</label>
                           <input class="form-control" name="email" placeholder="E-mail" type="text" required value="<?php
                                                                                                                     if (isset($_POST['email'])) {
                                                                                                                        echo $_POST['email'];
                                                                                                                     }
                                                                                                                     ?>" />
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 voffset-2">
                           <label><span class="text-danger">*</span> Subject</label>
                           <input class="form-control" id="subject" name="subject" placeholder="Subject" type="text" required value="<?php
                                                                                                                                       if (isset($_POST['subject'])) {
                                                                                                                                          echo $_POST['subject'];
                                                                                                                                       }
                                                                                                                                       ?>" />
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 voffset-2">
                           <label>Message</label>
                           <textarea class="form-control" placeholder="Message..." rows="5" name="comment"><?php
                                                                                                            if (isset($_POST['comment'])) {
                                                                                                               echo $_POST['comment'];
                                                                                                            }
                                                                                                            ?></textarea>
                        </div>
                     </div>
                     <div class="row">
                        <div class="form-group col-xs-12 voffset-2">
                           <p class="text-warning">Just making sure you're not a robot</p>
                        </div>
                        <div class="form-group col-xs-12">
                           <div class="form-group col-xs-6 voffset-1 col-xxs-full-width">
                              <!-- <img src="<?= $captcha_img ?>" alt="CAPTCHA1" class="img-responsive col-xxs-center-img" /> -->
                              <?php
                              $PHPCAP->prime(1);
                              $PHPCAP->draw(1);
                              ?>

                           </div>
                           <div class="form-group col-xs-6 col-xxs-full-width">
                              <label><span class="text-danger">*</span> Type the Image Text :</label><input name="captchabox" type="text" size="20" maxlength="10" id="captchabox" value="" class="form-control" required>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="panel-footer voffset-1 text-right">
                     <button type="submit" class="btn btn-info square" value="Send">Send <span class="glyphicon glyphicon-send"></span></button>
                     <!--<span class="glyphicon glyphicon-remove"></span>-->
                     <button type="button" class="btn btn-danger btn-close btn-xs square offset-l-2" data-dismiss="modal">Close</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   <!-- row END -->
   <!-- Rentals row Start -->
   <div class="row">
      <div class="modal fade" id="contact-rentals" tabindex="-1" role="dialog" aria-labelledby="contactLabel" aria-hidden="true">
         <div class="modal-dialog">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="panel-title" id="contactLabel"><span class="glyphicons glyphicons-message-plus"></span>&nbsp; Make a service request.</h4>
               </div>
               <div class="row">
                  <div class="col-xs-12 voffset-1 voffset-b-1">
                     <div class="container-fluid">
                        <?php
                        if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['quick-form']) &&
                           $_POST['quick-form'] == 'rentals'
                        ) {
                           if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                              if (
                                 trim($_POST['firstname']) == null || trim($_POST['lastname']) == null ||
                                 trim($_POST['phone']) == null || trim($_POST['email']) == null ||
                                 trim($_POST['subject']) == null || trim($_POST['captchabox']) == null ||
                                 trim($_POST['cat_name']) == null
                              ) {
                                 echo ('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Please Enter Required Details</div>');
                              } else if (!is_valid_email($_POST['email'])) {
                                 echo ('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Please Enter a Valied E-mail</div>');
                                 // } else if ($_POST['captchabox'] != $captcha_code) {
                              } else if (!$PHPCAP->verify($_POST["captchabox"], 2)) {
                                 echo ('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Please Enter Correct Image value</div>');
                              } else {
                                 $comment = 'Requested item/service(s) : ' . $_POST['cat_name'] . '<br/><br/>' . $_POST['comment'];
                                 if (sendContactUsEmail($comment, $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['phone'], $_POST['subject'])) {
                                    unset($_POST['firstname']);
                                    unset($_POST['lastname']);
                                    unset($_POST['email']);
                                    unset($_POST['phone']);
                                    unset($_POST['comment']);
                                    unset($_POST['subject']);
                                    unset($_POST['cat_name']);
                                    echo ('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Thank you for contacting us.</strong> We have received your enquiry and will respond to you shortly.</div>');
                                 } else {
                                    echo ('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>Sorry, Message not sent</div>');
                                 }
                              }
                           }
                        }
                        ?>
                     </div>
                  </div>
               </div>
               <form id="rent_form_id" action="" method="post" accept-charset="utf-8" class="text-left form">
                  <input type="hidden" name="quick-form" value="rentals" />
                  <div class="modal-body">
                     <div class="fetched-data"></div>
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 voffset-2">
                           <label><span class="text-danger">*</span> First Name</label>
                           <input class="form-control" name="firstname" placeholder="Firstname" type="text" required autofocus value="<?php
                                                                                                                                       if (isset($_POST['firstname'])) {
                                                                                                                                          echo $_POST['firstname'];
                                                                                                                                       }
                                                                                                                                       ?>" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 voffset-2">
                           <label><span class="text-danger">*</span> Last Name</label>
                           <input class="form-control" name="lastname" placeholder="Lastname" type="text" required value="<?php
                                                                                                                           if (isset($_POST['lastname'])) {
                                                                                                                              echo $_POST['lastname'];
                                                                                                                           }
                                                                                                                           ?>" />
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 voffset-2">
                           <label><span class="text-danger">*</span> Phone No</label>
                           <input class="form-control" name="phone" placeholder="Phone" type="text" required value="<?php
                                                                                                                     if (isset($_POST['phone'])) {
                                                                                                                        echo $_POST['phone'];
                                                                                                                     }
                                                                                                                     ?>" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 voffset-2">
                           <label><span class="text-danger">*</span> Your Email</label>
                           <input class="form-control" name="email" placeholder="E-mail" type="text" required value="<?php
                                                                                                                     if (isset($_POST['email'])) {
                                                                                                                        echo $_POST['email'];
                                                                                                                     }
                                                                                                                     ?>" />
                        </div>
                     </div>
                     <div class="row">
                        <div class="form-group col-xs-12 voffset-2">
                           <label><span class="text-danger">*</span> Services details</label>
                           <span class="help-block">You can select multiple options for your service</span>
                           <?php
                           $query = "SELECT * FROM item_main_category WHERE active ='1' ORDER BY cat_name ASC";
                           $result = $myCon->query($query);
                           ?>
                           <select name="cat_name" class="form-control selectpicker" multiple show-menu-arrow required data-size="5">
                              <option disabled>Please Select one or more option(s)</option>
                              <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                 <option value="<?php echo ($row['cat_name']); ?>">
                                    <?php echo ($row['cat_name']); ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 voffset-2">
                           <label><span class="text-danger">*</span> Subject</label>
                           <input class="form-control" id="rent_subject" name="subject" placeholder="Subject" type="text" required value="<?php
                                                                                                                                          if (isset($_POST['subject'])) {
                                                                                                                                             echo $_POST['subject'];
                                                                                                                                          }
                                                                                                                                          ?>" />
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 voffset-2">
                           <label>Message</label>
                           <textarea class="form-control" placeholder="Message..." rows="5" name="comment"><?php
                                                                                                            if (isset($_POST['comment'])) {
                                                                                                               echo $_POST['comment'];
                                                                                                            }
                                                                                                            ?></textarea>
                        </div>
                     </div>
                     <div class="row">
                        <div class="form-group col-xs-12 voffset-2">
                           <p class="text-warning">Just making sure you're not a robot</p>
                        </div>
                        <div class="form-group col-xs-12">
                           <div class="form-group col-xs-6 voffset-1 col-xxs-full-width">
                              <!-- <img src="<?= $captcha_img ?>" alt="CAPTCHA2" class="img-responsive col-xxs-center-img" /> -->
                              <?php
                              $PHPCAP->prime(2);
                              $PHPCAP->draw(2);
                              ?>

                           </div>
                           <div class="form-group col-xs-6 col-xxs-full-width">
                              <label><span class="text-danger">*</span> Type the Image Text :</label><input name="captchabox" type="text" size="20" maxlength="10" id="captchabox" value="" class="form-control" required>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="panel-footer voffset-1 text-right">
                     <button type="submit" class="btn btn-info square" value="Send">Send <span class="glyphicon glyphicon-send"></span></button>
                     <!--<span class="glyphicon glyphicon-remove"></span>-->
                     <button type="button" class="btn btn-danger btn-close btn-xs square offset-l-2" data-dismiss="modal">Close</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   <!-- row END -->
</div>

<script>
   $(document).ready(function() {
      $(".data_inq").click(function() {
         $("#subject").val($(this).data('id'));
         $('#contact').modal({
            modal: true,
            backdrop: 'static',
            keyboard: false
         });
      });
      $(".data_rent").click(function() {
         $("#rent_subject").val($(this).data('id'));
         $('#contact-rentals').modal({
            modal: true,
            backdrop: 'static',
            keyboard: false
         });
      });
      <?php if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['quick-form']) && $_POST['quick-form'] == 'products') { ?>
         $('#contact').modal({
            modal: true,
            backdrop: 'static',
            keyboard: false
         });
      <?php } else if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['quick-form']) && $_POST['quick-form'] == 'rentals') { ?>
         $('#contact-rentals').modal({
            modal: true,
            backdrop: 'static',
            keyboard: false
         });
      <?php } ?>
   });
</script>

<?php
// $myCon->closeCon();
?>

<?php cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . ' loaded.'); ?>