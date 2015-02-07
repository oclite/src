<?php
if ($position === 'column_left' || $position === 'content_right') { ?>
<div class="module-inner">
	<h3 class="module-title"><span><?php echo $heading_title; ?></span></h3>
	<b class="click"></b>
	<div class="module-ct">
<ul id="accordion" class="list" >
<?php
foreach ($categories as $category) { ?>
<li class="level0 VmClose<?php if($category['category_id'] == $category_id) echo ' active'; if ($category['children']) echo ' parent'; ?>"><a href="<?php echo ($category['children'])? "javascript:return false;" : $category['href']; ?>"><?php echo $category['name']; ?></a>
  <?php if ($category['children']) { ?>
  <span class="VmArrowdown"><i class="plus">+</i><i class="minus">-</i></span>
	<ul class="level1">
  <?php foreach ($category['children'] as $child) { ?>
  <?php if ($child['category_id'] == $child_id) { ?>
  <li class="level1 VmClose active"><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
  <?php } else { ?>
  <li class="level1 VmClose"><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
<?php
			}
		} ?>
	</ul>
	<?php } ?>
	</li>
<? } ?>
</ul>
	</div>
</div>
<?php } elseif($position === 'content_top' || $position === 'content_bottom') { ?>

<?php }