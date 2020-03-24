<?php
 /**
 * class Cwd_wp_bootstrapwp_Walker_Nav_Menu()
 *
 * Extending Walker_Nav_Menu Class
 *
 * @author Gabriel Vasile
 **/
  class Cwd_wp_bootstrapwp_Walker_Nav_Menu extends Walker_Nav_Menu {
 
    function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
      // check, whether there are children for the given ID and append it to the element with a (new) ID
      $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);
 
      return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
 
    // CHANGE .sub-menu INTO .dropdown-menu
    function start_lvl(&$output, $depth = 0, $args = array()) {
      $indent = str_repeat("\t", $depth);
      $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }
 
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
      $item_html = '';
      parent::start_el($item_html, $item, $depth, $args);
 
      if (($item->hasChildren) && ($depth === 0)) {
        $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html);
        $item_html = str_replace('</a>', ' <i class="fa fa-caret-down menucaret"></i></a>', $item_html);
      }
 
      $output .= $item_html;
    }
 
  }
 
  function cwd_wp_bootstrapwp_nav_menu_css_class($classes, $item) {
    // CHANGE .current-menu-item .current-menu-parent .current-menu-ancestor INTO .active
    $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
 
    // Add the .dropdown class to the list items that have children
    if ($item->hasChildren) {
      $classes[] = 'dropdown';
    }
 
    return $classes;
  }
  add_filter('nav_menu_css_class', 'cwd_wp_bootstrapwp_nav_menu_css_class', 10, 2);