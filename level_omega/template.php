<?php
// $Id: template.php,v 1.1.2.9 2010/07/09 14:53:42 himerus Exp $

/*
 * Add any conditional stylesheets you will need for this sub-theme.
 *
 * To add stylesheets that ALWAYS need to be included, you should add them to
 * your .info file instead. Only use this section if you are including
 * stylesheets based on certain conditions.
 */
/* -- Delete this line if you want to use and modify this code
// Example: optionally add a fixed width CSS file.
if (theme_get_setting('omega_starterkit_fixed')) {
  drupal_add_css(path_to_theme() . '/layout-fixed.css', 'theme', 'all');
}
// */


/**
 * Implementation of HOOK_theme().
 */
function level_omega_theme(&$existing, $type, $theme, $path) {
  $hooks = omega_theme($existing, $type, $theme, $path);
  // Consistant UI friendly date which can't be created with format date
  $hooks['ui_date'] = array(
    'arguments' => array ('date' => NULL, $prefix = ''),
   );

  
  // Add your theme hooks like this:
  /*
  $hooks['hook_name_here'] = array( // Details go here );
  */
  // @TODO: Needs detailed comments. Patches welcome!
  return $hooks;
}

/** @defgroup theme_functions
 * Theme functions implementations.
 *  @{
 */  


/**
 * 
 * Format a date in HTLM in a ui friendly format
 * @param unknown_type $date
 */
function level_omega_ui_date($date, $prefix = '') {
  $ui_date = '<span class="ui_date">' . $prefix .
   '<em>' .
  date('jS F', $date) .
  '</em> ' .
  '<span class="year">' . date('Y',$date) . '</span>' .
  '</span>';
  
  return $ui_date;
}

/** @} */

/** @defgroup custom functions
 *  Functions which are common to the theme but not standard drupal theme 
 *  functions.
 *  @{
 */  

/** @} */

/** @defgroup Preprocessors
 *  Pre process functions. 
 *  @{
 */  


/**
 * Override or insert variables into all templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered (name of the .tpl.php file.)
 */
function level_omega_preprocess(&$vars, $hook) {

  // Preprocessing for items we don't have templates for
  switch ($hook) {
    case 'views_view':
      // Set the title of the the page for the company profile view.
      if($vars['name'] == "companies_house_latest_profile_2") {
        if ($vars['view']->result[0]->name) {
          drupal_set_title($vars['view']->result[0]->name);
          global $company_name;
          $company_name = $vars['view']->result[0]->name;
        }
     }

     if ($vars['name'] == 'ch_solr_transactions') {
        // Get the date and set the title if the view is one of the main blocks
        // summary_page, page_2 or block
        $system_date = strtotime(variable_get('level_platform_latest_daily_date','0000-00-00'));
        $date = date('jS F Y',$system_date);
        $title = "leveldaily update for ";
        if ($vars['display_id'] == 'block_1') {
          $vars['view']->build_info['title'] = $title . $date;
        }
        if ($vars['display_id'] == 'summary_page') {
          drupal_set_title($title . $date);
        }
        if ($vars['display_id'] == 'summary_homepage_page') {          
          $vars['header'] = '<h1>' . theme('ui_date',$system_date,$title) . '</h1>';
        }
      }
    break;
    
  }
  

}

function level_omega_preprocess_block(&$vars, $hook) {
  // Substitute in some text into the twitter block. 
  $vars['extra_classes'] = '';
  
  switch($vars['block']->delta) {
    case 'tweet_block':
      global $company_name;
      $vars['block']->content = str_replace('class="twitter-share-button"', 'class="twitter-share-button" data-text="I\'ve been looking at the level profile for ' . $company_name . '"' , $vars['block']->content);    
    break;
    case 'company_transactions':
      // Set up the solr objects
      $views_query = apachesolr_current_query();
      list($module, $solr_search_class) = variable_get('apachesolr_query_class', array('apachesolr', 'Solr_Base_Query'));
      $filters = $_GET['filters'];
      $solr_query =  new $solr_search_class(apachesolr_get_solr(), '' , $filters , $views_query->get_solrsort() , $views_query->get_path());
      // Check that there is a company transacitions filter.
      $filter_search = $views_query->get_filters('company_transactions');
           
      if (isset($filter_search[0]['#value'])) {
        $transaction_filter = $filter_search[0]['#value'];
        $filter_elements = explode('|',$transaction_filter);
        $facet_text = constant ('LEVEL_PLATFORM_TRANSACTION_TYPES_' .  $filter_elements[1]);
        $facet_text .= ' on ' . theme('ui_date',strtotime($filter_elements[0]));       
        // Remove the filter
        $solr_query->remove_filter('company_transactions', $transaction_filter);
        $options['query'] = $solr_query->get_url_queryvalues();
        $options['html'] = $facet_text;
        $vars['block']->subject = 'Filtered by';
        $vars['block']->content = theme('apachesolr_unclick_link', $facet_text, $solr_query->get_path(), $options);
      }
      else {
        unset($vars['block']);
      }
    break;
    case 'converted_count-block_counter':
       $vars['extra_classes'] = 'grid_2 alpha';
    break;
  }
  
  if ($vars['block']->module == 'webform') {
    $vars['extra_classes'] = 'grid_1 omega';
  }

  

  if ($vars['block']->region == 'footer') {
    if ($vars['block_id'] == 1) {
      $vars['extra_classes'] = 'alpha';
    } 
    if ($vars['block_id'] == 4) {
      $vars['extra_classes'] = 'omega';
    } 
  }
  

}

