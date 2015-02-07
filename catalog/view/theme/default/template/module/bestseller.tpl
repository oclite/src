<?php if(count($products) > 0) { 
	if($position === 'content_top' || $position === 'content_bottom') {
?>
<h3 class="bestseller"><?php echo $heading_title; ?></h3>
<div class="row product-layout">
  <?php foreach ($products as $product) { ?>
  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb transition">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <p><?php echo $product['description']; ?></p>
        <?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if($product['qpbox'] > 1) { ?>
    		<ul>
    			<li><?php echo $text_in_pack . ' ' . $product['qpbox']; ?></li>
					<li><?php echo $text_unit_price . ' <b>' . $product['unit_price'] . '</b>'; ?></li>
				</ul>
				<?php } ?>
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if ($product['special'] <= 0) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php } ?>
        </p>
        <?php } ?>
      </div>
      <div class="button-group">
        <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
<?php }
	elseif ($position === 'column_left' || $position === 'content_right') { ?>
<div class="module-inner">
	<h3 class="module-title"><span><?php echo $heading_title; ?></span></h3>
	<b class="click"></b>
	<div class="module-ct">
	<ul class="listbest"> 
	<?php foreach ($products as $product) { ?>
	<li class="item">
	<div class="product-box spacer"> 
	<div class="browseImage">
	<div class="new"> </div>
	<a href="<?php echo $product['href']; ?>" class="img2"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="browseProductImage featuredProductImage" border="0" /></a>
	</div>
		<div class="fleft">
			<div class="Title">
			<a class="bestshot"  href="<?php echo $product['href']; ?>" rel="<?php echo $product['thumb']; ?>">
			<?php echo $product['name']; ?></a>                        		
          </div>		 					                                         	 													
        <?php if ($product['rating']) { ?>
        <span class="vote">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </span>
        <?php } ?>
          <?php if ($product['price']) { ?>
                <div class="Price">
                  <?php if ($product['special'] <= 0) { ?>
				  <span class="PricesalesPrice"><?php echo $product['price']; ?></span>
                  <?php } else { ?>
                  <span class="sales"><?php echo $product['special']; ?></span> <span class="WithoutTax"><?php echo $product['price']; ?></span>
                  <?php } ?>
                </div>
          <?php } ?>
          <div class="wrapper">
			</div>    
		</div>
	</div>
	</li>
	<?php } ?>
	</ul>
	</div>
</div>
<?php	}
} ?>
