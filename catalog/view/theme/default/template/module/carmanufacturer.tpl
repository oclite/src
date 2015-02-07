<?php if($box) { ?>
<div class="box">
	<div class="box-heading">
		<div style="float: left; margin-right: 8px;"><img src="catalog/view/theme/default/image/factory16.png" alt="" /></div>
	<?php echo $heading_title; ?>
	</div>
	<div class="box-content">
<?php } ?>
		<div id="manufcarousel" class="flexslider">		
			<ul class="slides">
		    <?php foreach ($carmanufacturers as $carmanufacturer) { ?>
		    	<li><a href="<?php echo $carmanufacturer['href']; ?>"><img src="<?php echo $carmanufacturer['thumb']; ?>" alt="<?php echo $carmanufacturer['name']; ?>" title="<?php echo $carmanufacturer['name']; ?>" /></a></li>
		    <?php } ?>
		    </ul>
		</div>
<?php if($box) { ?>
	</div>
</div>
<?php } ?>
<script type="text/javascript">
<!--
$(window).load(function() {
$('#manufcarousel').flexslider({
	animation: 'slide',
	itemWidth: 130,
	itemMargin: 100,
	minItems: 2,
    maxItems: 4
});});
-->
</script>
