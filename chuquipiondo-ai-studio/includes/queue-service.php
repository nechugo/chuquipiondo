<?php
/**
 * WP-Cron queue for AI batch generation.
 *
 * Generating several articles in one request can exhaust PHP's execution
 * time on cheap hosting. This service enqueues one event per article and
 * processes them one at a time via WP-Cron, with a synchronous fallback.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue a batch of topics as individual cron events.
 *
 * @param array $batch   Array of publish-service params (one per article).
 * @param bool  $publish Force publish each article when done.
 * @return array {queued: int, ids: array of queue ids}
 */
function chuquipiondo_ai_queue_batch( array $batch, $publish = false ) {
	$queued = 0;
	$ids    = array();
	foreach ( $batch as $params ) {
		if ( empty( $params['topic'] ) ) {
			continue;
		}
		$queue_id = wp_generate_password( 12, false );
		update_option(
			'chuquipiondo_ai_queue_' . $queue_id,
			array(
				'params' => $params,
				'publish' => (bool) $publish,
				'status' => 'pending',
				'created' => time(),
			),
			false
		);
		wp_schedule_single_event( time() + $queued, 'chuquipiondo_ai_queue_item', array( $queue_id ) );
		$ids[] = $queue_id;
		$queued++;
	}
	return array( 'queued' => $queued, 'ids' => $ids );
}

/**
 * Cron worker: process one queued article.
 *
 * @param string $queue_id Queue item id.
 */
function chuquipiondo_ai_process_queue_item( $queue_id ) {
	$key   = 'chuquipiondo_ai_queue_' . sanitize_key( $queue_id );
	$state = get_option( $key );
	if ( ! is_array( $state ) || 'done' === $state['status'] ) {
		return;
	}
	update_option( $key, array_merge( $state, array( 'status' => 'running' ) ), false );
	$result = Chuquipiondo_AI_Publish_Service::create( $state['params'], (bool) $state['publish'] );
	if ( is_wp_error( $result ) ) {
		update_option( $key, array_merge( $state, array(
			'status' => 'error',
			'error'  => $result->get_error_message(),
		) ), false );
		return;
	}
	update_option( $key, array_merge( $state, array(
		'status'    => 'done',
		'post_id'   => isset( $result['id'] ) ? (int) $result['id'] : 0,
		'post_url'  => isset( $result['post_url'] ) ? $result['post_url'] : '',
	) ), false );
}
add_action( 'chuquipiondo_ai_queue_item', 'chuquipiondo_ai_process_queue_item' );

/**
 * REST: queue status for the admin UI polling.
 */
function chuquipiondo_ai_queue_status( $ids ) {
	$out = array();
	foreach ( (array) $ids as $id ) {
		$state = get_option( 'chuquipiondo_ai_queue_' . sanitize_key( $id ) );
		if ( is_array( $state ) ) {
			$out[ $id ] = array(
				'status'   => $state['status'],
				'post_id'  => isset( $state['post_id'] ) ? (int) $state['post_id'] : 0,
				'post_url' => isset( $state['post_url'] ) ? $state['post_url'] : '',
				'error'    => isset( $state['error'] ) ? $state['error'] : '',
			);
		}
	}
	return $out;
}
