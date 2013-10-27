<div id="main_column">

<?php
	$searchterms = $_POST['searchterms'];
	$search_what = $_POST['search_what'];
	if ( $search_what == "friends" ) {
		echo '<div class="search_title"><h2>Search: People</h2></div>';
		echo '<div class="search_form">';
		echo '<form action="' . $communitypi->getSetting('baseurl') . 'search" method="post">';
		echo '<input type="hidden" name="search_what" value="friends" /><input type="text" name="searchterms" value="' . $searchterms . '" />';
		echo '<input type="submit" value="Search" /></form></div><div class="clear"></div>';
		
		$results = $communitypi->searchPeople($searchterms);
		if ($results) {
			echo '<div class="search_results">';
			foreach ($results as $id) {
				$imgurl = $communitypi->getProfileImage($id, 60);
				echo '<a href="' . $communitypi->getUserProfileURL($id) . '"><div class="search_result">';
					echo '<div class="image"><img src="' . $imgurl . '" /></div>';
					echo '<div class="name">' . $communitypi->getProfileInfo($id, "name") . '</div>';
				echo '</div></a><div class="clear"></div>';
			}
			echo '</div>';
		}
		
	} elseif ( $search_what == "groups" ) {
		echo '<div class="search_title"><h2>Search: Groups</h2></div>';
		echo '<div class="search_form">';
		echo '<form action="' . $communitypi->getSetting('baseurl') . 'search" method="post">';
		echo '<input type="hidden" name="search_what" value="groups" /><input type="text" name="searchterms" value="' . $searchterms . '" />';
		echo '<input type="submit" value="Search" /></form></div><div class="clear"></div>';
		
		$results = $communitypi->searchGroups($searchterms);
		if ($results) {
			echo '<div class="search_results">';
			foreach ($results as $row) {
				echo '<a href="' . $communitypi->getSetting('baseurl') . 'group/' . $row['slug'] . '"><div class="search_result">';
					echo '<div class="image"><img src="' . $row['image'] . '" height="60px" width="60px"/></div>';
					echo '<div class="name">' . $row['name'] . '</div>';
				echo '</div></a><div class="clear"></div>';
			}
			echo '</div>';
		}
	}
?>

	
	
	
	
	
	
	
	<?php
	
	if ($searchterms) {
		
	}
	?>
</div>