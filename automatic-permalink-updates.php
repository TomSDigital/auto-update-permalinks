<?php
/**
 * Plugin Name: Automatic Permalink Updates
 * Description: Automatically updates a post's permalinks when its title is changed.
 * Version: 1.0
 * Author: Somebody Digital
 * Author URI: https://www.somebodydigital.com
 * License: GNU General Public License version 2
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 **/


add_filter( 'wp_insert_post_data', 'custom_slug_change', 50, 2 );
function custom_slug_change( $data, $postarr ) {
    //Check for the  post statuses you want to avoid
    if ( !in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft' ) ) ) {           
		$data['post_name'] = sanitize_title( $data['post_title'] );
    }
    return $data;
}

add_action('edit_form_after_editor', 'update_slug_script');

function update_slug_script(){
	?>
	<script type="text/javascript">
		function string_to_slug(str){
			str = str.replace(/^\s+|\s+$/g, ''); // trim
			str = str.toLowerCase();
			// remove accents, swap ñ for n, etc
			var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
			var to   = "aaaaeeeeiiiioooouuuunc------";
			for (var i=0, l=from.length ; i<l ; i++){
				str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
			}
			str = str.replace('.', '-') // replace a dot by a dash 
			.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
			.replace(/\s+/g, '-') // collapse whitespace and replace by a dash
			.replace(/-+/g, '-'); // collapse dashes
			return str;
		}
		function auto_update_slug(e){
			const titleValue = e.target.value;
			const convertedSlug = string_to_slug(titleValue);
			const editablePostName = document.getElementById('editable-post-name');
			const editablePostNameFull = document.getElementById('editable-post-name-full');
			if(editablePostName){
				editablePostName.innerText = convertedSlug;
			}
			if(editablePostNameFull){
				editablePostNameFull.innerText = convertedSlug;
			}
		}
		const titleField = document.getElementById('title');
		if(titleField){
			titleField.addEventListener('change', auto_update_slug, false);
		}
	</script>
<?php
}