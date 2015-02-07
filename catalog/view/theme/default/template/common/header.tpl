<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge" /><![endif]-->
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<link rel="apple-touch-icon" type="image/png" href="<?php echo HTTP_SERVER.$logo; ?>" />
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="<?php echo $base; ?>catalog/view/javascript/jquery/jquery-2.0.3.min.js" type="text/javascript"></script>
<link href="<?php echo $base; ?>catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="<?php echo $base; ?>catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $base; ?>catalog/view/theme/default/js/linescript.js" type="text/javascript"></script>
<link href="<?php echo $base; ?>catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="<?php echo $base; ?>catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<meta name='yandex-verification' content='6655a7b029c00db6' />
<script src="<?php echo $base; ?>catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $base . $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php echo $custom_header; ?>
</head>
<body class="<?php echo $class; ?>">
<header>
  <nav id="top">
  <div class="container">
    <div class="pull-left"></div>
    <div id="top-links" class="pull-right"><a href="tel:<?php echo $telephone; ?>" class="headertel"><i class="fa fa-phone"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></a> <a href="<?php echo $account; ?>"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span></a> <a href="<?php echo $wishlist; ?>" id="wishlist-total"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a> <a href="<?php echo $shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a> <a href="<?php echo $checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></div>
  </div>
</nav>
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
      </div>
      <div class="col-sm-5"><?php echo $search; ?>
      </div>
      <div class="col-sm-3">
	  <?php echo $cart; ?>
	  </div>
    </div>
  </div>
<?php if ($categories) { ?>
  <nav id="menu" class="navbar">
  <div class="container">
    <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav">
      	<li><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a></li>
      	<li><a href="<?php echo $news; ?>"><?php echo $text_news; ?></a></li>
		<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Товары  <b class="caret"></b></a>
		<div class="dropdown-menu">
		<div class="dropdown-inner">
<?php 
	foreach ($categories as $category) {
		echo '<ul class="topdrmenu">';
		echo '<li class="module-title">'.$category['name']."</li>\n";
		if ($category['children']){
			foreach ($category['children'] as $child) {
				echo '<li><a href="' . $child['href'] . '" ' . (($child['category_id'] == $child_id)? 'class="active"' : '') . '>'.$child['name']."</a></li>\n";
			}
		}
		echo "</ul>\n";
	}
?>
		</div>
	</div>
	</li>
        <?php foreach ($informations as $information) { ?>
        <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      </ul>
    </div>
      </div>
  </nav>
<?php } ?>
</header>
