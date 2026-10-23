<?php
header("Content-type: text/css; charset: UTF-8");
if(isset($_GET['color']))
{
  $color = '#'.$_GET['color'];
}
else {
  $color = '#FF9900';
}
?>

a.text-danger:focus, a.text-danger:hover {
    color: <?php echo $color?> !important;
}

.theme-bg{
	background: <?php echo $color?> !important;
}

html body .bg-red {
  background-color: <?php echo $color?>;
}

.theme-cl,
ul.icons_lists.colors li:before,
.text-danger,
html body .text-primary,
html body .text-themecolor,
.lists-4.color li:before, .lists-3.color li:before, .lists-2.color li:before, .lists-1.color li:before,
.pagination>.active>a,
.pagination>.active>a:focus,
.pagination>.active>a:hover,
.pagination>.active>span,
.pagination>.active>span:focus,
.pagination>.active>span:hover,
.pagination>li>a:focus,
.pagination>li>a:hover{
	color: <?php echo $color?> !important;
}

.theme-btn-light,
.page-item.active .page-link {
	color: <?php echo $color?>;
    border:1px solid <?php echo $color?>;
}

.theme-btn-light:hover, .btn-light:focus {
    background: <?php echo $color?>;
    border: 1px solid <?php echo $color?>;
}

.radio-custom:checked + .radio-custom-label:before {
    background: <?php echo $color?>;
    border-color: <?php echo $color?>;
}

[type="radio"]:checked + label:after,
[type="radio"]:not(:checked) + label:after {
    background: <?php echo $color?>;
}

.nav-menu>.active>a,
.nav-menu>.focus>a,
.nav-menu>li:hover>a {
    color:<?php echo $color?> !important;
}

.nav-menu>.active>a .submenu-indicator-chevron,
.nav-menu>.focus>a .submenu-indicator-chevron,
.nav-menu>li:hover>a .submenu-indicator-chevron {
    border-color: transparent <?php echo $color?> <?php echo $color?> transparent
}

.nav-search-button:hover .nav-search-icon {
    color: <?php echo $color?>;
}

.nav-button {
    background-color: <?php echo $color?>;
}

.nav-dropdown>li>a:hover, .nav-dropdown>li>a:focus {
	color:<?php echo $color?>;
}

.nav-dropdown>.focus>a,
.nav-dropdown>li:hover>a {
    color:<?php echo $color?>;
}

.nav-dropdown>.focus>a .submenu-indicator-chevron,
.nav-dropdown>li:hover>a .submenu-indicator-chevron {
    border-color:transparent <?php echo $color?> <?php echo $color?> transparent;
}

.nav-menu.nav-menu-social>li.add-listing,
.Uploadphoto{
    background:<?php echo $color?>;
}

.header-transparent.dark-text .nav-menu>li>a:hover, .header-transparent.dark-text .nav-menu>li>a:focus {
    color:<?php echo $color?>;
}

.Rego-top-cates ul li a:hover .Rego-tp-ico, .Rego-top-cates ul li a:focus .Rego-tp-ico, .Rego-top-cates ul li a:active .Rego-tp-ico{
    color:<?php echo $color?>;
}

.Rego-bnr-cats ul li a:hover, .Rego-bnr-cats ul li a:focus, .Rego-bnr-cats ul li a:active,
.uli-list-features .list-uiyt .list-iobk,
.Rego-cates a,
.Rego-facilities-wrap ul li,
.vrt-list-features ul li a:hover, .vrt-list-features ul li a:focus,.vrt-list-features ul li a:active,
.Rego-catg-icon,
.Rego-cat-arrow,
.Rego-author-lists,
.Rego-author-links .Rego-social.colored li a,
.Rego-price-body ul li i,
.Rego-all-drp .Rego-single-drp.small .btn-group>.btn.active,
.reviews-reaction a.comment-love.active,
.prt_body ul li:before,
.btn.choose_package,
.btn.choose_package:hover, .btn.choose_package:focus,
.dashboard-nav ul li.active a, .dashboard-nav ul li:hover a,
a.close-list-item,
.abs-list-sec .add-list-btn,
.daterangepicker td.available:hover, .daterangepicker th.available:hover,
.blg_grid_caption .blg_tag,
.single_article_pagination .article_pagination_center_grid a,
.social-links li a:hover,
.social-links li a:focus {
	color:<?php echo $color?>;
}

.Rego-btn-book:hover, .Rego-btn-book:focus, .Rego-btn-book:active,
.vrt-list-amenties ul li .vrt-amens.no:before,
.Rego-catg-wrap:hover .Rego-catg-icon, .Rego-catg-wrap:focus .Rego-catg-icon, .Rego-catg-wrap:active .Rego-catg-icon,
.Rego-author-links .Rego-social li a:hover, .Rego-author-links .Rego-social li a:focus, .Rego-author-links .Rego-social li a:active,
.filt-count {
    background:<?php echo $color?>;
}


.d14ixh{
	color: <?php echo $color?> !important;
}

.Rego-img-location-wrap:hover .Rego-cat-arrow, .Rego-img-location-wrap:focus .Rego-cat-arrow, .Rego-img-location-wrap:active .Rego-cat-arrow {
    background: <?php echo $color?>;
    border: 1px dashed <?php echo $color?>;
}

.Rego-price-btn:hover, .Rego-price-btn:focus, .Rego-price-btn:active, .Rego-price-btn.active{
	background:<?php echo $color?>;
	border:2px dashed <?php echo $color?>;
}

.Rego-all-drp .dropdown-toggle.active:after {
    border-color: transparent <?php echo $color?> <?php echo $color?>transparent;
}
.btn.choose_package.active{
	background:<?php echo $color?>;
	border-color:<?php echo $color?>;
}

.nav-pills .nav-link.active, .show>.nav-pills .nav-link,
.daterangepicker td.active, .daterangepicker td.active:hover {
    background-color:<?php echo $color?>;
}
.dashboard-nav ul li.active, .dashboard-nav ul li:hover{
    border-color:<?php echo $color?>;
}
