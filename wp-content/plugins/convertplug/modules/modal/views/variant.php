<?php
// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

$test = isset( $_GET['variant-test'] ) ? esc_attr( $_GET['variant-test'] ) : 'main';

switch($test){
	case 'new':
		require_once(CP_BASE_DIR_MODAL.'/views/variant/new.php');
		break;
	case 'edit':
		require_once(CP_BASE_DIR_MODAL.'/views/variant/edit.php');
		break;
	case 'main':
	default:
		require_once(CP_BASE_DIR_MODAL.'/views/variant/variant.php');
		break;
}