function level_omega_preprocess_views_view_field(&$vars, $hook) {
  //var_dump(array_keys($vars));
  //var_dump($vars['field']->options['id']);
  switch($vars['field']->options['id']) {
  case 'return_made_up_date':
  case 'incorporation_date':
    $vars['output'] = date('Y m d',strtotime($vars['output']));
  break;
  case 'accounts_made_up_date':
    $vars['output'] = date('d M Y',strtotime($vars['output']));
  break;
  }
}
/**
 * Override or insert variables into the page templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function level_omega_preprocess_page(&$vars, $hook) {
   
  if(arg(1) == 'company' || arg(0) == 'company') {
    $vars['scripts'] .= '<script src="http://platform.twitter.com/widgets.js"></script>';
    $vars['scripts'] .= '<script> FB.init({
     appId  : "206040062739797",
     status : false, // check login status
     cookie : false, // enable cookies to allow the server to access the session
     xfbml  : true  // parse XFBML
   });
    </script>';
    $vars['scripts'] .= '<script src="http://www.scribd.com/javascripts/view.js"></script>';
  
  }
}
/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function level_omega_preprocess_node(&$vars, $hook) {
  // Change classes for items on the homepage
  
  if($vars['node']->type == 'in_the_news') {
    $vars['node_attributes']['class'][] = 'grid_1';
    if($vars['id'] % 3 == 1) {
      $vars['node_attributes']['class'][] = 'alpha';
    }
    if($vars['id'] % 3 == 0) {
      $vars['node_attributes']['class'][] = 'omega';
    }
    // Attributes has already been set, so re do them
    $new_attributes = $vars['node_attributes'];
    $new_attributes['class'] = implode(' ',$new_attributes['class']);
    $vars['attributes'] = drupal_attributes($new_attributes);
  }
}

function level_omega_preprocess_apachesolr_currentsearch(&$vars, $hook) {
  // Create the current search term as a variable
  drupal_add_js(drupal_get_path('module', 'apachesolr') . '/apachesolr.js');
  $vars['current_search'] = filter_xss($_GET['text']);
  if ($vars['current_search'] == "*:*") {
    $vars['current_search'] = '';    
  }

  $views_query = apachesolr_current_query();
  $filter_search = $views_query->get_filters('company_transactions');
  if (isset($filter_search[0]['#value'])) {
        $transaction_filter = $filter_search[0]['#value'];
        $filter_elements = explode('|',$transaction_filter);
        $facet_text .=  theme('ui_date',strtotime($filter_elements[0]),constant ('LEVEL_PLATFORM_TRANSACTION_TYPES_' .  $filter_elements[1]) . ' on ');       
        $vars['transaction_search'] = $facet_text;    
        $title =  $facet_text;
  }
  

  if ($title) {
    drupal_set_title($title);    
  }
}

/** @} */

/**
 * Create a string of attributes form a provided array.
 * 
 * @param $attributes
 * @return string
 */
function level_omega_render_attributes($attributes) {
	return omega_render_attributes($attributes);  
}


function level_omega_apachesolr_facet_link($facet_text, $path, $options = array(), $count, $active = FALSE, $num_found = NULL) {
  $options['attributes']['class'][] = 'apachesolr-facet';
  if ($active) {
    $options['attributes']['class'][] = 'active';
  }
  $options['attributes']['class'] = implode(' ', $options['attributes']['class']);
  $formatted_count = number_format($count);
  return $facet_text . "&nbsp;" .apachesolr_l("($formatted_count)",   $path, $options);
}
function level_omega_apachesolr_unclick_link($facet_text, $path, $options = array()) {
  if (empty($options['html'])) {
    $facet_text = check_plain($facet_text);
  }

  else {
    // Don't pass this option as TRUE into apachesolr_l().
    unset($options['html']);
  }

  // If 'filters' is not present apachesolr_l will ignore it and keep the 
  // existing filter so add the empty string to clear final filter.
  if (!isset($options['query']['filters'])) {
    $options['query']['filters']  = "";
  }
  $options['attributes']['class'] = 'apachesolr-unclick';
  $options['html'] = '<div class="link_text">' . $facet_text . '</div> (remove)';
//  var_dump($options);
  return   apachesolr_l('<div class="link_text">' . $facet_text . '</div> (remove)', $path, $options);
}
