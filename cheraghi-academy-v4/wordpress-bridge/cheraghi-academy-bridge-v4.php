<?php
/**
 * Plugin Name: Cheraghi Academy Bridge V4
 * Description: REST bridge for Cheraghi Academy article drafts, verification and publishing.
 * Version: 4.0.0-alpha
 * Author: Cheraghi Academy
 */

if (!defined('ABSPATH')) { exit; }

final class Cheraghi_Academy_Bridge_V4 {
    const NS = 'cheraghi-academy/v4';

    public static function init() {
        add_action('rest_api_init', [__CLASS__, 'routes']);
    }

    public static function permission_edit() {
        return current_user_can('edit_posts');
    }

    public static function permission_publish() {
        return current_user_can('publish_posts');
    }

    public static function routes() {
        register_rest_route(self::NS, '/health', [
            'methods' => 'GET',
            'callback' => [__CLASS__, 'health'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route(self::NS, '/draft', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'create_draft'],
            'permission_callback' => [__CLASS__, 'permission_edit'],
        ]);

        register_rest_route(self::NS, '/verify/(?P<id>\\d+)', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'verify'],
            'permission_callback' => [__CLASS__, 'permission_edit'],
        ]);

        register_rest_route(self::NS, '/publish/(?P<id>\\d+)', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'publish'],
            'permission_callback' => [__CLASS__, 'permission_publish'],
        ]);

        register_rest_route(self::NS, '/status/(?P<id>\\d+)', [
            'methods' => 'GET',
            'callback' => [__CLASS__, 'status'],
            'permission_callback' => [__CLASS__, 'permission_edit'],
        ]);
    }

    public static function health() {
        return new WP_REST_Response([
            'ok' => true,
            'version' => '4.0.0-alpha',
            'namespace' => self::NS,
        ], 200);
    }

    public static function create_draft(WP_REST_Request $request) {
        $title = sanitize_text_field((string) $request->get_param('title'));
        $content = wp_kses_post((string) $request->get_param('content'));
        $excerpt = sanitize_textarea_field((string) $request->get_param('excerpt'));
        $slug = sanitize_title((string) $request->get_param('slug'));
        $source_status = sanitize_key((string) $request->get_param('source_status'));
        $qc_status = sanitize_key((string) $request->get_param('qc_status'));

        if (!$title || !$content) {
            return new WP_Error('cheraghi_missing_fields', 'title and content are required', ['status' => 400]);
        }

        if ($source_status !== 'verified' || $qc_status !== 'passed') {
            return new WP_Error('cheraghi_quality_gate', 'Source and QC must pass before draft creation', ['status' => 422]);
        }

        $post_id = wp_insert_post([
            'post_type' => 'post',
            'post_status' => 'draft',
            'post_title' => $title,
            'post_content' => $content,
            'post_excerpt' => $excerpt,
            'post_name' => $slug,
        ], true);

        if (is_wp_error($post_id)) { return $post_id; }

        update_post_meta($post_id, '_cheraghi_academy_v4', 1);
        update_post_meta($post_id, '_cheraghi_source_status', 'verified');
        update_post_meta($post_id, '_cheraghi_qc_status', 'passed');
        update_post_meta($post_id, '_cheraghi_review_status', 'pending');

        return new WP_REST_Response([
            'ok' => true,
            'post_id' => $post_id,
            'status' => 'draft',
            'review_status' => 'pending',
        ], 201);
    }

    public static function verify(WP_REST_Request $request) {
        $post_id = absint($request['id']);
        if (!get_post($post_id)) {
            return new WP_Error('cheraghi_not_found', 'Post not found', ['status' => 404]);
        }

        $decision = sanitize_key((string) $request->get_param('decision'));
        $notes = sanitize_textarea_field((string) $request->get_param('notes'));
        if (!in_array($decision, ['approved', 'rejected', 'needs_review'], true)) {
            return new WP_Error('cheraghi_bad_decision', 'Invalid decision', ['status' => 400]);
        }

        update_post_meta($post_id, '_cheraghi_review_status', $decision);
        update_post_meta($post_id, '_cheraghi_review_notes', $notes);

        return new WP_REST_Response(['ok' => true, 'post_id' => $post_id, 'review_status' => $decision], 200);
    }

    public static function publish(WP_REST_Request $request) {
        $post_id = absint($request['id']);
        $post = get_post($post_id);
        if (!$post) {
            return new WP_Error('cheraghi_not_found', 'Post not found', ['status' => 404]);
        }

        if (get_post_meta($post_id, '_cheraghi_review_status', true) !== 'approved') {
            return new WP_Error('cheraghi_not_approved', 'Post must be approved before publishing', ['status' => 422]);
        }

        $result = wp_update_post(['ID' => $post_id, 'post_status' => 'publish'], true);
        if (is_wp_error($result)) { return $result; }

        return new WP_REST_Response(['ok' => true, 'post_id' => $post_id, 'status' => 'publish'], 200);
    }

    public static function status(WP_REST_Request $request) {
        $post_id = absint($request['id']);
        $post = get_post($post_id);
        if (!$post) {
            return new WP_Error('cheraghi_not_found', 'Post not found', ['status' => 404]);
        }

        return new WP_REST_Response([
            'ok' => true,
            'post_id' => $post_id,
            'post_status' => $post->post_status,
            'source_status' => get_post_meta($post_id, '_cheraghi_source_status', true),
            'qc_status' => get_post_meta($post_id, '_cheraghi_qc_status', true),
            'review_status' => get_post_meta($post_id, '_cheraghi_review_status', true),
        ], 200);
    }
}

Cheraghi_Academy_Bridge_V4::init();
