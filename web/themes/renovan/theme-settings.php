<?php
use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\ThemeSettingsForm;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

function renovan_form_system_theme_settings_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form['settings'] = array(
        '#type' => 'details',
        '#title' => t('Theme settings'),
        '#open' => TRUE,
    );
    $form['settings']['header'] = array(
        '#type' => 'details',
        '#title' => t('Header settings'),
        '#open' => FALSE,
    );
    $form['settings']['header']['header_style'] = array(
        '#type' => 'select',
        '#title' => t('header layout'),
        '#options' => array(
            'page-home01' => t('Default'),
            'page-home02' => t('Style 2'),
            'page-home03' => t('Style 3'),
        ),
        '#default_value' => theme_get_setting('header_style', 'renovan'),
    );
    $form['settings']['header']['header_social_networks'] = array(
        '#type' => 'textarea',
        '#title' => t('Social networks'),
        '#default_value' => theme_get_setting('header_social_networks', 'renovan'),
    );

    $form['settings']['header']['page_header'] = array(
        '#type' => 'details',
        '#title' => t('Page header settings'),
        '#open' => FALSE,
    );

    $form['settings']['header']['page_header']['page_headers_style'] = array(
        '#title' => t('Page Headers Style'),
        '#type' => 'select',
        '#options' => array(
            'none' => t('Page Header - Default'),
            'image' => t('Page Header - Image background'),
            'big_image' => t('Page Header - Big Image background'),
        ),
        '#default_value' => theme_get_setting('page_headers_style', 'renovan'),
    );


    $form['settings']['header']['page_header']['header_bg_image_file'] = array(
        '#type' => 'textfield',
        '#title' => t('URL of the Header bg image'),
        '#default_value' => theme_get_setting('header_bg_image_file'),
        '#description' => t('Enter a URL Header bg image.'),
        '#size' => 40,
        '#maxlength' => 512,
    );
    $form['settings']['header']['page_header']['header_bg_image'] = array(
        '#type' => 'file',
        '#title' => t('Upload Header bg image'),
        '#size' => 40,
        '#attributes' => array('enctype' => 'multipart/form-data'),
        '#description' => t('If you don\'t jave direct access to the server, use this field to upload your Header bg image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),
        '#element_validate' => array('renovan_second_image_validate'),
    );


    $form['settings']['general_setting'] = array(
        '#type' => 'details',
        '#title' => t('General Settings'),
        '#open' => FALSE,
    );

    $form['settings']['general_setting']['general_setting_tracking_code'] = array(
        '#type' => 'textarea',
        '#title' => t('Tracking Code'),
        '#default_value' => theme_get_setting('general_setting_tracking_code', 'renovan'),
    );


    // Blog settings
    $form['settings']['blog'] = array(
        '#type' => 'details',
        '#title' => t('Blog settings'),
        '#open' => FALSE,
    );
    $form['settings']['blog']['blog_listing'] = array(
        '#type' => 'details',
        '#title' => t('Blog listing'),
        '#open' => FALSE,
    );
    $form['settings']['blog']['blog_listing']['blog_style'] = array(
        '#type' => 'select',
        '#title' => t('Default layout'),
        '#options' => array(
            'post-list' => t('List layout'),
            'grid' => t('Grid layout'),
            'simple' => t('Simple layout'),
        ),
        '#default_value' => theme_get_setting('blog_style', 'renovan'),
    );
    $form['settings']['blog']['blog_listing']['sidebar'] = array(
        '#type' => 'select',
        '#title' => t('Default sidebar'),
        '#options' => array(
            'left' => t('Left'),
            'right' => t('Right'),
        ),
        '#default_value' => theme_get_setting('sidebar', 'renovan'),
    );
    
      $form['settings']['projects'] = array(
        '#type' => 'details',
        '#title' => t('Projects settings'),
        '#open' => FALSE,
    );
       $form['settings']['projects']['projects_listing']['projects_sidebar'] = array(
        '#type' => 'select',
        '#title' => t('Default sidebar'),
        '#options' => array(
            'none' => t('None'),
            'left' => t('Left'),
            'right' => t('Right'),
        ),
        '#default_value' => theme_get_setting('projects_sidebar', 'renovan'),
    );

    // custom css
    $form['settings']['custom_css'] = array(
        '#type' => 'details',
        '#title' => t('Custom CSS'),
        '#open' => FALSE,
    );


    $form['settings']['custom_css']['custom_css'] = array(
        '#type' => 'textarea',
        '#title' => t('Custom CSS'),
        '#default_value' => theme_get_setting('custom_css', 'renovan'),
        '#description' => t('<strong>Example:</strong><br/>h1 { font-family: \'Metrophobic\', Arial, serif; font-weight: 400; }')
    );
    //footer settings
    $form['settings']['footer'] = array(
        '#type' => 'details',
        '#title' => t('Footer settings'),
        '#open' => FALSE,
    );

        $form['settings']['footer']['bg_footer_image_file'] = array(
        '#type' => 'textfield',
        '#title' => t('URL of the footer background image'),
        '#default_value' => theme_get_setting('bg_footer_image_file'),
        '#description' => t('Enter a URL footer background image.'),
        '#size' => 40,
        '#maxlength' => 512,
    );
    $form['settings']['footer']['bg_footer_image'] = array(
        '#type' => 'file',
        '#title' => t('Upload footer background image'),
        '#size' => 40,
        '#attributes' => array('enctype' => 'multipart/form-data'),
        '#description' => t('Use for Footer version ,If you don\'t jave direct access to the server, use this field to upload your footer background image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),
        '#element_validate' => array('renovan_second_image_validate'),
    );
}

function renovan_second_image_validate($element, FormStateInterface $form_state) {
    global $base_url;

    $validators = array('file_validate_extensions' => array('png gif jpg jpeg apng svg'));
    $file1 = file_save_upload('header_bg_image', $validators, "public://", NULL, FILE_EXISTS_REPLACE);

    if (!empty($file1)) {
        // change file's status from temporary to permanent and update file database
        if ((is_object($file1[0]) == 1)) {
            $file1[0]->status = FILE_STATUS_PERMANENT;
            $file1[0]->save();
            $uri = $file1[0]->getFileUri();
            $file_url1 = file_create_url($uri);
            $file_url1 = str_ireplace($base_url, '', $file_url1);
            $form_state->setValue('header_bg_image_file', $file_url1);
        }
    }
        $file3 = file_save_upload('bg_footer_image', $validators, "public://", NULL, FILE_EXISTS_REPLACE);

    if (!empty($file3)) {
        // change file's status from temporary to permanent and update file database
        if ((is_object($file3[0]) == 1)) {
            $file3[0]->status = FILE_STATUS_PERMANENT;
            $file3[0]->save();
            $uri = $file3[0]->getFileUri();
            $file_url3 = file_create_url($uri);
            $file_url3 = str_ireplace($base_url, '', $file_url3);
            $form_state->setValue('bg_footer_image_file', $file_url3);
        }
    }
}

?>