<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-flat" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a></div>
      <h1 class="panel-title"><i class="fa fa-truck fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-flat" class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-descr"><?php echo $entry_descr; ?>:</label>
          <div class="col-sm-10">
            <input type="text" name="flat_descr" value="<?php echo $flat_descr; ?>" placeholder="<?php echo $entry_descr; ?>" id="input-descr" class="form-control" />
          </div>
        </div>
		<div class="form-group">
          <label class="col-sm-2 control-label" for="input-cost"><?php echo $entry_cost; ?>:</label>
          <div class="col-sm-10">
            <input type="text" name="flat_cost" value="<?php echo $flat_cost; ?>" placeholder="<?php echo $entry_cost; ?>" id="input-cost" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?>:</label>
          <div class="col-sm-10">
            <select name="flat_geo_zone_id" id="input-geo-zone" class="form-control">
              <option value="0"<?php if($flat_geo_zone_id == 0) echo ' selected="selected"'; ?>><?php echo $text_all_zones; ?></option>
              <option value="<?php echo $geo_zones['zone_id']; ?>"<?php if ($geo_zones['zone_id'] == $flat_geo_zone_id)  echo ' selected="selected"'; ?>><?php echo $geo_zones['name']; ?></option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?>:</label>
          <div class="col-sm-10">
            <select name="flat_status" id="input-status" class="form-control">
              <?php if ($flat_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?>:</label>
          <div class="col-sm-10">
            <input type="text" name="flat_sort_order" value="<?php echo $flat_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 