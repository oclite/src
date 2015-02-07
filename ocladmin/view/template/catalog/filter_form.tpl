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
        <button type="submit" form="form-filter" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a></div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-filter" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label"><?php echo $entry_group; ?></label>
          <div class="col-sm-10">
            <div class="input-group">
              <input type="text" name="filter_group_name" value="<?php echo isset($filter_group_name) ? $filter_group_name : ''; ?>" placeholder="<?php echo $entry_group; ?>" class="form-control" />
            </div>
            <?php if (isset($error_name)) { ?>
            <div class="text-danger"><?php echo $error_name; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
          <div class="col-sm-10">
            <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
          </div>
        </div>
        <table id="filter" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left required"><?php echo $entry_name ?></td>
              <td class="text-right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php $filter_row = 0; ?>
            <?php foreach ($filters as $filter) { ?>
            <tr id="filter-row<?php echo $filter_row; ?>">
              <td class="text-left"><input type="hidden" name="filter[<?php echo $filter_row; ?>][filter_id]" value="<?php echo $filter['filter_id']; ?>" />
                <div class="input-group">
                  <input type="text" name="filter[<?php echo $filter_row; ?>][name]" value="<?php echo isset($filter['name']) ? $filter['name'] : ''; ?>" placeholder="<?php echo $entry_name ?>" class="form-control" />
                </div>
                <?php if (isset($error_filter[$filter_row])) { ?>
                <div class="text-danger"><?php echo $error_filter[$filter_row]; ?></div>
                <?php } ?></td>
              <td class="text-right"><input type="text" name="filter[<?php echo $filter_row; ?>][sort_order]" value="<?php echo $filter['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" /></td>
              <td class="text-left"><button type="button" onclick="$('#filter-row<?php echo $filter_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button></td>
            </tr>
            <?php $filter_row++; ?>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="text-left"><a onclick="addFilterRow();" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_filter_add; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var filter_row = <?php echo $filter_row; ?>;

function addFilterRow() {
	html  = '<tr id="filter-row' + filter_row + '">';
    html += '  <td class="text-left"><input type="hidden" name="filter[' + filter_row + '][filter_id]" value="" />';
	html += '  <div class="input-group">';
	html += '    <input type="text" name="filter[' + filter_row + '][name]" value="" placeholder="<?php echo $entry_name ?>" class="form-control" />';
    html += '  </div>';
	html += '  </td>';
	html += '  <td class="text-right"><input type="text" name="filter[' + filter_row + '][sort_order]" value="" value="" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#filter-row' + filter_row + '\').remove();" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button></td>';
	html += '</tr>';
	
	$('#filter tbody').append(html);
	
	filter_row++;
}
//--></script> 
<?php echo $footer; ?> 