<?php 

if( !function_exists( 'redirect' ) ){
	function redirect($url){
		$content  = '<script>
		window.location = "'. $url .'";
		</script>';
		return $content;
	}
}


?>