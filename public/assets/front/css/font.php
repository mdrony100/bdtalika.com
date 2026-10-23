<?php
header("Content-type: text/css; charset: UTF-8");
if(isset($_GET['font_familly']))
{
  $font_familly = $_GET['font_familly'];
}
else {
  $font_familly = '"Open sans", sans-serif';
}
?>

body,
h1,
h2,
h3,
h4,
h5,
h6,
.form-label, .btn,
.explore-content h1,
.explore-content h2,
.lead-i,
.nav-menu > li > a,
.nav-dropdown > li > a,
h1.italian-header-capt,
.content_block_2 .content-box .btn-box .download-btn span,
.bl-continue,
.st-author-info .st-author-subtitle,
.pricing-header .pr-subtitle,
.d-navigation ul li a,
.cmn--btn {
  font-family: <?php echo $font_familly?>;
}

@media screen and (max-width: 375px) {
    .change-language {
        font-family: <?php echo $font_familly?>;
    }
}