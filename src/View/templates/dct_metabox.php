<?php
foreach ($meta_items as $meta_key => $meta_value) {
  if ($meta_key === '_wp_page_template' || strpos($meta_key, '_edit') !== FALSE) {
    continue;
  }
  ?>
    <div style="width: 45%;margin-left: 1%; float: left">
        <input type="text" id="<?php echo $meta_key ?>"
               name="page_meta[<?php echo $meta_key ?>]"
               value="<?php echo $meta_value[0] ?>">
        <label for="<?php echo $meta_key ?>"><?php echo $meta_key ?></label>
    </div>
<?php } ?>
<div style="clear: both"></div>
