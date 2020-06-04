<?php

namespace DareDev;

class Instagram {

	/**
	 * 1. Create a Facebook App here: https://developers.facebook.com/
	 * 2. Under Add a Product, select "Instagram / Set Up"
	 * 3. Under Products / Instagram, click on Basic Display
	 * 4. Click Create New App and create a new Instagram App
	 * 5. Add valid URLS, save changes and generate a token via the token generator as per instructions: https://developers.facebook.com/docs/instagram-basic-display-api/overview#user-token-generator
	 *
	 * @param $token
	 * @param $user // User ID can be found using the Facebook debug tool: https://developers.facebook.com/tools/debug/accesstoken/
	 * @param int $limit // Add a limit to prevent excessive calls.
	 * @param string $fields // More options here: https://developers.facebook.com/docs/instagram-basic-display-api/reference/media
	 * @param array $restrict // Available options: IMAGE, VIDEO, CAROUSEL_ALBUM
	 *
	 * @return array|mixed // Use it like that (minimal example):  \DareDev\Instagram::media(TOKEN, USER_ID);
	 */
	public static function media(
		$token,
		$user,
		$limit = 10,
		$fields = 'media_url,permalink,media_type,caption',
		$restrict = [ 'IMAGE' ]
	) {
		// The request URL. see: https://developers.facebook.com/docs/instagram-basic-display-api/reference/user
		$request_url = 'https://graph.instagram.com/' . $user . '?fields=media&access_token=' . $token;

		// We use transients to cache the results and fetch them once every hour, to avoid bumping into Instagram's limits (see: https://developers.facebook.com/docs/graph-api/overview/rate-limiting#instagram-graph-api)
		$output = get_transient( 'instagram_feed_' . $user ); // Our transient should have a unique name, so we pass the user id as an extra precaution.
		if ( false === ( $data = $output ) || empty( $output ) ) {
			// Prepare the data variable and set it as an empty array.
			$data = [];
			// Make the request
			$response      = wp_safe_remote_get( $request_url );
			$response_body = '';
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$response_body = json_decode( $response['body'] );
			}
			if ( $response_body && isset( $response_body->media->data ) ) {
				$i = 0;
				// Get each media item from it's ID and push it to the $data array.
				foreach ( $response_body->media->data as $media ) {
					if ( $limit > $i ) {
						$request_media_url = 'https://graph.instagram.com/' . $media->id . '?fields=' . $fields . '&access_token=' . $token;
						$media_response    = wp_safe_remote_get( $request_media_url );
						if ( is_array( $media_response ) && ! is_wp_error( $media_response ) ) {
							$media_body = json_decode( $media_response['body'] );
						}
						if ( in_array( $media_body->media_type, $restrict, true ) ) {
							$i ++;
							$data[] = $media_body;
						}
					}
				}
			}
			// Store the data in the transient and keep if for an hour.
			set_transient( 'instagram_feed_' . $user, $data, HOUR_IN_SECONDS );

			// Refresh the token to make sure it never expires (see: https://developers.facebook.com/docs/instagram-basic-display-api/guides/long-lived-access-tokens#refresh-a-long-lived-token)
			wp_safe_remote_get( 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $token );
			$output = $data;
		}

		return $output;

	}
}