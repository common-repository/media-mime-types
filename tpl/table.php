<div class="wrap">
    <?php screen_icon('options-general'); ?>
    <h2>
        <?php _e('MIME Types', 'mmt'); ?>
        <a class="add-new-h2"
           href="javascript:void(0);" onclick="addMimeType();">+ <?php _e('Add New MIME Type', 'mmt'); ?></a>
    </h2>
    <div id="poststuff">
        <form method="post" action="options.php">
            <?php settings_fields('mmt_mime_types'); ?>
            <table class="wp-list-table widefat" id="mmt" cellspacing="0">
                <thead>

                    <tr>
                        <th scope="col" class="manage-column">
                            <?php _e('Extension', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column">
                            <?php _e('MIME Type', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column">
                            <?php _e('Singular Label', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column">
                            <?php _e('Plural Label', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column">
                            <?php _e('Add Filter', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column">
                            <?php _e('Allow Upload', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column"></th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $allowed_mimes = get_allowed_mime_types();
                    $all_mimes = wp_get_mime_types();
                    $mmt_mime_types = get_option('mmt_mime_types');

                    ksort($all_mimes, SORT_STRING);
                    $i = 0;
                    ?>
                    <?php foreach ($all_mimes as $type => $mime): ?>
                        <?php if (isset($mmt_mime_types[$type])): ?>
                            <?php $allowed = isset($allowed_mimes[$type]); ?>
                            <?php $mmt_type = $mmt_mime_types[$type]; ?>
                            <tr>
                                <td style="vertical-align: middle;">
                                    <code><?php echo $type; ?></code>
                                    <input type="hidden"
                                           name="mmt_mime_types[<?php echo $i; ?>][type]"
                                           value="<?php echo $type; ?>"/>
                                </td>
                                <td style="vertical-align: middle;">
                                    <code><?php echo $mime; ?></code>
                                    <input type="hidden"
                                           name="mmt_mime_types[<?php echo $i; ?>][mime]"
                                           value="<?php echo $mime; ?>"/>
                                </td>
                                <td style="vertical-align: middle;">
                                    <input type="text"
                                           name="mmt_mime_types[<?php echo $i; ?>][singular]"
                                           value="<?php echo $mmt_type['singular']; ?>"/>
                                </td>
                                <td style="vertical-align: middle;">
                                    <input type="text"
                                           name="mmt_mime_types[<?php echo $i; ?>][plural]"
                                           value="<?php echo $mmt_type['plural']; ?>"/>
                                </td>
                                <td class="checkbox_td" style="vertical-align: middle;">
                                    <input type="checkbox"
                                           name="mmt_mime_types[<?php echo $i; ?>][filter]"
                                        <?php checked(1, $mmt_mime_types[$type]['filter']); ?>
                                           title="<?php _e('Add Filter', 'mmt'); ?>"
                                           value="1"/>
                                </td>
                                <td class="checkbox_td" style="vertical-align: middle;">
                                    <input type="checkbox"
                                           name="mmt_mime_types[<?php echo $i; ?>][allowed]"
                                        <?php checked(true, $allowed); ?>
                                           title="<?php _e('Allow Upload', 'mmt'); ?>"
                                           value="1"/>
                                </td>
                                <td style="vertical-align: middle;">
                                    <a href="javascript:void(0)" onclick="removeMimeType(this)" class="add-new-h2">remove</a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <tr class="mmt_clone" style="display: none;" data-index="<?php echo $i; ?>">
                        <td style="vertical-align: middle;">
                            <input type="text"
                                   name="mmt_mime_types[<?php echo $i; ?>][type]"
                                   placeholder="jpg|jpeg|jpe"/>
                        </td>
                        <td style="vertical-align: middle;">
                            <input type="text"
                                   name="mmt_mime_types[<?php echo $i; ?>][mime]"
                                   placeholder="image/jpeg"/>
                        </td>
                        <td style="vertical-align: middle;">
                            <input type="text"
                                   name="mmt_mime_types[<?php echo $i; ?>][singular]"
                                   placeholder="Image"/>
                        </td>
                        <td style="vertical-align: middle;">
                            <input type="text"
                                   name="mmt_mime_types[<?php echo $i; ?>][plural]"
                                   placeholder="Images"/>
                        </td>
                        <td class="checkbox_td" style="vertical-align: middle;">
                            <input type="checkbox"
                                   name="mmt_mime_types[<?php echo $i; ?>][filter]"
                                   title="<?php _e('Add Filter', 'mmt'); ?>"
                                   value="1"/>
                        </td>
                        <td class="checkbox_td" style="vertical-align: middle;">
                            <input type="checkbox"
                                   name="mmt_mime_types[<?php echo $i; ?>][allowed]"
                                   title="<?php _e('Allow Upload', 'mmt'); ?>"
                                   value="1"/>
                        </td>
                        <td style="vertical-align: middle;">
                            <a href="javascript:void(0)" onclick="removeMimeType(this)" class="add-new-h2">remove</a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col" class="manage-column">
                            <?php _e('Extension', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column">
                            <?php _e('MIME Type', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column">
                            <?php _e('Singular Label', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column">
                            <?php _e('Plural Label', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column">
                            <?php _e('Add Filter', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column">
                            <?php _e('Allow Upload', 'mmt'); ?>
                        </th>
                        <th scope="col" class="manage-column"></th>
                    </tr>
                </tfoot>
            </table>
            <script type="text/javascript">
                function removeMimeType(e) {
                    jQuery(e).parents('tr').remove();
                }
                function addMimeType() {
                    var c = jQuery('tr.mmt_clone');
                    jQuery('#mmt tbody').prepend(c.clone().removeClass('mmt_clone').show());

                    var i = parseInt(c.data('index')) + 1;
                    c.data('index', i);
                    c[0].innerHTML = c[0].innerHTML.replace(/\[[0-9]+\]/g, '[' + i + ']');
                }
            </script>
            <p class="submit">
                <?php submit_button(__('Restore default MIME Types', 'mmt'), 'secondary', 'mmt_restore_backup', false); ?>
                <?php submit_button(__('Save Changes'), 'primary', 'submit', false); ?>
            </p>
        </form>
    </div>
</div>
