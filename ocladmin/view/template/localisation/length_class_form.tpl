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
        <button type="submit" form="form-length-class" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a></div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="form-length-class">
        <div class="form-group required">
          <label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
          <div class="col-sm-10">
            <div class="input-group">
              <input type="text" name="title" value="<?php echo isset($title) ? $title : ''; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
            </div>
            <?php if (isset($error_title)) { ?>
            <div class="text-danger"><?php echo $error_title; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label"><?php echo $entry_unit; ?></label>
          <div class="col-sm-10">
            <div class="input-group">
              <input type="text" name="unit" value="<?php echo isset($unit) ? $unit : ''; ?>" placeholder="<?php echo $entry_unit; ?>" class="form-control" />
            </div>
            <?php if (isset($error_unit)) { ?>
            <div class="text-danger"><?php echo $error_unit; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-value"><?php echo $entry_value; ?></label>
          <div class="col-sm-10">
            <input type="text" name="value" value="<?php echo $value; ?>" placeholder="<?php echo $entry_value; ?>" id="input-value" class="form-control" />
            <span class="help-block"><?php echo $help_value; ?></span></div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>