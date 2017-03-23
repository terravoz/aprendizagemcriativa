<?php
/**
 * @file
 * The primary PHP file for this theme.
 */

function aprendizagemcriativa_preprocess_node(&$variables) {
  $node = $variables['node'];
  if ($node->type == 'project' && $variables['elements']['#view_mode'] == 'full') {
    //If Other is selected in target_public or activity_type then use value from other field

    $target_public = field_get_items('node', $node, 'field_target_public');
    foreach ($target_public as $key => $item) {
      if($item['tid'] == 11) {
        //Other term, then use value from field_target_public_other
        $target_public_other = field_get_items('node', $node, 'field_target_public_other');
        $variables['content']['group_basic_info']['field_target_public'][$key]['#markup'] .= ' ' . $target_public_other[0]['safe_value'];
        break;
      }
    }

    $activity_type = field_get_items('node', $node, 'field_activity_type');
    foreach ($activity_type as $key => $item) {
      if($item['tid'] == 15) {
        //Other term, then use value from field_activity_type_other
        $activity_type_other = field_get_items('node', $node, 'field_activity_type_other');
        $variables['content']['group_basic_info']['field_activity_type'][$key]['#markup'] .= ' ' . $activity_type_other[0]['safe_value'];
        break;
      }
    }

    //Link Author to projects/{author_email} view
    $authors = field_get_items('node', $node, 'field_authors');
    if($authors) {
      foreach ($authors as $key => $item) {
        $author = $item['entity'];
        $author_email_field = field_get_items('node', $author, 'field_email');
        $author_email = $author_email_field[0]['email'];

        $institution = field_get_items('node', $author, 'field_institution');
        $authors_output = $author->title .' ('.$institution[0]['safe_value'].')';
        $variables['content']['group_basic_info']['field_authors'][$key]['#markup'] = l($authors_output, 'projects/'.$author_email);
      }
    }
  }

  if($node->type == 'project' && $variables['elements']['#view_mode'] == 'teaser') {
    //node--project--teaser.tpl.php
    $variables['theme_hook_suggestions'][] = 'node__project__teaser';

    //Author displayed as Name (Institution)
    $authors = field_get_items('node', $node, 'field_authors');
    $authors_output = array();

    if($authors) {
      foreach($authors as $key => $item) {
        $author = $item['entity'];
        $institution = field_get_items('node', $author, 'field_institution');
        $authors_output[]= $author->title .' ('.$institution[0]['safe_value'].')';
      }
    }
    $variables['author'] = implode($authors_output, ", ");
    //Background image
    $image = field_get_items('node', $node, 'field_image');
    if ($image) {
      $variables['background_image'] = file_create_url($image[0]['uri']);
    }
    else {
      $variables['background_image'] = '';
    }


  }
}

function aprendizagemcriativa_filter_tips_more_info() { return ''; }
