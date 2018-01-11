<?php
foreach ($meta_items as $meta_key => $meta_value) {
  if ($meta_key === '_wp_page_template' || strpos($meta_key, '_edit') !== FALSE) {
    continue;
  }
  ?>
    <div style="width: 45%;margin-left: 1%; float: left">
        <input type="text" id="<?php echo esc_attr($meta_key) ?>"
               name="page_meta[<?php echo esc_attr($meta_key) ?>]"
               value="<?php echo esc_attr($meta_value[0]) ?>">
        <label for="<?php echo esc_attr($meta_key) ?>"><?php echo esc_attr($meta_key) ?></label>
    </div>
<?php } ?>
<div style="clear: both"></div>
