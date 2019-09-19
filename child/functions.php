<?php
include('custom-option.php');

// for category
function create_new_category_api($term_id, $taxonomy_term_id){
	$url = '/createCategory';
	$category = get_category($term_id);
	$data = array(
			'category_id' => $term_id,
			'category_name' => $category->name,
			'category_slug' => $category->slug

		);
    $response = wp_remote_post(API_URL.$url, array(
			'method' => 'POST',
			'body' => $data,
		    )
		);

		if ( is_wp_error( $response ) ) {
		   $error_message = $response->get_error_message();
		   print_r($data);
		   echo "Something went wrong: $error_message";
		} 
}
function update_category_api( $term_id )
{
		$url = '/createCategory';
		$category = get_category($term_id);
		$data = array(
			'category_id' => $term_id,
			'category_name' => $category->name,
			'category_slug' => $category->slug

		);
		$response = wp_remote_post(API_URL.$url, array(
			'method' => 'POST',
			'body' => $data,
		    )
		);

}
function delete_category_api( $term_id )
{
		$url = '/deleteCategory';
		$data = array(
			'category_id' => $term_id

		);
		$response = wp_remote_post(API_URL.$url, array(
			'method' => 'POST',
			'body' => $data,
		    )
		);

}
add_action('delete_category', 'delete_category_api');
add_action('edited_category', 'update_category_api');
add_action('create_category', 'create_new_category_api', 10, 2);
// for category
// for post
function create_new_post_api( $post_id ) {
   $url = '/createPost';
   $url_test = '/testing';
   $post = get_post( $post_id );
   if ( $post->post_status == 'publish') { 
   		$category = get_the_category( $post_id );
   		$category_id = array();
   		foreach ($category as $key => $value) {
   			array_push($category_id,$value->term_id);
   		}
   		$data_test = array(
			'post' => get_post( $post_id )

		);
		$data = array(
			'post_id' => $post_id,
			'author_id' => get_post_field( 'post_author', $post_id ),
			'title' => $post->post_title,
			'post_type' => $post->post_type,
			'post_slug' => $post->post_name,
			'post_status' => $post->post_status,
			'content' => $post->post_content,
			'thumbnail' => get_the_post_thumbnail_url($post_id, 'full'),
			'menu_order' => $post->menu_order,
			'category' =>  json_encode($category_id)

		);
		$response = wp_remote_post(API_URL.$url, array(
			'method' => 'POST',
			'body' => $data,
		    )
		);
		$response = wp_remote_post(API_URL.$url_test, array(
			'method' => 'POST',
			'body' => $data_test,
		    )
		);
		
		if ( is_wp_error( $response ) ) {
		   $error_message = $response->get_error_message();
		   print_r($data);
		   echo "Something went wrong: $error_message";
		} 
	}
}

function trash_post_api( $post_id ) {

    $post = get_post( $post_id );
    $url = '/updatePost';
    $data = array(
			'post_id' => $post_id,
			'post_status' => 'trashed',
		);
    $response = wp_remote_post(API_URL.$url, array(
			'method' => 'POST',
			'body' => $data,
		    )
		);
}
function untrash_post_api( $post_id ) {

    $post = get_post( $post_id );
    $url = '/updatePost';
    $data = array(
			'post_id' => $post_id,
			'post_status' => 'publish',
		);
    $response = wp_remote_post(API_URL.$url, array(
			'method' => 'POST',
			'body' => $data,
		    )
		);
}

function delete_post_api( $post_id ){
	$url = '/deletePost';
    // We check if the global post type isn't ours and just return
    $data = array(
			'post_id' => $post_id
		);
    $response = wp_remote_post(API_URL.$url, array(
			'method' => 'POST',
			'body' => $data,
		    )
		);

    // My custom stuff for deleting my custom post type here
}
add_action( 'delete_post', 'delete_post_api' );
add_action( 'wp_untrash_post', 'untrash_post_api' );
add_action( 'wp_trash_post', 'trash_post_api' );
add_action( 'wp_insert_post', 'create_new_post_api' );
// for post


