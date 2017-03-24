<?php
	/**
	 * Saves the manga page to the project
	 * 
	 * @param string $url url of the page
	 * @param string $base_path path to the manga chapter
	 * @param int $page_no page number (default value is 1)
	 *
	 * @return void
	 */
	function save_manga_page($url, $base_path, $page_no=1) {
		$img = $base_path . '-' . ($page_no<10? '0'.$page_no:$page_no) . '.jpg';
		file_put_contents($img, file_get_contents($url));
	}
	
	/**
	 * Gets the url of the manga page to be used for saving
	 *
	 * @param string $url url of the manga chapter
	 * @param string $contents this will hold the html content of the page
	 * @param string $site_source 1 of 2 supported manga source
	 *
	 * @return string returns the manga page url
	 */
	function get_image_url($url, $contents=FALSE, $site_source='mangapanda') {
		if(!$contents)
			if($site_source == 'mangahere')
				$contents = file_get_contents($url.'.html');
			else
				$contents = file_get_contents($url);
		
		if($site_source == 'mangahere')
			$start = strpos($contents, 'src="', strpos($contents, 'id="viewer"'))+5;
		else
			$start = strpos($contents, 'src="', strpos($contents, 'id="imgholder"'))+5;
		return substr($contents, $start, strpos($contents, '"', $start)-$start);
	}

	/**
	 * Generate the main content of the site, this also caters the fetching of the manga chapter
	 */
	function generate_content() {
		if(!empty($_GET['get_manga'])) {
			preg_match('/\/([\w-]+)\/(c?[0-9\.]+)/', $_GET['get_manga'], $matches);
			
			if($matches) {
				if(strpos($_GET['get_manga'], 'mangapanda.com') !== FALSE) {
					$anime_title = ucwords(str_replace(array('-','_'), ' ', $matches[1]));
					$chapter = $matches[2];
					$base_filename = $matches[1].'-'.$chapter;
					$site_source = 'mangapanda';
				}
				elseif(strpos($_GET['get_manga'], 'mangahere.co') !== FALSE) {
					$anime_title = ucwords(str_replace(array('-','_'), ' ', $matches[1]));
					$chapter = (float) str_replace('c','',$matches[2]);
					$base_filename = $matches[1].'-'.$chapter;
					$site_source = 'mangahere';
				}
				else
					die('Site not supported');
			}
			
			$anime_path = $anime_title.'/'.$chapter;
			
			if( !file_exists($anime_title) )
				mkdir($anime_title);
			
			if( !file_exists($anime_title.'/'.$chapter) ) {
				mkdir($anime_path);
			
				$contents = file_get_contents($_GET['get_manga']);
				$base_url = $_GET['get_manga'];
				
				if($site_source == 'mangahere') {
					$total_pages = (int) str_replace(' ','', substr($contents, strpos($contents, 'var total_pages = ')+18, 3));
					
					save_manga_page(get_image_url($base_url, $contents, $site_source), $anime_path . '/' . $base_filename);
					
					for($y=2; $y<=$total_pages ;$y++)
						save_manga_page(get_image_url($base_url.'/'.$y, FALSE, $site_source), $anime_path . '/' . $base_filename, $y);
				}
				else {
					$total_pages = (int) substr($contents, strpos($contents, '/div', strpos($contents, 'id="selectpage"'))-3, 5);
				
					save_manga_page(get_image_url($base_url, $contents, $site_source), $anime_path . '/' . $base_filename);
					
					for($y=2; $y<=$total_pages ;$y++)
						save_manga_page(get_image_url($base_url.'/'.$y, FALSE, $site_source), $anime_path . '/' . $base_filename, $y);
				}
			}
			
		}
		// This generates the actual manga chapter pages
		elseif(!empty($_GET['view'])) {
			$images = scandir($_GET['view'].'/'); 
			
			for($y=2; $y < sizeof($images) ;$y++)
				echo '<div><img src="'.$_GET['view'].'/'.$images[$y].'" id="'.($y-1).'" /></div>';

			$chap = explode('/', $_GET['view']);
			if( file_exists( $chap[0] .'/'. sprintf('%03d', $chap[1]+1) ) )
				echo '<div><h2><a href="?view='. $chap[0] .'/'. sprintf('%03d', $chap[1]+1) .'">NEXT CHAPTER</a></h2></div>';
		}
	}
?>

<html>
	<head>
		<title><?php echo !empty($_GET['view'])? str_replace('/', ' - ', $_GET['view']):'MANGA MO DIRA'; ?></title>
		
		<link href="_assets/css/jquery-ui.css" rel="stylesheet" type="text/css" />
		<link href="_assets/css/styles.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="_assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="_assets/js/jquery-ui.min.js"></script>
	</head>
	<body>
	
		<div id="left-menu">
			<h1><a href="index.php">MANGA TA BAI</a></h1>
			<div>Supports: <i>mangapanda.com | mangahere.co</i></div>
			<?php
				// We get the current manga viewed if there's any
				$manga = '';
				if(!empty($_GET['view'])) {
					$arr = explode('/',$_GET['view']);
					$manga = $arr[0];
				}
					
				$dirs = scandir('.');
				$current_item = 0;
				$set_active = "'none'";
				echo '<div id="content" class="content">';

				// First 2 of dir are the current and parent folders, last 2 are the _assets and index.php so we skip them
				for($y=2; $y < sizeof($dirs)-2 ;$y++) {
					$title = $dirs[$y];

					// Skip the file if it has a . at the start, usually for .git and .gitignore, including README.md
					if(preg_match('/^\..+/', $title) || $title == 'README.md' )
						continue;
					if($title == $manga)
						$set_active = $current_item;

					echo '<h3>'.$title.'</h3><div>';
					$chapters = scandir($dirs[$y].'/',1);
					for($z=0; $z < sizeof($chapters)-2; $z++)
						echo '<p><a href="?view='.$title.'/'.$chapters[$z].'">Chapter '.$chapters[$z].'</a></p>';
					echo '</div>';

					$current_item++;
				}

				if(!$current_item)
					echo '<p>No Manga saved.</p>';

				echo '</div>';
			?>
		</div>
		
		<div id="right-area">
			<?php generate_content(); ?>
		</div>

		<script type="text/javascript">
			var active_sidebar = <?php  echo $set_active; ?>;
		</script>
		<script type="text/javascript" src="_assets/js/main.js"></script>
	</body>
</html>