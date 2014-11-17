<?php 
// get and return the postings that te user wants to display

$strDisplayPost = "";//holds the post being display

//checks if the sort is empty if not then do the search of the post 
if(empty($_REQUEST['Sort']) == false)
{
	$intCategory = $_POST['Category'];//holds the category of the post
	$strTag = urldecode($_POST['Tag']);//holds the tag of the post
	$strFormat = $_POST["Format"];//holds the format of the post
	$strPostStatus = $_POST["PostStatus"];//holds the status of the post
	$strSearchText = $_POST["SearchText"];//holds the search text
	$strPostFormat = $_POST["PostFormat"];//holds the post format
	$intCurrentPage = $_POST['GoToPage'];//holds the Current Page
	$intDisplayNumberOfPostPerPage = $_POST['DisplayNumberOfPostPerPage'];//holds the number of post that will be display per page
	$strDisplayBodyID = $_POST['Body'];//holds the div id that can be sued to change the body in real time
	$boolDisplayPaging = $_POST['DisplayPaging'];//holds if the user whats to display the paging 
	$options;//holds hte options that is used in the posting query
	$arrSort = split(' ',$_POST['Sort']);//holds the split of the Sort [0] what to sort and [1] 'DESC' OR 'ASC'

	//checks to make sure that arrSort as 2 items
	if(count($arrSort))
	{
		//checks if the [1] is either 0 for ASC, or 1 for DESC
		if($arrSort[1] == "1")
			$strSort = "DESC";
		else
			$strSort = "ASC";
		
		//checks which field to sort
		switch($arrSort[0])
		{
			case "1":
				$strSortBy = "title";
			break;
			default:
				$strSortBy = "date";
			break;
		}//end of switch
		
		//sets the options of the posttings
		$options = array('method' => 'loop',//'loop','array' - default = 'loop'
		  'posts_per_page' => $intDisplayNumberOfPostPerPage, //default = 5
		  'paged' => $intCurrentPage,
		  'orderby' => $strSortBy, //'author','date','title','modified','parent','id','rand','comment_count'
		  'order' => $strSort //'ASC','DESC'
		);
		
		//checks if there is anything insted $strPostStatus if so then 
		//then display only those status
		if($strPostStatus != "")
			$options['post_status'] = $strPostStatus;
			
		//checks if there is search text that is needed
		if($strSearchText != "")
		{
			$options['s'] = str_replace(" ","+",$strSearchText);
			//$options['post_type'] = "post";
		}//end of if
				
		//checks if there is any category if so then add it to the options to limit it
		if($intCategory != "")
			//sets the option for the category
			$options['cat'] = $intCategory;
		else
			$options['cat'] = "";
		
		//checks if there is a tag or format of a post that needs to be change
		if($strTag != "")
			//sets the option for the tag
			$options['tag'] = $strTag;
		else if($strFormat != "")
		{
			//sets the option for the format
			$options['tax_query'] = array(
			  array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-'.$strFormat
			  )
			);
		}//end of else if
	
		//adds the filter to the where in order to remove all events that should not be in there
		add_filter('posts_where', 'whatsNewQuery'); 
	
		//resets the getting post to get the other post for this archive
		$queryPost = new WP_Query($options);
		
		//removes the filter on the WP_Query in order not to interfear with the other Queries
		remove_filter('posts_where', 'whatsNewQuery');
		
		//checks if there is any postings to display
		if ($queryPost->have_posts())
		{
			//goes around displaying the format the the use wanted to display checking for displaying
			while ($queryPost->have_posts())
			{
				//gets the next post
				$queryPost->the_post();
				
				$postCurrent = get_post(get_the_ID());//holds the current post data as this is easier to change the layout as need
				//sets the post format for display 
				$strDisplayPost .= whatsNewDisplayPostings($postCurrent,$strPostFormat);
			}//end of while loop
			
			//checks if the paging should be display
			if($boolDisplayPaging == true || $boolDisplayPaging == "on")
				//does the paging 
				$strDisplayPost .= whatsNewDisplayPaging($intDisplayNumberOfPostPerPage, $queryPost->found_posts, $intCurrentPage, $strDisplayBodyID, $boolDisplayPaging, $strPostStatus, $strTag, $strFormat, $strSearchText,$strPostFormat);
		}//end of if
	}//end of if
}//end of if

//sets the query back to when the page loads
wp_reset_query();

echo $strDisplayPost;
?>