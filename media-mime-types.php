<?php
/**
 * Plugin Name: Media Mime Types
 * Description: Allow control mime types and filter them in media page
 * Version: 1.0.3
 * Author: Andriy Kuzyk
 */


if (is_admin()) {
    add_filter('plugin_action_links', 'mmt_settings_link', 10, 2);
    function mmt_settings_link($links, $file)
    {
        if ($file == 'media-mime-types/media-mime-types.php') {
            $url = admin_url('options-general.php?page=media-mime-types');
            $_links = array(
                'settings' => '<a href="' . $url . '">Settings</a>'
            );
            $links = $_links + $links;
        }
        return $links;
    }

    add_action('admin_menu', 'mmt_admin_menu');
    function mmt_admin_menu()
    {
        add_options_page(
            'Mime Types Control', 'Media Mime Types', 'manage_options', 'media-mime-types', 'mmt_options_page'
        );
    }

    function mmt_options_page()
    {
        require __DIR__ . '/tpl/table.php';
    }

    add_action('admin_init', 'mmt_init');
    function mmt_init()
    {
        register_setting('mmt_mime_types', 'mmt_mime_types', 'mmt_mime_types_save');
    }

    function mmt_mime_types_save($mmt_mime_types)
    {
        $mime_types = array();
        if (!isset($_REQUEST['mmt_restore_backup'])) {
            foreach ($mmt_mime_types as $mime) {
                $type = sanitize_mime_type($mime['type']);
                if ($type && !empty($mime['mime'])) {
                    $mime_types[$mime['type']] = array(
                        'mime'     => sanitize_mime_type($mime['mime']),
                        'type'     => $type,
                        'plural'   => sanitize_mime_type(!empty($mime['plural']) ? $mime['plural'] : $mime['mime']),
                        'singular' => sanitize_mime_type(!empty($mime['singular']) ? $mime['singular'] : $mime['mime']),
                        'filter'   => !empty($mime['filter']) ? (int)$mime['filter'] : 0,
                        'allowed'  => !empty($mime['allowed']) ? (int)$mime['allowed'] : 0
                    );
                }
            }
        } else {
            $mime_types = get_option('mmt_mime_types_backup');
        }
        return $mime_types;
    }
}

register_activation_hook(__FILE__, 'mmt_activate');
function mmt_activate()
{
    if (!get_option('mmt_mime_types_backup') || !get_option('mmt_mime_types')) {
        $all_mimes = wp_get_mime_types();
        $allowed_mimes = get_allowed_mime_types();
        $mmt_mime_types = array();
        foreach ($all_mimes as $type => $mime) {
            $mmt_mime_types[$type] = array(
                'mime'     => $mime,
                'type'     => $type,
                'plural'   => $type,
                'singular' => $type,
                'filter'   => 0,
                'allowed'  => isset($allowed_mimes[$type])
            );
        }
        update_option('mmt_mime_types_backup', $mmt_mime_types);
        update_option('mmt_mime_types', $mmt_mime_types);
    }
}

add_filter('post_mime_types', 'mmt_post_mime_types');
function mmt_post_mime_types($post_mime_types)
{
    $mmt_mime_types = get_option('mmt_mime_types');

    if (!empty($mmt_mime_types) && is_array($mmt_mime_types)) {
        foreach ($mmt_mime_types as $mime) {
            if ($mime['filter'] == 1) {
                $post_mime_types[$mime['mime']] = array(
                    $mime['singular'],
                    'Manage ' . $mime['singular'],
                    _n_noop(
                        $mime['singular'] . ' <span class="count">(%s)</span>',
                        $mime['plural'] . ' <span class="count">(%s)</span>'
                    )
                );
            }
        }
    }

    return $post_mime_types;
}


add_filter('upload_mimes', 'mmt_upload_mimes');
function mmt_upload_mimes($existing_mimes = array())
{
    $allowed_types = array();
    $mmt_mime_types = get_option('mmt_mime_types');

    if (!$mmt_mime_types || !is_array($mmt_mime_types)) {
        return $existing_mimes;
    }


    foreach ($mmt_mime_types as $mime) {
        if ($mime['allowed']) {
            $allowed_types[$mime['type']] = $mime['mime'];
        }
    }
    return $allowed_types;
}


add_filter('mime_types', 'mmt_mime_types');
function mmt_mime_types($existing_mimes)
{
    $mmt_mime_types = get_option('mmt_mime_types');

    if (!$mmt_mime_types || !is_array($mmt_mime_types)) {
        return $existing_mimes;
    }

    $mime_types = array();
    foreach ($mmt_mime_types as $mime) {
        $mime_types[$mime['type']] = $mime['mime'];
    }
    return $mime_types;
}
