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
        <button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a></div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
          <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
              <div class="tab-pane" id="general">
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="category_description[name]" value="<?php echo isset($category_description['name']) ? $category_description['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                    <?php if (isset($error_name)) { ?>
                    <div class="text-danger"><?php echo $error_name; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="category_description[description]" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control"><?php echo isset($category_description['description']) ? $category_description['description'] : ''; ?></textarea>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-meta-title"><?php echo $entry_meta_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="category_description[meta_title]" value="<?php echo isset($category_description['meta_title']) ? $category_description['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title" class="form-control" />
                    <?php if (isset($error_meta_title)) { ?>
                    <div class="text-danger"><?php echo $error_meta_title; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-meta-description"><?php echo $entry_meta_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="category_description[meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description" class="form-control"><?php echo isset($category_description['meta_description']) ? $category_description['meta_description'] : ''; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-meta-keyword"><?php echo $entry_meta_keyword; ?></label>
                  <div class="col-sm-10">
                    <textarea name="category_description[meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword" class="form-control"><?php echo isset($category_description['meta_keyword']) ? $category_description['meta_keyword'] : ''; ?></textarea>
                  </div>
                </div>
              </div>
          </div>
          <div class="tab-pane" id="tab-data">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
              <div class="col-sm-10">
                <input type="text" name="path" value="<?php echo $path; ?>" placeholder="<?php echo $entry_parent; ?>" id="input-parent" class="form-control" />
                <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-filter"><?php echo $entry_filter; ?></label>
              <div class="col-sm-10">
                <input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" class="form-control" />
                <span class="help-block"><?php echo $help_filter; ?></span>
                <div id="category-filter" class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($category_filters as $category_filter) { ?>
                  <div id="category-filter<?php echo $category_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category_filter['name']; ?>
                    <input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-keyword"><?php echo $entry_keyword; ?></label>
              <div class="col-sm-10">
                <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                <span class="help-block"><?php echo $help_keyword; ?></span></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
              <div class="col-sm-10">
                <?php if ($thumb) { ?>
                <a href="" id="thumb-image" class="img-thumbnail img-edit"><img src="<?php echo $thumb; ?>" alt="" title="" /></a>
                <?php } else { ?>
                <a href="" id="thumb-image" class="img-thumbnail img-edit"><i class="fa fa-camera fa-5x"></i></a>
                <?php } ?>
                <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-top"><?php echo $entry_top; ?></label>
              <div class="col-sm-10">
                <label class="checkbox">
                  <?php if ($top) { ?>
                  <input type="checkbox" name="top" value="1" checked="checked" id="input-top" />
                  <?php } else { ?>
                  <input type="checkbox" name="top" value="1" id="input-top" />
                  <?php } ?>
                </label>
                <span class="help-block"><?php echo $help_top; ?></span></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-column"><?php echo $entry_column; ?></label>
              <div class="col-sm-10">
                <input type="text" name="column" value="<?php echo $column; ?>" placeholder="<?php echo $entry_column; ?>" id="input-column" class="form-control" />
                <span class="help-block"><?php echo $help_column; ?></span></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
              <div class="col-sm-10">
                <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
              </div>
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
          </div>
          <div class="tab-pane" id="tab-design">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <tbody>
                  <tr>
                    <td class="text-left"><?php echo $entry_layout; ?></td>
                    <td class="text-left"><select name="category_layout" class="form-control">
                        <option value=""></option>
                        <?php foreach ($layouts as $layout) { ?>
                        <?php if (isset($category_layout) && $category_layout == $layout['layout_id']) { ?>
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
<script type="text/javascript"><!--
$('input[name=\'path\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				json.unshift({
					'category_id': 0,
					'name': '<?php echo $text_none; ?>'
				});
				
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'path\']').val(item['label']);
		$('input[name=\'parent_id\']').val(item['value']);
	}	
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'filter\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['filter_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter\']').val('');
		
		$('#category-filter' + item['value']).remove();
		
		$('#category-filter').append('<div id="category-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="category_filter[]" value="' + item['value'] + '" /></div>');
	}	
});

$('#category-filter').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script>
<?php echo $footer; ?>