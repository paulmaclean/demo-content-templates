<div class="wrap">
    <h1><?php _e('Demo Template Settings', 'demo-content-templates') ?></h1>
    <p><?php _e('Convert your page contents to templates, or make a new page from an existing template', 'demo-content-templates') ?></p>
    <div class=" pure-g">
        <div class="pure-u-1-2">
            <div class="admin-box">
                <h3 class="title"><?php _e('Pages', 'demo-content-templates') ?></h3>
                <div class="toggle-group">
                    <input id="toggle-pages" type="checkbox" onClick="toggle(this ,'pages')"/><label for="toggle-pages"><?php _e('Toggle All', 'demo-content-templates') ?></label>
                </div>
                <form method="post" class="pure-form pages"  action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="<?php echo $form_actions['dct_convert'] ?>"/>
                    <input type="hidden" name="convert_from" value="page"/>
                    <input type="hidden" name="convert_to" value="dct_template"/>
                    <div class="body scrollable">
                      <?php wp_nonce_field('dct_verify'); ?>
                      <?php echo '<ul>' . $page_checkboxes . '</ul>'; ?>
                    </div>
                    <div class="footer">
                        <input type="checkbox" name="flatten_hierarchy" id="flatten-page-hierarchy">
                        <label for="flatten-page-hierarchy"><?php _e('Flatten Page Hierarchy', 'demo-content-templates') ?></label>
                        <input type="submit" value="Create Template(s)" class="button-primary"/>
                    </div>
                </form>
            </div>
        </div>
        <div class="pure-u-1-2">
            <div class="admin-box">
                <h3 class="title"><?php _e('Templates', 'demo-content-templates') ?></h3>
                <div class="toggle-group">
                    <input id="toggle-templates" type="checkbox" onClick="toggle(this, 'templates')" /><label for="toggle-templates"><?php _e('Toggle All', 'demo-content-templates') ?></label>
                </div>
                <form method="post" class="templates" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="<?php echo $form_actions['dct_convert'] ?>"/>
                    <input type="hidden" name="convert_from" value="dct_template"/>
                    <input type="hidden" name="convert_to" value="page"/>
                    <div class="body scrollable">
                        <?php wp_nonce_field('dct_verify'); ?>
                        <?php echo '<ul>' . $content_template_checkboxes . '</ul>'; ?>
                    </div>
                    <div class="footer">
                        <input type="checkbox" name="flatten_hierarchy" id="flatten-template-hierarchy">
                        <label for="flatten-template-hierarchy"><?php _e('Flatten Template Hierarchy', 'demo-content-templates') ?></label>
                        <input type="submit" value="Create Page(s)" class="button-primary"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function toggle(source, formClass) {
        var inputs = document.querySelectorAll("." + formClass + " .body input[type='checkbox']");
        for(var i = 0; i < inputs.length; i++) {
            inputs[i].checked = source.checked;
        }
    }
</script>