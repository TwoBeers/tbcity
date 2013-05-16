<?php
/**
 * hooks.php
 *
 * defines every wrapping function for the theme hooks
 *
 * Includes The Hook Alliance support file (https://github.com/zamoose/themehookalliance)
 *
 * @package The Black City
 * @since 2.00
 */


/** Grab the THA theme hooks file */
require_once( get_template_directory() . '/lib/tha/tha-theme-hooks.php' );

/**
 * the <head> section
 */
function tbcity_hook_head_top() {
	tha_head_top();
	do_action( 'tbcity_hook_head_top' );
}

function tbcity_hook_head_bottom() {
	do_action( 'tbcity_hook_head_bottom' );
	tha_head_bottom();
}

/**
 * the header section
 */
function tbcity_hook_header_before() {
	tha_header_before();
	do_action( 'tbcity_hook_header_before' );
}

function tbcity_hook_header_after() {
	do_action( 'tbcity_hook_header_after' );
	tha_header_after();
}

function tbcity_hook_header_top() {
	tha_header_top();
	do_action( 'tbcity_hook_header_top' );
}

function tbcity_hook_header_bottom() {
	do_action( 'tbcity_hook_header_bottom' );
	tha_header_bottom();
}

/**
 * the content section
 */
function tbcity_hook_content_before() {
	tha_content_before();
	do_action( 'tbcity_hook_content_before' );
}

function tbcity_hook_content_after() {
	do_action( 'tbcity_hook_content_after' );
	tha_content_after();
}

function tbcity_hook_content_top() {
	tha_content_top();
	do_action( 'tbcity_hook_content_top' );
}

function tbcity_hook_content_bottom() {
	do_action( 'tbcity_hook_content_bottom' );
	tha_content_bottom();
}

/**
 * the entry section
 */
function tbcity_hook_entry_before() {
	tha_entry_before();
	do_action( 'tbcity_hook_entry_before' );
}

function tbcity_hook_entry_after() {
	do_action( 'tbcity_hook_entry_after' );
	tha_entry_after();
}

function tbcity_hook_entry_top() {
	tha_entry_top();
	do_action( 'tbcity_hook_entry_top' );
}

function tbcity_hook_entry_bottom() {
	do_action( 'tbcity_hook_entry_bottom' );
	tha_entry_bottom();
}

function tbcity_hook_entry_content_top() {
	do_action( 'tbcity_hook_entry_content_top' );
}

function tbcity_hook_entry_content_bottom() {
	do_action( 'tbcity_hook_entry_content_bottom' );
}

/**
 * the comments section
 */
function tbcity_hook_comments_before() {
	tha_comments_before();
	do_action( 'tbcity_hook_comments_before' );
}

function tbcity_hook_comments_after() {
	do_action( 'tbcity_hook_comments_after' );
	tha_comments_after();
}

function tbcity_hook_comments_list_before() {
	do_action( 'tbcity_hook_comments_list_before' );
}

function tbcity_hook_comments_list_after() {
	do_action( 'tbcity_hook_comments_list_after' );
}

/**
 * the sidebars section
 *
 * currently supported $location:
 * - primary -> sidebar.php
 * - secondary -> sidebar-secondary.php
 * - header -> sidebar-header.php
 * - footer -> sidebar-footer.php
 * - error404 -> sidebar-error404.php
 */
function tbcity_hook_sidebars_before( $location = 'undefined' ) {
	tha_sidebars_before();
	do_action( 'tbcity_hook_sidebars_before' );
	do_action( 'tbcity_hook_' . $location . '_sidebar_before' );
}

function tbcity_hook_sidebars_after( $location = 'undefined' ) {
	do_action( 'tbcity_hook_' . $location . '_sidebar_after' );
	do_action( 'tbcity_hook_sidebars_after' );
	tha_sidebars_after();
}

function tbcity_hook_sidebar_top( $location = 'undefined' ) {
	tha_sidebar_top();
	do_action( 'tbcity_hook_sidebar_top' );
	do_action( 'tbcity_hook_' . $location . '_sidebar_top' );
}

function tbcity_hook_sidebar_bottom( $location = 'undefined' ) {
	do_action( 'tbcity_hook_' . $location . '_sidebar_bottom' );
	do_action( 'tbcity_hook_sidebar_bottom' );
	tha_sidebar_bottom();
}

/**
 * the footer section
 */
function tbcity_hook_footer_before() {
	tha_footer_before();
	do_action( 'tbcity_hook_footer_before' );
}

function tbcity_hook_footer_after() {
	do_action( 'tbcity_hook_footer_after' );
	tha_footer_after();
}

function tbcity_hook_footer_top() {
	tha_footer_top();
	do_action( 'tbcity_hook_footer_top' );
}

function tbcity_hook_footer_bottom() {
	do_action( 'tbcity_hook_footer_bottom' );
	tha_footer_bottom();
}

/**
 * the <body> section
 */
function tbcity_hook_body_top() {
	do_action( 'tbcity_hook_body_top' );
}

function tbcity_hook_body_bottom() {
	do_action( 'tbcity_hook_body_bottom' );
}

/**
 * the posts title section
 */
function tbcity_hook_post_title_before() {
	do_action( 'tbcity_hook_post_title_before' );
}

function tbcity_hook_post_title_after() {
	do_action( 'tbcity_hook_post_title_after' );
}

function tbcity_hook_post_title_top() {
	do_action( 'tbcity_hook_post_title_top' );
}

function tbcity_hook_post_title_bottom() {
	do_action( 'tbcity_hook_post_title_bottom' );
}

function tbcity_hook_post_title_wrap_top() {
	do_action( 'tbcity_hook_post_title_wrap_top' );
}

function tbcity_hook_post_title_wrap_bottom() {
	do_action( 'tbcity_hook_post_title_wrap_bottom' );
}

/**
 * the breadcrumb section
 */
function tbcity_hook_breadcrumb_navigation () {
	do_action( 'tbcity_hook_breadcrumb_navigation' );
}

function tbcity_hook_change_view() {
	do_action( 'tbcity_hook_change_view' );
}
