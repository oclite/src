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
        <button type="submit" form="form-information" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a></div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-information" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
          <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
              <div class="tab-pane" id="descr">
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="title" value="<?php echo isset($title) ? $title : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
                    <?php if (isset($error_title)) { ?>
                    <div class="text-danger"><?php echo $error_title; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="description" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control"><?php echo isset($description) ? $description : ''; ?></textarea>
                    <?php if (isset($error_description)) { ?>
                    <div class="text-danger"><?php echo $error_description; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-meta-title"><?php echo $entry_meta_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="meta_title" value="<?php echo isset($meta_title) ? $meta_title : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title" class="form-control" />
                    <?php if (isset($error_meta_title)) { ?>
                    <div class="text-danger"><?php echo $error_meta_title; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-meta-description"><?php echo $entry_meta_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="meta_description" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description" class="form-control"><?php echo isset($meta_description) ? $meta_description : ''; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-meta-keyword"><?php echo $entry_meta_keyword; ?></label>
                  <div class="col-sm-10">
                    <textarea name="meta_keyword" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword" class="form-control"><?php echo isset($meta_keyword) ? $meta_keyword : ''; ?></textarea>
                  </div>
                </div>
              </div>
          </div>
          <div class="tab-pane" id="tab-data">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-seourl"><?php echo $entry_seourl; ?></label>
              <div class="col-sm-10">
                <input type="text" name="seourl" value="<?php echo $seourl; ?>" id="input-seourl" class="form-control" />
                <span class="help-block"><?php echo $help_seourl; ?></span> </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-positions"><?php echo $entry_positions; ?></label>
              <div class="col-sm-10">
              	<select name="positions" id="input-positions" class="form-control">
                  <option value="0"<?php if ($positions == 0) echo ' selected="selected"';  ?>><?php echo $text_positions_1; ?></option>
                  <option value="1"<?php if ($positions == 1) echo ' selected="selected"';  ?>><?php echo $text_positions_2; ?></option>
                  <option value="2"<?php if ($positions == 2) echo ' selected="selected"';  ?>><?php echo $text_positions_3; ?></option>
                  <option value="3"<?php if ($positions == 3) echo ' selected="selected"';  ?>><?php echo $text_positions_4; ?></option>
                </select>
                <span class="help-block"><?php echo $help_positions; ?></span></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="col-sm-10">
                <select name="status" id="input-status" class="form-control">
                  <?php if ($status) { ?>
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
              <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
              <div class="col-sm-10">
                <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-design">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <tbody>
                  <tr>
                    <td class="text-left"><?php echo $entry_layout; ?></td>
                    <td class="text-left">
                    <select name="information_layout" class="form-control">
                        <option value=""></option>
                        <?php foreach ($layouts as $layout) { ?>
                        <?php if (isset($information_layout) && $information_layout == $layout['layout_id']) { ?>
                        <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
CKEDITOR.replace('input-description');
//--></script> 
<?php echo $footer; ?>