<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($added == 'true') {
    echo '<div class="container-fluid voffset-1">
        <div class="fix-cart alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        Product item has been added to the cart! <a href="' . WEB_HOST . '/orders.php" class="fancybox fancybox.iframe"><strong>Click here</strong></a> to view items.
        </div>
        </div>';
} else if ($updated == 'true') {
    echo '<div class="container-fluid voffset-1">
        <div class="fix-cart alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        Cart items has been updated! <a href="' . WEB_HOST . '/orders.php" class="fancybox fancybox.iframe" onclick="$(\'alert\').close()"><strong>Click here</strong></a> to view items.
        </div>
        </div>';
}
?>
<script>
    $(".fix-cart").fadeTo(8000, 500).slideUp(500, function () {
        $(".fix-cart").alert('close');
    });
</script>