<?php

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Wordpress Plugin YetAnotherICALCalendar (PHP Component)
 *
 * @license MIT https://en.wikipedia.org/wiki/MIT_License
 * @author  Kai Thoene <k.git.thoene@gmx.net>
 */

/**
 * Taxonomy functions class.
 */
class YetAnotherICALCalendar_Taxonomy {
  /**
   * The name for the taxonomy.
   *
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $taxonomy;

  /**
   * The plural name for the taxonomy terms.
   *
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $plural;

  /**
   * The singular name for the taxonomy terms.
   *
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $single;

  /**
   * The array of post types to which this taxonomy applies.
   *
   * @var     array
   * @access  public
   * @since   1.0.0
   */
  public $post_types;

  /**
   * The array of taxonomy arguments
   *
   * @var     array
   * @access  public
   * @since   1.0.0
   */
  public $taxonomy_args;

  /**
   * Taxonomy constructor.
   *
   * @param string $taxonomy Taxonomy variable nnam.
   * @param string $plural Taxonomy plural name.
   * @param string $single Taxonomy singular name.
   * @param array  $post_types Affected post types.
   * @param array  $tax_args Taxonomy additional args.
   */
  public function __construct( $taxonomy = '', $plural = '', $single = '', $post_types = array(), $tax_args = array() ) {

    if ( ! $taxonomy || ! $plural || ! $single ) {
      return;
    }

    // Post type name and labels.
    $this->taxonomy = $taxonomy;
    $this->plural   = $plural;
    $this->single   = $single;
    if ( ! is_array( $post_types ) ) {
      $post_types = array( $post_types );
    }
    $this->post_types    = $post_types;
    $this->taxonomy_args = $tax_args;

    // Register taxonomy.
    add_action( 'init', array( $this, 'register_taxonomy' ) );
  }

  /**
   * Register new taxonomy
   *
   * @return void
   */
  public function register_taxonomy() {
    //phpcs:disable
    $labels = array(
      'name'                       => $this->plural,
      'singular_name'              => $this->single,
      'menu_name'                  => $this->plural,
      'all_items'                  => sprintf( __( 'All %s', 'yetanothericalcalendar' ), $this->plural ),
      'edit_item'                  => sprintf( __( 'Edit %s', 'yetanothericalcalendar' ), $this->single ),
      'view_item'                  => sprintf( __( 'View %s', 'yetanothericalcalendar' ), $this->single ),
      'update_item'                => sprintf( __( 'Update %s', 'yetanothericalcalendar' ), $this->single ),
      'add_new_item'               => sprintf( __( 'Add New %s', 'yetanothericalcalendar' ), $this->single ),
      'new_item_name'              => sprintf( __( 'New %s Name', 'yetanothericalcalendar' ), $this->single ),
      'parent_item'                => sprintf( __( 'Parent %s', 'yetanothericalcalendar' ), $this->single ),
      'parent_item_colon'          => sprintf( __( 'Parent %s:', 'yetanothericalcalendar' ), $this->single ),
      'search_items'               => sprintf( __( 'Search %s', 'yetanothericalcalendar' ), $this->plural ),
      'popular_items'              => sprintf( __( 'Popular %s', 'yetanothericalcalendar' ), $this->plural ),
      'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'yetanothericalcalendar' ), $this->plural ),
      'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'yetanothericalcalendar' ), $this->plural ),
      'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'yetanothericalcalendar' ), $this->plural ),
      'not_found'                  => sprintf( __( 'No %s found', 'yetanothericalcalendar' ), $this->plural ),
    );
    //phpcs:enable
    $args = array(
      'label'                 => $this->plural,
      'labels'                => apply_filters( $this->taxonomy . '_labels', $labels ),
      'hierarchical'          => true,
      'public'                => true,
      'show_ui'               => true,
      'show_in_nav_menus'     => true,
      'show_tagcloud'         => true,
      'meta_box_cb'           => null,
      'show_admin_column'     => true,
      'show_in_quick_edit'    => true,
      'update_count_callback' => '',
      'show_in_rest'          => true,
      'rest_base'             => $this->taxonomy,
      'rest_controller_class' => 'WP_REST_Terms_Controller',
      'query_var'             => $this->taxonomy,
      'rewrite'               => true,
      'sort'                  => '',
    );

    $args = array_merge( $args, $this->taxonomy_args );

    register_taxonomy( $this->taxonomy, $this->post_types, apply_filters( $this->taxonomy . '_register_args', $args, $this->taxonomy, $this->post_types ) );
  }
}
