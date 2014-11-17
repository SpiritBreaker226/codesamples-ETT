<?php
/*

	Custom Funcations For ETT
	
*/

//checks if the user is an admin if so then display the bar
if (!current_user_can('administrator'))
	//Disable the Admin Bar
	show_admin_bar(false);
				
add_action('init','adminInit');
	
function adminInit()
{
	global $userdata, $wp_roles;
	
	get_currentuserinfo();//finds the current users data
	
	//checks if the users role is an author if so then remove 
	//the publishing rights for them
    if ($userdata->role == "author") 
	{
		//removes the ability to publish for this role
	    $wp_roles->remove_cap('author', 'publish_pages');
	    $wp_roles->remove_cap('author', 'publish_posts');
    }//end of if
	else if ($userdata->role == "contributor")
	{		
		//add the ability to see private post
        $wp_roles->add_cap('contributor', 'read_private_posts');
	}//end of if else
}//end of adminInit()

//creates the admin menu location of the admin pages

add_action('admin_menu', 'adminMenuPage');

function adminMenuPage() 
{
	//Main menu tab
	add_menu_page('ETT', '<span style=" font-size:12px">ETT</span>', 'administrator', 'ettAdmin', 'adminMenuPageSchool', get_bloginfo('template_url')."/images/ETTLogoSmallIcon.png");
	
		//School	
		add_submenu_page('ettAdmin', 'ETT - School', 'School', 'administrator', 'ettschool', 'adminMenuPageSchool');
		
		//Region
		add_submenu_page('ettAdmin', 'ETT - School', 'Region', 'administrator', 'ettregion', 'adminMenuPageRegion');
}//end of adminMenuPage() 

//creates the different admin pages

function adminMenuPageSchool() 
{
	$tableSchool = new customTableListTableSchool();
    $tableSchool->prepare_items();

    $message = '';
	
    if ('delete' === $tableSchool->current_action())
        $message = '<div class="updated below-h2" id="message"><p>'.sprintf(__('Items deleted: %d', 'custom_table_example'), count($_REQUEST['id'])).'</p></div>';
    ?>
	<div class="wrap">
    	<div class="icon32 icon32-posts-post" id="icon-edit">
        	<br />
        </div>
	    <h2><label>School</label> <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=ettschool');?>">Add new</a>
	    </h2>
    	<?php echo $message; ?>
        <form id="persons-table" method="GET">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <?php $tableSchool->display(); ?>
        </form>
	</div>    
	<?php
}//end of adminMenuPageSchool()

function adminMenuPageRegion() 
{
	echo "Region";
}//end of adminMenuPageRegion()

//creates the alphabet search for user search and page seach
function alphabetSearch($arrTermUser, $queryPost, $strLetter, $intDisplayNumberOfPostPerPage, $strSortBy = "", $strSort = "", $intParentID = 0, $boolDisplayPaging = false, $intTermID = 0, $strDisplayBodyID = "", $strDatabaseTableName = "", $boolDisplayFirstName = false, $strExeFormat = "", $boolUseDatabase = false, $boolDisplayTitle = false, $strUserRole = 'author')
{
	$strDisplayAlphabet = "";//holds the alphabet being display
	$strOnClick = "";//holds the on click event for the search fliter
	
	$arrABC = array(
		'a' => '<div class="spanExeABC spanExePostingDisable customLeft">a</div>',
		'b' => '<div class="spanExeABC spanExePostingDisable customLeft">b</div>',
		'c' => '<div class="spanExeABC spanExePostingDisable customLeft">c</div>',
		'd' => '<div class="spanExeABC spanExePostingDisable customLeft">d</div>',
		'e' => '<div class="spanExeABC spanExePostingDisable customLeft">e</div>',
		'f' => '<div class="spanExeABC spanExePostingDisable customLeft">f</div>',
		'g' => '<div class="spanExeABC spanExePostingDisable customLeft">g</div>',
		'h' => '<div class="spanExeABC spanExePostingDisable customLeft">h</div>',
		'i' => '<div class="spanExeABC spanExePostingDisable customLeft">i</div>',
		'j' => '<div class="spanExeABC spanExePostingDisable customLeft">j</div>',
		'k' => '<div class="spanExeABC spanExePostingDisable customLeft">k</div>',
		'l' => '<div class="spanExeABC spanExePostingDisable customLeft">l</div>',
		'm' => '<div class="spanExeABC spanExePostingDisable customLeft">m</div>',
		'n' => '<div class="spanExeABC spanExePostingDisable customLeft">n</div>',
		'o' => '<div class="spanExeABC spanExePostingDisable customLeft">o</div>',
		'p' => '<div class="spanExeABC spanExePostingDisable customLeft">p</div>',
		'q' => '<div class="spanExeABC spanExePostingDisable customLeft">q</div>',
		'r' => '<div class="spanExeABC spanExePostingDisable customLeft">r</div>',
		's' => '<div class="spanExeABC spanExePostingDisable customLeft">s</div>',
		't' => '<div class="spanExeABC spanExePostingDisable customLeft">t</div>',
		'u' => '<div class="spanExeABC spanExePostingDisable customLeft">u</div>',
		'v' => '<div class="spanExeABC spanExePostingDisable customLeft">v</div>',
		'w' => '<div class="spanExeABC spanExePostingDisable customLeft">w</div>',
		'x' => '<div class="spanExeABC spanExePostingDisable customLeft">x</div>',
		'y' => '<div class="spanExeABC spanExePostingDisable customLeft">y</div>',
		'z' => '<div class="spanExeABC spanExePostingDisable customLeft">z</div>',
	);//holds the alphabet of all of the exeectuives first or last name
	
	//sets the link for to filter out the search
	//checks seaction the search is going with user or pages
	//if pages then use the sendLading if the users then sendExecutives
	if($arrTermUser == false)
		$strOnClick = "javascript:sendLoadingPage(&quot;".get_bloginfo('template_url')."/PurePHP/GetLoadingPage.php&quot;, getDocID(&quot;divLandingPage&quot;), &quot;%s&quot;, &quot;".$strSortBy."&quot;, &quot;".$strSort."&quot;, ".$intDisplayNumberOfPostPerPage.", ".$intParentID.", ".$boolDisplayPaging.", 1);";
	else			
		$strOnClick = "javascript:getDocID(&quot;txtSearchBox&quot;).value = &quot;&quot;;sendExecutives(&quot;".get_bloginfo('template_url')."/PurePHP/GetExecutives.php&quot;, getDocID(&quot;".$strDisplayBodyID."&quot;), &quot;%s&quot;, getDocID(&quot;SerachOption&quot;), ".$intDisplayNumberOfPostPerPage.", null, ".$boolUseDatabase.", ".$intTermID.", &quot;".$strDatabaseTableName."&quot;, ".$boolDisplayFirstName.", &quot;".$strExeFormat."&quot;, null, null, null, ".$boolDisplayPaging.", ".$boolDisplayTitle.", &quot;".$strUserRole."&quot;, 1);";

	//goes around for each user that is in this Term 
	//and check the first letter of either the last or first name in order to use it for search
	for($intUserIndex = 0;count($arrTermUser) > $intUserIndex || $queryPost != false && $queryPost->have_posts();$intUserIndex++)
	{
		$strFirstChar = "";//holds the first char of either the first or last name
		$sectionDetails = false;//holds the details of the section
		
		//checks seaction the search is going with user or pages
		//if pages then use the sendLading if the users then sendExecutives
		if($arrTermUser == false)
		{
			//gets the next post
			$queryPost->the_post();
			
			$sectionDetails = get_post(get_the_ID());//holds the current post data as this is easier to change the layout as need
			$strFirstChar = strtolower(substr($sectionDetails->,0,1));//holds the first char of committee name
		}//end of if
		else		
		{	
			//gest the users details for this term
			$sectionDetails = $arrTermUser[$intUserIndex];
		
			//checks if the funcation is using the database or user's meta tags
			if($boolUseDatabase == false)
			{			
				//checks if the user wants to use first name or last
				if($boolDisplayFirstName == true) 
					$strFirstChar = strtolower(substr($sectionDetails->,0,1));
				else
					$strFirstChar = strtolower(substr($sectionDetails->,0,1));
			}//end of if
			else
				$strFirstChar = strtolower(substr($sectionDetails->,0,1));
		}//end of else
		
		//checks if this is the letter that will be selected
		if($strLetter == $strFirstChar)
			$arrABC[$strFirstChar] = str_replace("spanExePostingDisable","spanExeSelected divPostPagingCurrent",$arrABC[$strFirstChar]);
		else			
			$arrABC[$strFirstChar] = str_replace(">".$strFirstChar."</div>","><a title='Goto ".$strFirstChar."' href='javascript:void(0)' onclick='".sprintf($strOnClick,$strFirstChar)."' class='aExeABC'>".$strFirstChar."</a></div>",str_replace("spanExePostingDisable","spanExePosting",$arrABC[$strFirstChar]));
	}//end of for loop
		
	//checks if there is another item that is selctectd if so then add in the link to the for the
	if(empty($strLetter) == false && $strLetter != "All")
		//displays the all exe as this is the first item to be selected
		$strDisplayAlphabet .= "<div class='spanExeABC spanExePosting customLeft' id='divExeABCAll'>".
			"<a title='Goto All' href='javascript:void(0)' onclick='".sprintf($strOnClick,"All")."' class='aExeABC'>All</a>".
		"</div>";
	else
		//displays the all exe as this is the first item to be selected
		$strDisplayAlphabet .= "<div class='spanExeABC spanExeSelected customLeft' id='divExeABCAll'>All</div>";

	//goes around for each of the aplphabet and displays them
	foreach ($arrABC as $ABC) 
	{
		$strDisplayAlphabet .= $ABC;
	}//end of for loop

	$strDisplayAlphabet.= "<div class='customFooter'></div>";
	
	return $strDisplayAlphabet;
}//end of alphabetSearch()

//Displays the path to the page
function breadCrumbsDisplay()
{
	$strDisplayPath = "<div id='divBreadCrumbsDisplay'>";//holds the path being display
	
	//gets the current page
	if(is_page() || is_author() || is_single())
	{
		$intPageCurrentID = 0;//holds the current page id that the user is on
		
		//checks if the page is a author page and if so then set it to the Executives page as 
		//this is the default for the aother page
		if(is_author())
			$intPageCurrentID = 402;
		else
			//gets the current page id and its ancestors pages that came before it
			$intPageCurrentID = get_the_ID();
       
		$pageCurrentDetails = get_page($intPageCurrentID);//holds the page details the current page
        
		$arrPageAncestors = get_ancestors($intPageCurrentID, 'page');
		
		if (is_single())
            $strDisplayPath .= "<a href='".get_permalink(94)."' class='aBreadCurmbs'>Members<span class='spanBreadCurmbsArrow'>&gt;</span></a><a href='".get_permalink(1092)."' class='aBreadCurmbs'>Forums<span class='spanBreadCurmbsArrow'>&gt;</span></a>";
        
		//goes around finding the path to this page
		for($intAncestorsLevel = (count($arrPageAncestors) - 1);$intAncestorsLevel > -1; $intAncestorsLevel--)
		{
			$pageAncestorDetails = get_page($arrPageAncestors[$intAncestorsLevel]);//holds the page details for this Ancestors
			$intEventID = $_GET['ee'];//holds the event id for the registractoin page
													
			//checks if the page should be display as it may not be publish yet
			if($pageAncestorDetails->post_status == "publish")
			{
                $strAltTitle = get_post_meta($pageAncestorDetails->, '_aioseop_title', true);//holds the alt title
				$strDisplayPath .= "<a href='".get_permalink($arrPageAncestors[$intAncestorsLevel])."' class='aBreadCurmbs'>";
				//checks if there is a title for the page and if so then use that
				if(empty($strAltTitle) == true)
					$strDisplayPath .= $pageAncestorDetails->post_title;
				else
					$strDisplayPath .= $strAltTitle;
									
				$strDisplayPath .= "<span class='spanBreadCurmbsArrow'>&gt;</span></a>";
			}//end of if
			
			//checks if there is a event id to use
			if(!isset($intEventID) && isset($_REQUEST['']) && $_REQUEST[''] != '')
				$intEventID = $_REQUEST[''];
			
			//checks if this is the registration page as it uses a event name in bettween the page
			//and the parent if so then adds the event name here
			//checks if there is a event id to use
			if(isset($intEventID) && $intAncestorsLevel == 0)
			{
				//gets the Category for this posting if it has one
				$eventDetails = getEventCate($intEventID, true); 
				
				$strDisplayPath .= "<a href='".site_url()."/events-pd/event-details?ee=".$eventDetails[0]->."' class='aBreadCurmbs'>".$eventDetails[0]->."<span class='spanBreadCurmbsArrow'>&gt;</span></a>";
			}//end of if
		}//end of foreach
		
		//checks if the page is the aother page and if so displays the users first and last anme
		if(is_author())
		{
			$authorCurrent = false;//holds the current author details
			
			//checks which way the user is trying to get the authors data and then 
			//gets it that way
			if(isset($_GET['author_name'])) 
				$authorCurrent = get_user_by('slug', $author_name);
			if(isset($_GET['author'])) 
				$authorCurrent = get_userdata(intval($_GET['author']));
			
			//displays the page where the author is and first and last name
			$strDisplayPath .= "<a href='".get_permalink($intPageCurrentID)."' class='aBreadCurmbs'>".$pageCurrentDetails->."<span class='spanBreadCurmbsArrow'>&gt;</span></a>".
			"<span class='spanBreadCurmbsCurrentPage'>".$authorCurrent->." ".$authorCurrent->."</span>";
		}//end of else if
		else
			//displays the page name as the last part of the breadcurmb
			$strDisplayPath .= "<span class='spanBreadCurmbsCurrentPage'>".$pageCurrentDetails->."</span>";
	}//end of if
    	
	return $strDisplayPath."</div>";
}//end of breadCrumbsDisplay()

//changes the main CSS to the CSS folder in order to be more orginized

add_filter('stylesheet_uri', 'changeCSS');

function changeCSS() 
{
	return get_bloginfo('template_url').'/CSS/style.css';
}//end of changeCSS()

//checks if the event is allow to display
function checkEventStatus($eventDetailsCate, $intPostID)
{
	$boolCanDisplay = false;//holds if the event can display
	
	//checks if this is an event if so then do checks to see if it can be display
	if($eventDetailsCate != false)
	{
		//checks if event status is either in draft or deleted and not display this posting
		//only if it is an event post
		if($eventDetailsCate[0]-> == 'A')
		{

			//checks if this is a recurrence id
			if($eventDetailsCate[0]-> > 0)
			{
				$recurrenceData = getDataColoumn("", "", "WHERE  = ".$eventDetailsCate[0]->." AND  >= '".date('Y-n-h')."'");//holds all of the recurrence of this event

				//checks if the post id is the same as the one for the 
				//event that will be displaying if so then display the date
				if($recurrenceData != false && $recurrenceData[0]-> == $intPostID)
					$boolCanDisplay = true;
			}//end of if
			//checks if the end date is still coming	
			else if(strtotime($eventDetailsCate[0]->) >= strtotime(date('Y-m-j')))
				$boolCanDisplay = true;
		}//end of if
	}//end of if
	else
		$boolCanDisplay = true;

	return $boolCanDisplay;
}//end of checkEventStatus()

//checks if the user can download this file
function checkFileAccess($strUserFileRoles)
{
	$boolCanDownload = true;//holds if the user can download this file or not
	
	//checks if this file is locked and if so if the user is log in or not
	if(empty($strUserFileRoles) == false)
	{
		//checks if the user is even logged in
		if(!is_user_logged_in())
			$boolCanDownload = false;
		else 
		{
			$userCurrent = wp_get_current_user();//holds the current user data
	
			//goes around for each role the file can access checks if 
			//this user can access it 
			for($intFileIndex = 0;count($userCurrent->roles) > $intFileIndex;$intFileIndex++)
			{
				//checks if the user can access this file
				//if so then skip the rest of the loop
				if(strpos($strUserFileRoles,$userCurrent->roles[$intFileIndex]) === false && empty($userCurrent->roles[$intFileIndex]) == false)
					$boolCanDownload = false;
			}//end of for loop
		}//end of else		
	}//end of if

	return $boolCanDownload;
}//end of checkFileAccess()

//creates the post for all Teacher Resources files 
//as it is too much work for the user to do this on the own
function createPostTeacherResources()
{
	$arrFilePost = getDataColoumn("", "", "WHERE  = 0 AND  != ''");//holds the all of the files for the Teacher Resources that do not have post attach to them

	//goes around for each Teacher Resources that do not have post attach to them
	//and creates a post then updates that resource in the database
	foreach($arrFilePost as $filePost)
	{
		$arrTagCate = explode(',', $filePost->);//holds the tags that have category name
		$arrCate = array();//holds all of the cateogries that can be added to the posting
		
		//goes around checks if the value in $arrCate is a category
		//if so then adds it to the array to be added to teh new posting
		for($intIndex = 0;count($arrTagCate) > $intIndex;$intIndex++)
		{
			$cateDetails = get_term_by('name', $arrTagCate[$intIndex], 'category');//holds the details for this cateogry
			//checks if the cateogry exist in the database if so then add the id to the arrCate
			if($cateDetails != false)
				$arrCate[] = $cateDetails->;
		}//end of for loop

		//checks if the user has selected this item
		if(count($arrCate) > 0)
		{
			//creates a post for this File
			$intPostID = wp_insert_post(array(
				'post_title'    => $filePost->,
				'post_content'  => $filePost->,
				'post_date'     => $filePost->,
				'tags_input'    => $filePost->,  
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_author'   => 1,
			));
						
			//checks if the posting is inseted into the database
			if($intPostID != false)
			{
				//sets the post format of this post
				set_post_format($intPostID, 'link');
				
				//adds the cateogries to this post
				wp_set_post_categories($intPostID, $arrCate);

				//updates the Teacher Resources file in the database witht he post id
				updateTeacherResourcesPostID($filePost->, $intPostID);
			}//end of if
		}//end of if
	}//end of foreach
}//end of createPostTeacherResources()

//updates the custom user fields for the site

add_action('personal_options_update', 'customActionProfileFields');
add_action('edit_user_profile_update', 'customActionProfileFields');

function customActionProfileFields($intUserID)
{
	//checks if the user is allowed to edit
	if (!current_user_can('edit_user', $intUserID)) 
		return false;
		
	$regionData = getDataColoumn("*", "", "Order by ");//holds all of the regions in the database
	$strUpdateData = "";//holds the update data
	$strSchoolID = $_POST['school'];
	
	//checks if strSchoolID is other meaning that the user enter a other school
	if($strSchoolID == "Other")
		//sets what the user has enter into other school
		$strSchoolID = $_POST['txtOtherSchool'];

	//updates the user meta tags
	update_usermeta($intUserID, 'school', $strSchoolID);
    update_usermeta($intUserID, 'TDSB_Email', $_POST['TDSB_Email']);
	update_usermeta($intUserID, 'ettposition', $_POST['ettposition']);

	//removes all of the users ids from ett_section_link_executive as to updated it with the
	//newal selected sections
	deleteAddExecutiveTable($intUserID);

	//goes around checking if the user selected this data all of the region in the database
	foreach($regionData as $region)
	{
		$strID = "region".$region->;//holds the id of the checkedbox
		
		//checks if the user has selected this item
		if($region-> == $_POST[$strID])
		{
			$strUpdateData .= $region->.",";

			//adds the uses sections to the link table
			deleteAddExecutiveTable($intUserID, $region->);
		}//end of if
	}//end of foreach
	
	//checks to see if there is any data and if so then remove the ,
	//in order to do string split currently
	if(empty($strUpdateData) == false)
		//removes the , at the end of $strUpdateData
		$strUpdateData = substr($strUpdateData,0,strlen($strUpdateData) - 1);
	
	update_usermeta($intUserID, 'ettregion', $strUpdateData);
	
	//resets $strUpdateData for the next checkboxes
	$strUpdateData = "";
	$committeeData = getDataColoumn("*", "", "Where  = 92 and  = 'publish' Order by ");//holds all of the schools in the database
	
	//goes around checking if the user selected this data all of the committee in the database
	foreach($committeeData as $committee)
	{
		$strID = "committee".$committee->;//holds the id of the checkedbox

		//checks if the user has selected this item
		if($committee-> == $_POST[$strID])
			$strUpdateData .= $committee->.",";
	}//end of foreach

	//checks to see if there is any data and if so then remove the ,
	//in order to do string split currently
	if(empty($strUpdateData) == false)
		//removes the , at the end of $strUpdateData
		$strUpdateData = substr($strUpdateData,0,strlen($strUpdateData) - 1);

	update_usermeta($intUserID, 'ettcommittee', $strUpdateData);
}//end of customActionProfileFields()

//creates custom fields in the user field section

add_action('show_user_profile', 'customProfileFields');
add_action('edit_user_profile', 'customProfileFields');

//displays the event days and the Venue as it has been come more complex 
//inside on the site 
function displayEventDateVenue($intEventID, $strEventDateClass = "lblEventDate", $strVenueClass = "lblEventPlace",$boolIsEvent = true, $boolVenueDisplay = false, $boolEventDisplay = false, $boolTimeDisplay = false)
{
	$venueEvent = getEventVenue($intEventID, $boolIsEvent);//holds the Venue for this event
	$eventDetails = getEventCate($intEventID, $boolIsEvent);//holds the Details for this event
	$eventTime = getDataColoumn(", ", "", "Where  = ".$eventDetails[0]->);//holds the times for this event
	$arrDate = explode("-",$eventDetails[0]->);//holds the event start date 0 = Year 1 = Month 2 = Day
	$arrEndDate = explode("-",$eventDetails[0]->);//holds the event end date 0 = Year 1 = Month 2 = Day
    $strDisplayDateVenue = "";//holds what will be display for this events Mata

	//checks if this Event should be display
	if($boolEventDisplay == false)
	{
		//checks if there is at count 3 length
		if(count($arrDate) == 3)
		{
			$strDisplayDate = esc_html(date("F j, Y",mktime(0, 0, 0, $arrDate[1], $arrDate[2], $arrDate[0])));//holds the date that will be display in the title
			
			//checks if the End date in the same month and not in a different year/month
			if($arrDate[1] == $arrEndDate[1] && $arrDate[0] == $arrEndDate[0] && $arrDate[2] != $arrEndDate[2])
			{
				$dateStart = date("F j",mktime(0, 0, 0, $arrDate[1], $arrDate[2], $arrDate[0]));//holds the start date
				$dateEnd = date("-j, Y",mktime(0, 0, 0, $arrEndDate[1], $arrEndDate[2], $arrEndDate[0]));//holds the end date
		
				//combines the start and end date to display to the user
				$strDisplayDate = esc_html($dateStart).esc_html($dateEnd);
			}//end of if
			//checks if the End date in the different month and not in a different year
			else if($arrDate[0] == $arrEndDate[0] && $arrDate[1] != $arrEndDate[1])
			{
				$dateStart = date("F j",mktime(0, 0, 0, $arrDate[1], $arrDate[2], $arrDate[0]));//holds the start date
				$dateEnd = date("-F j, Y",mktime(0, 0, 0, $arrEndDate[1], $arrEndDate[2], $arrEndDate[0]));//holds the end date
				
				//combines the start and end date to display to the user
				$strDisplayDate = esc_html($dateStart).esc_html($dateEnd);
			}//end of if
			//checks if the End date in the different year
			else if($arrDate[0] != $arrEndDate[0])
			{
				$dateStart = date("F j, Y",mktime(0, 0, 0, $arrDate[1], $arrDate[2], $arrDate[0]));//holds the start date
				$dateEnd = date("-F j, Y",mktime(0, 0, 0, $arrEndDate[1], $arrEndDate[2], $arrEndDate[0]));//holds the end date
				
				//combines the start and end date to display to the user
				$strDisplayDate = esc_html($dateStart).esc_html($dateEnd);
			}//end of if
											
			//displays the date of the event
			$strDisplayDateVenue .= "<label class='".$strEventDateClass."'>".$strDisplayDate." </label>";
		}//end of if
	}//end of if
	
	//checks if this Time should be display
	if($boolTimeDisplay == false)
	{
		//goes around for each of the start times for this event
		foreach($eventTime as $time)
		{
			$arrTime = explode(':',$time->);//holds the parts of time
			$arrEndTime = explode(':',$time->);//holds the parts of time
	
			//starts the displays the time of the event
			$strDisplayDateVenue .= "<label class='".$strEventDateClass."'>";
		
			//checks if this Event should be display
			//if so then add in the @ for then event to display properly
			if($boolEventDisplay == false)
				$strDisplayDateVenue .= " @ ";
			
			//displays the time of the event
			$strDisplayDateVenue .= date("g:i A",mktime(intval($arrTime[0]), intval($arrTime[1]), 0, 0, 0, 0));
			
			//checks if the end time should be display as there will be times that it should not
			if($time->end_time != "00:00")
				$strDisplayDateVenue .= " - ".date("g:i A",mktime(intval($arrEndTime[0]), intval($arrEndTime[1]), 0, 0, 0, 0));
									
			$strDisplayDateVenue .= "</label>";
		}//end of foreach
	}//end of if
		
	//checks if this Event should be display or there is a time 
	//display if there is no event date being displayed
	if($boolEventDisplay == false || $eventTime != false)
		$strDisplayDateVenue .= "<br/>";
	
	//checks if this is a event if so then use the event details page
	//and checks if this Venue should be display
	if ($venueEvent != false && $boolVenueDisplay == false)
		$strDisplayDateVenue .= "<label class='".$strVenueClass."'>".esc_html(stripslashes_deep($venueEvent[0]->))."</label>";
	
	return $strDisplayDateVenue;
}//end of displayEventDateVenue()

//Display the file's icon
function displayFileIcon($fileCurrent, $boolIsMini = false)
{
	$strMiniOrLock = "";//holds the extra mode of the file either it is small and or is locked
	$strFileIconName = "Link";//holds the file name of the icon
		
	//checks if the image will be small
	if($boolIsMini == true)
		$strMiniOrLock = "Mini";
	
	//checks if this file is locked 
	//then displays the lock
	if(checkFileAccess($fileCurrent->) == false)
		$strMiniOrLock .= "Lock";
		
	//checks which category the user belongs to
	switch($fileCurrent->)
	{
		case 11:
		case 10:
			$strFileIconName = "PDF";
		break;
		case 9:
		case 8:
			$strFileIconName = "XLS";
		break;
		case 7:
		case 6:
			$strFileIconName = "PPT";
		break;
		case 5:
		case 4:
			$strFileIconName = "Doc";
		break;
	}//end of switch
	
	return "FileIcon".$strFileIconName.$strMiniOrLock.".png";
}//end of displayFileIcon()

//Display Share Icons
function displayShareIcons($postCurrentID, $boolIsSingle, $boolIsEvent = false, $objPage = false)
{
	$strURLProtocol = strpos(strtolower(htmlentities($_SERVER['SERVER_PROTOCOL'])),'https') === FALSE ? 'http://' : 'https://';//holds the URL protocal
	$strDisplayPost = "";//holds the post being display
	$strShareURL = get_permalink();//holds the url that will be used to share
	$strSingleClass = "";//holds if the class of the share icons will as they will be different
	$strFBImage = get_bloginfo('template_url')."/images/ETTLogo.png";//holds the image that will be used to display to facebook
	$strFBDesc = trim(str_replace("'","\'",substr(get_the_excerpt(),0,strrpos(get_the_excerpt(),"...") - strlen(get_the_excerpt()))));//holds the description for facebook
	$strFBTitle = get_the_title();//holds the title for facebook

	//checks if there is a thumnail for this post	
	if (has_post_thumbnail())
		$strFBImage = wp_get_attachment_url(get_post_thumbnail_id($postCurrentID));
	
	//checks if the share icons should be in a single page or an array of summary post
	if($boolIsSingle == true)
		$strSingleClass = "Single";
	
	//checks if this is a event or a posting
	if($boolIsEvent === true)
	{
		//gets the Category for this posting if it has one
		$eventDetails = getEventCate($postCurrentID, $boolIsEvent);
		
		//sets the share for events 
		$strShareURL = site_url()."/events-pd/event-details?ee=".$postCurrentID;
		$strFBTitle = trim(str_replace("'","\'",$eventDetails[0]->));
		$strFBDesc = "";
		$strFBImage = getEventThumbnail($postCurrentID, $boolIsEvent);
	}//end of if
	
	//checks if this even a page
	if($objPage !== false)
	{
		//sets the share for events 
		$strShareURL = get_page_link($postCurrentID);
		$strFBTitle = trim(str_replace("'","\'",$objPage->));
		$strFBDesc = trim(str_replace("'","\'",get_post_meta($postCurrentID, '_aioseop_description', true)));
		$strFBImage = wp_get_attachment_url(get_post_thumbnail_id($postCurrentID));
	}//end of if
	
	$strDisplayPost = "<div class='divJustHidden div".$strSingleClass."ShareIconsLinkHidden' id='divShareIconsLink".$postCurrentID."'>".
		"<div class='divShareIconsLinkHiddenClose'>".
			"<a href='javascript:void(0);' onClick='javascript:toggleLayer(&quot;divShareIconsLink".$postCurrentID."&quot;,&quot;&quot;,&quot;&quot;);'>".
				"<img alt='X' src='".get_bloginfo('template_url')."/images/XButton.png' />".
			"</a>".
		"</div>".
		"<input type='text' value='".$strShareURL."' id='inputShareIconsLink".$postCurrentID."'>".
	"</div>".
	"<div class='customContainer divImage".$strSingleClass."ShareIconsContainer";

	//checks if the share icons should be in a single page or an array of summary post
	if($boolIsSingle == false || $boolIsSingle == "")
		$strDisplayPost .= " divJustHidden";
	
	$strDisplayPost .= "' id='divImageShareIcon".$postCurrentID."'>".   
		"<div class='customLeft divImage".$strSingleClass."ShareIconsLeft'>".
			"<a href='http://twitter.com/share?url=".urlencode($strShareURL)."&text=".urlencode($strFBTitle)." &#73; @ElemTeachersTO &related=ElemTeachersTO' target='_blank'>".
			"<img src='".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsTwitterOff.png' alt='Twitter' onMouseOver='javascript:changeImage(&quot;imgShareIconTwitter".$postCurrentID."&quot;,&quot;".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsTwitterOn.png&quot;);' onMouseOut='javascript:changeImage(&quot;imgShareIconTwitter".$postCurrentID."&quot;,&quot;".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsTwitterOff.png&quot;);' id='imgShareIconTwitter".$postCurrentID."' />".
			"</a>".
		"</div>".
		"<div class='customMiddle divImage".$strSingleClass."ShareIconsMiddle fb-login-button'>".
			"<a href='javascript:void(0);' onclick='fbInit(&quot;https://www.facebook.com/sharer/sharer.php?u=".$strShareURL."&quot;,&quot;".$strFBImage."&quot;,&quot;".$strFBTitle."&quot;,&quot;".$strFBDesc."&quot;);return false;'>".
				"<img src='".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsFBOff.png' alt='Facebook' onMouseOver='javascript:changeImage(&quot;imgShareIconFacebook".$postCurrentID."&quot;,&quot;".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsFBOn.png&quot;);' onMouseOut='javascript:changeImage(&quot;imgShareIconFacebook".$postCurrentID."&quot;,&quot;".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsFBOff.png&quot;);' id='imgShareIconFacebook".$postCurrentID."' />".
			"</a>".
		"</div>".
		"<div class='customMiddle divImage".$strSingleClass."ShareIconsMiddle divImageShareIconMail'>".
			"<a href='mailto:?subject=".urlencode('A Link You May Like')."&body=".urlencode('Here is a posting I thought you may like <a href=&quot;'.$strShareURL.'&quot;>'.$strShareURL.'</a>')."' target='_blank'>".
				"<img src='".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsMailOff.png' alt='Mail' onMouseOver='javascript:changeImage(&quot;imgShareIconMail".$postCurrentID."&quot;,&quot;".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsMailOn.png&quot;);' onMouseOut='javascript:changeImage(&quot;imgShareIconMail".$postCurrentID."&quot;,&quot;".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsMailOff.png&quot;);' id='imgShareIconMail".$postCurrentID."' />".
			"</a>".
		"</div>".
		"<div class='customRight divImage".$strSingleClass."ShareIconsRight' onClick='javascript:toggleLayer(&quot;divShareIconsLink".$postCurrentID."&quot;,&quot;&quot;,&quot;&quot;);getDocID(&quot;inputShareIconsLink".$postCurrentID."&quot;).focus();getDocID(&quot;inputShareIconsLink".$postCurrentID."&quot;).select();'>".
			"<a href='javascript:void(0);'>".
				"<img src='".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsLinkOff.png' alt='Link' onMouseOver='javascript:changeImage(&quot;imgShareIconLink".$postCurrentID."&quot;,&quot;".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsLinkOn.png&quot;);' onMouseOut='javascript:changeImage(&quot;imgShareIconLink".$postCurrentID."&quot;,&quot;".get_bloginfo('template_url')."/images/".$strSingleClass."ShareIconsLinkOff.png&quot;);' id='imgShareIconLink".$postCurrentID."' />".
			"</a>".
		"</div>".
		"<div class='customFotter divImage".$strSingleClass."ShareIconsFooter'></div>".
	"</div>";

	return $strDisplayPost;
}//end of displayShareIcons()

//Displays Events sidebar
function eventSidebar($events, $strSidebarEventTitle, $strGliderID)
{	
	//sets the header of the widget
	return "<script type='text/javascript'>".
			"$(document).ready(function(){".
				"featuredcontentglider.init({".
				"gliderid: 'div".$strGliderID."',".
				"contentclass: 'glide".$strGliderID."Content',".
				"togglerid: 'p-".$strGliderID."Select',".
				"remotecontent: '',".
				"selected: 0,".
				"persiststate: false,".
				"speed: 500,".
				"direction: 'rightleft',".
				"autorotate: true,".
				"autorotateconfig: [10000, 9999]".
			"})".
		"});".
	"</script>".
	"<div class='divSidebarSecton'>".
		"<div class='divSidebarSectonHeader'>".
			"<label>".$strSidebarEventTitle."</label>".
		"</div>".
		"<div id='divSideBarEvents' class='divSidebarSectonBody'>".
			"<div id='div".$strGliderID."' class='glide".$strGliderID."ContentWrapper'>".
				//displays the format of the sidebar
				eventSidebarFormat($events, true).						
			"</div>".
		"</div>".
		"<div class='divSidebarLinkToMore'>
			<div id='p-".$strGliderID."Select' class='glide".$strGliderID."ContentToggler'></div>".
			"<div class='customFooter'></div>".
		"</div>".
	"</div>";
}//end of eventSidebar()

//Format Events for the sidebar
function eventSidebarFormat($events, $boolUsingGlider)
{
	$strDisplaysEvent = "";//holds the displays for the events

	//goes around for each event found in events and gives the sidebar event
	foreach ($events as $event) 
	{
		//checks if the event is actully inside another arrary
		if(is_array($event))
		{
			$cpyEvent = $event[0];//holds the stdclass to
	
			//resets event to be just a stdclass
			$event = new StdClass();

			//copies the stdclass into the event in order to activated use this function
			$event = clone $cpyEvent;
		}//end of if
	
		$eventCurrentMeta = unserialize($event->);//holds all of the meta data of the event
		$status = event_espresso_get_is_active($event->);//holds the status of the event
		$eventTime = getDataColoumn("", "", "Where  = ".$event->);//gets the times for this event
		$arrDescCaption = stripslashes_deep(explode(' ',$event->));//holds the words of the Caption for the desc	

		//checks if the user is logged in to view this event
		if (!is_user_logged_in() && $event->member_only == 'Y') 
			//Display a message if the user is not logged in.
			$strDisplaysEvent .= '';
		else 
		{
			//checks the status of the event
			switch ($status['status']) 
			{
				case 'NOT_ACTIVE':
					//Don't show the event if any of the above are true
					break;
				default:
					//checks if this is in a if
					if($boolUsingGlider == true)
						$strDisplaysEvent .= '<div class="glideEventContent">'.
							'<div class="divSidebarEventsRight">';
					else
						$strDisplaysEvent .= '<div class="divSidebarNonGlideEventsRight">';
			
					//checks if there is a image to display this event
					if(empty($eventCurrentMeta['event_thumbnail_url']) == false) 
					{
						$strImageName = str_replace(".jpg","-186x72.jpg",$eventCurrentMeta['event_thumbnail_url']);//holds the image name of what to display for the feature image
						
						//checks which image type to use .gif or .png
						if(strrpos($strImageName, ".gif") > 0)
							$strImageName = str_replace(".gif","-186x72.gif",$eventCurrentMeta['event_thumbnail_url']);
						else if(strrpos($strImageName, ".png") > 0)
							$strImageName = str_replace(".png","-186x72.png",$eventCurrentMeta['event_thumbnail_url']);
					
						$strDisplaysEvent .= '<a href="'.site_url().'/events-pd/event-details?ee='.$event->.'"><img src="'.$strImageName.'" alt="'.stripslashes_deep($event->).'" /></a>'; 
					}//end of if
						
					$strDisplaysEvent .= '<div class="divEventTitle" id="event_title-'.$event->.'" class="event_title ui-widget-header ui-corner-top">'.
						'<a title="'.stripslashes_deep($event->).'" class="a_event_title" id="a_event_title-'.$event->.'" href="'.site_url().'/events-pd/event-details?ee='.$event->.'">'.stripslashes_deep($event->).'</a>'.
					'</div>'.
					'<div class="event-meta">';

						$strDisplaysEvent .= displayEventDateVenue($event->,"lblEventDate","lblEventPlace",true,true);

						//checks if there is a venue for this event
						if(empty($eventCurrentMeta['venue_id']) == false)
						{ 								
							//gets the Venue for this posting
							$venueEvent = getEventVenue($event->, true);
			
							//checks if this is a venue
							if ($venueEvent != false)
								$strDisplaysEvent .= '<div class="divEventPlace" id="p_event_venu-'.$event-> .'" class="event_venue">'.
								'<span class="section-title"></span>'.
									'<label class="lblEventPlace">';
									
									//gets all of the words for the venune
									$arrCaption = explode(' ',stripslashes_deep($venueEvent[0]->));
									
									//checks if the number of words is more then 5
									if(count($arrCaption) > 5)
									{
										//resets the Caption for the first word that is in arrCaption
										$strCaption = $arrCaption[0];
										
										//goes around for the each other word that is in arrCaption for displays 
										for($intCaptionIndex = 1;$intCaptionIndex < 5;$intCaptionIndex++)
										{
											$strCaption .= " ".$arrCaption[$intCaptionIndex];
										}//end of for loop
							
										$strDisplaysEvent .= $strCaption."...";
									}//end of if
									else
										$strDisplaysEvent .= stripslashes_deep($venueEvent[0]->);
									
							$strDisplaysEvent .= '</label>'.
							'</div>';
						}//end of if
						
						$strDisplaysEvent .= "<div class='divEventSidebarContent'>".
							"<label>";
						
						//checks if the Caption is larger then 19 words if so then displays the first 50 words
						if(count($arrDescCaption) > 5)
						{
							//resets the Caption for the first word that is in arrCaption
							$strCaption = $arrDescCaption[0];
							
							//goes around for the each other word that is in arrCaption for displays 
							for($intCaptionIndex = 1;$intCaptionIndex < 5;$intCaptionIndex++)
							{
								$strCaption .= " ".$arrDescCaption[$intCaptionIndex];
							}//end of for loop
							
							//ends the strCaption with dots to tell that there is more items to dispaly							
							$strDisplaysEvent .= $strCaption."...";
						}//end of if
						else
							$strDisplaysEvent .= stripslashes_deep($event->);
	
								$strDisplaysEvent .= " <a class='more-link' href='".site_url()."/events-pd/event-details?ee=".$event->."'>Read More</a>".
							"</label>".
						"</div>".
					"</div>";
					
					//gets all of the cateogries for this event
					$eventCate = getEventCate($event->, true);
						
					//checks if there is any categories to display
					if ($eventCate != false)
					{
						//does around for each cateogry found and display them 
						foreach ($eventCate as $categoryName) 
						{ 
							$strDisplaysEvent .= "<div class='spanPostTag customLeft'>".$categoryName->."</div>";
						}//end of foreach
			 
						$strDisplaysEvent .= "<div class='customFooter'></div>";
					}//end of if 
				
					//checks if this is in a if
					if($boolUsingGlider == true)
						$strDisplaysEvent .= "</div>";
						
					//ends the sidebar
					$strDisplaysEvent .= "</div>";

					break;
				}//end of switch
			}//end of else
		}//end of foreach

	return $strDisplaysEvent;
}//end of eventSidebarFormat() 

//changes the excerpt to fit with the design

add_filter('wp_trim_excerpt', 'excerpt');
add_filter('excerpt_length', 'excerptLength');
add_filter('excerpt_more', 'excerptMore');

function excerpt($excerpt) 
{
	//returns the new read more message
	return str_replace('[...]', '...', $excerpt);
}//end of excerpt()

function excerptLength($length) 
{
	//returns the new length to use
	return 19;
}//end of excerptLength()

function excerptMore($more) 
{
	//returns the new read more message
	global $post;
	
	//gets the Category for this posting if it has one
	$eventDetailsCate = getEventCate($post->, false);
	
	$strDisplayPost = '... <a href="';
	
	//checks if this is a event if so then use the event details page instead
	if ($eventDetailsCate != false)
		$strDisplayPost .= site_url()."/events-pd/event-details?ee=".$eventDetailsCate[0]->;
	else
		$strDisplayPost .= get_permalink($post->);
		
	return $strDisplayPost.'" class="more-link">Read More</a>';
}//end of excerptMore()

//gets the clients browser informaiton
function getBrowser()
{
	$uagentCurrent = htmlentities($_SERVER['HTTP_USER_AGENT']);//holds the details of the user
	$strBrowserName = 'Unknown';//holds the browser name
	$strBrowserShortName = '';//holds the browser name short forum
	$strPlatform = 'Unknown';//holds the platform of the user
	$strBrowserVersion= "";//holds the version of the browser
	
	//First get the platform?
	if (preg_match('/linux/i', $uagentCurrent))
		$strPlatform = 'Linux';
	elseif (preg_match('/macintosh|mac os x/i', $uagentCurrent))
		$strPlatform = 'Mac';
	elseif (preg_match('/windows|win32/i', $uagentCurrent))
		$strPlatform = 'Windows';
	
	// Next get the name of the useragent yes seperately and for good reason
	if(preg_match('/MSIE/i',$uagentCurrent) && !preg_match('/Opera/i',$uagentCurrent))
	{
		$strBrowserName = 'Internet Explorer';
		$strBrowserShortName = "MSIE";
	}//end of else if
	elseif(preg_match('/Firefox/i',$uagentCurrent))
	{
		$strBrowserName = 'Mozilla Firefox';
		$strBrowserShortName = "Firefox";
	}//end of else if
	elseif(preg_match('/Chrome/i',$uagentCurrent))
	{
		$strBrowserName = 'Google Chrome';
		$strBrowserShortName = "Chrome";
	}//end of else if
	elseif(preg_match('/Safari/i',$uagentCurrent))
	{
		$strBrowserName = 'Apple Safari';
		$strBrowserShortName = "Safari";
	}//end of else if
	elseif(preg_match('/Opera/i',$uagentCurrent))
	{
		$strBrowserName = 'Opera';
		$strBrowserShortName = "Opera";
	}//end of else if
	elseif(preg_match('/Netscape/i',$uagentCurrent))
	{
		$strBrowserName = 'Netscape';
		$strBrowserShortName = "Netscape";
	}//end of else if
	
	// finally get the correct version number
	$strKnown = array('Version', $strBrowserShortName, 'other');//holds the kown version number
	$strPattern = '#(?<browser>'.join('|', $strKnown).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';//holds the Patterens oof the 
	
	if (!preg_match_all($strPattern, $uagentCurrent, $matches)) {
		// we have no matching number just continue
	}//end of if
	
	//checks however versoin of the boswer the user has
	if (count($matches['browser']) != 1) 
	{
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($uagentCurrent,"Version") < strripos($uagentCurrent,$strBrowserShortName))
			$strBrowserVersion= $matches['version'][0];
		else
			$strBrowserVersion= $matches['version'][1];
	}//end of if
	else
		$strBrowserVersion = $matches['version'][0];
	
	// check if we have a number
	if ($strBrowserVersion==null || $strBrowserVersion=="") 
		$strBrowserVersion="?";
	
	return array(
		'userAgent' => $uagentCurrent,
		'name'      => $strBrowserName,
		'version'   => $strBrowserVersion,
		'platform'  => $strPlatform,
		'pattern'    => $strPattern
	);
}//end of getBrowser()

//creates a calendar
function genCalendar($intYear, $intMonth, $boolDisplayUserCal, $intDayNameLength = 3, $intFirstDay = 0, $intEventCate = 0, $boolDisplayOtherMonth = true, $boolIsSidebar = false)
{
    //echo "s".$boolDisplayUserCal = FALSE;
	$arrDays = array();//holds the days of the month 
	$arrDayNames = array();//holds the generate all the day names according to the current locale
	$arrTodaysDate = explode(" ",date('n j Y'));//holds todays date 0 = Month 1 = Day 2 = Year
    $dateFirstMonth = gmmktime(0,0,0,$intMonth,1,$intYear);//holds the first day of this month
	$intWeekday = ($intWeekday + 7 - $intFirstDay) % 7;//holds adjustment of the $intFirstDay
	$intNumDaysLastMonth = $intMonth - 1;//holds the days for last month
	$intNumDaysLastYear = $intYear;//holds the days for last year
	$intNumDaysNextMonth = $intMonth + 1;//holds the days for next month
	$intNumDaysNextYear = $intYear;//holds the days for next year
	$strSidebarClass = "";//holds the class of the sidebar
	
	//checks if this is a sidebar and changes the CSS to reflect this
	if($boolIsSidebar == true)
		$strSidebarClass = "Sidebar";
			
	//checks if the last of the month is zero menaing that this month is the first of the year
	//and should go to the last month of the year also adjusted the Year as well
	if($intNumDaysLastMonth < 1)
	{
		$intNumDaysLastYear = $intYear - 1;
		$intNumDaysLastMonth = 12;
	}//end of if
	
	//checks if the next of the month is zero menaing that this month is the last of the year
	//and should go to the first month of the year also adjusted the Year as well
	if($intNumDaysNextMonth > 12)
	{
		$intNumDaysNextYear = $intYear + 1;
		$intNumDaysNextMonth = 1;	
	}//end of if

	//gest the dates of the last and next month for display
	$intNumDaysLastMonthDays = cal_days_in_month(CAL_GREGORIAN, $intNumDaysLastMonth, $intNumDaysLastYear);
	$intNumDaysNextMonthDays = cal_days_in_month(CAL_GREGORIAN, $intNumDaysNextMonth, $intNumDaysNextYear);
	
    $strDisplayCalendar = "<div class='customContainer divEvent".$strSidebarClass."CalendarContainer'>";
	
	//goes around geting the names of the day
    for($n=0,$t=(3+$intFirstDay)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
	{
        $arrDayNames[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name
	}//end of for loop
	
	//holds the details of the calendar
    list($intMonth, $intYear, $month_name, $intWeekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$dateFirstMonth));

	//checks if the day names should be shown
    if($intDayNameLength > 0)
	{
		//starts the day name area
		$strDisplayCalendar .= "<div class='customHeader divEvent".$strSidebarClass."CalendarHeader'>";
		
        //checks if day_name_length is > 3, the full name of the day will be printed
        foreach($arrDayNames as $strDay)
		{
			$strDisplayDay = htmlentities($strDay);//holds the dsy that will be display to the use
			
			//checks if the user whats to displays the day in a format 1-4 or display the whole day
			if($intDayNameLength < 5)
			{
				//checks if the length is 4 if so then removes day 
				//as some days only have 3 letters with some days and also if it is
				if($intDayNameLength == 4)
				{
					//checks if it is Wed or Sat as those days should be 3 
					//because they ususlly displayed when the day names are 4 letters
					if($strDay == "Wednesday" || $strDay == "Saturday")
						$strDisplayDay = substr($strDay,0,3);
					else				
						$strDisplayDay = str_replace("day","",$strDay);
				}//end of if
				//checks if the lenght is 1 as only show the first letter
				else if($intDayNameLength == 1)
				{
					//checks if it is Thrus or Sat as those days should be 2
					//because they will be confuss with the other days that T or S
					if($strDay == "Thursday" || $strDay == "Saturday")
						$strDisplayDay = substr($strDay,0,2);
					else				
						$strDisplayDay = substr($strDay,0,$intDayNameLength);
				}//end of if
				else		
					//displays the sorten day name
					$strDisplayDay = substr($strDay,0,$intDayNameLength);
			}//end of if
			else
				$strDisplayDay = $strDay;

            $strDisplayCalendar .= "<div class='customLeft divEvent".$strSidebarClass."CalendarLeft divEventCalendar".$strSidebarClass."DaysName' id='divDay".htmlentities($strDay)."'>".
				htmlentities($strDisplayDay).
			"</div>";
		}//end of foreach
		
		//ends the days name section
        $strDisplayCalendar .= "<div class='customFooter divEvent".$strSidebarClass."CalendarFooter'></div></div>";
    }//end of if

	//goes around add days for the other month before this month
    for($intOtherDaysIndex = 1,$intDaysDisplay = ($intNumDaysLastMonthDays - $intWeekday);$intWeekday >= $intOtherDaysIndex;$intOtherDaysIndex++)
	{
		$strDisplayCalendar .= "<div class='customLeft divEvent".$strSidebarClass."CalendarLeft divEvent".$strSidebarClass."CalendarOtherLeft'>".
			"<div class='divEvent".$strSidebarClass."CalendarOtherDays'>";
		
		//checks if there is any days that are still in the other months if so then if the user wnats to 
		//display the number or display nothing
		if($boolDisplayOtherMonth == true)
		{
			//displays the days event
			$strDisplayCalendar .= genCalendarEventPosting($intNumDaysLastYear, $intNumDaysLastMonth, $intDaysDisplay + $intOtherDaysIndex, $intEventCate, $boolIsSidebar, $boolDisplayUserCal);
		}//end of if
		else
			$strDisplayCalendar .= "&nbsp;";
			
		$strDisplayCalendar .= "</div>".
		"</div>";
	}//end of for loop
		
	//goes around displaying the days of this month
    for($intMonthDays = 1,$intDaysInMonth=gmdate('t',$dateFirstMonth); $intMonthDays <= $intDaysInMonth; $intMonthDays++,$intWeekday++)
	{
		//checks if this is the end of the week
        if($intWeekday == 7)
		{
			//resets the week for the next week
            $intWeekday = 0;
            $strDisplayCalendar .= "<div class='customFooter divEvent".$strSidebarClass."CalendarFooter'></div>";
        }//end of if
		
		$strDisplayCalendar .= "<div class='customLeft divEvent".$strSidebarClass."CalendarLeft divEvent".$strSidebarClass."CalendarDays";
		
		//checks if this is the day todays date if so then add a add a class to it
		if($arrTodaysDate[1] == $intMonthDays && $arrTodaysDate[0] == $intMonth && $arrTodaysDate[2] == $intYear)
			$strDisplayCalendar .= " divEvent".$strSidebarClass."Today";
			
		//checks if it is the end of the week or the begining as this is the style may change 
		//during those days
		 if($intWeekday == 0 || $intWeekday == 6)
		 	$strDisplayCalendar .= " divEvent".$strSidebarClass."CalendarWeekend";
		else
		 	$strDisplayCalendar .= " divEvent".$strSidebarClass."CalendarWeekdays";
		
		$strDisplayCalendar .= "'>";
		
		//displays the days event
		$strDisplayCalendar .= genCalendarEventPosting($intYear, $intMonth, $intMonthDays, $intEventCate, $boolIsSidebar, $boolDisplayUserCal);
		
		$strDisplayCalendar .= "</div>";
    }//end of for loop
	
	//goes around add days for the other month before this month
    for($intOtherDaysIndex = 1;$intWeekday < 7;$intOtherDaysIndex++,$intWeekday++)
	{
		$strDisplayCalendar .= "<div class='customLeft divEvent".$strSidebarClass."CalendarLeft divEvent".$strSidebarClass."CalendarOtherLeft'>".
			"<div class='divEvent".$strSidebarClass."CalendarOtherDays'>";
		
		//checks if there is any days that are still in the other months if so then if the user wnats to 
		//display the number or display nothing
		if($boolDisplayOtherMonth == true)
		{
			//displays the days event
			$strDisplayCalendar .= genCalendarEventPosting($intNumDaysNextYear, $intNumDaysNextMonth, $intOtherDaysIndex, $intEventCate, $boolIsSidebar, $boolDisplayUserCal);
		}//end of if
		else
			$strDisplayCalendar .= "&nbsp;";
			
		$strDisplayCalendar .= "</div>".
		"</div>";
	}//end of for loop
	
	$strDisplayCalendar .= "<div class='customFooter divEvent".$strSidebarClass."CalendarFooter'></div>";
	
	//checks if this is not a sidebar as the prev and next will be display here
	if($boolIsSidebar == false)
	{
		$strDisplayCalendar .= "<div class='customContainer divEventCalendarFooterContainer'>".
			"<div class='customLeft divEventCalendarFooterLeft'>".
				"<span id='spanCalLeftPagingArrow' class='spanPagelink spanPostPagingArrows spanPrevNext'>".
					"<a title='Prev Month' onclick='sendCalendar(&quot;".get_bloginfo('template_url')."/PurePHP/GetCalendar.php&quot;, getDocID(&quot;divCalenderBody&quot;), getDocID(&quot;selectCalMonth&quot;), getDocID(&quot;selectCalYear&quot;), 1, 1, 0, null, 1, 0, ".$boolDisplayUserCal.");$(&quot;body,html&quot;).animate({scrollTop: 350}, 800);' href='javascript:void(0)' class='aPostPagingArrows aFontPrevNext'>&lt; Prev</a>".
				"</span>".
			"</div>".
			"<div class='customRight divEventCalendarFooterRight'>".
				"<span id='spanCalRightPagingArrow' class='spanPagelink spanPostPagingArrows spanPrevNext'>
					<a title='Next Month' onclick='sendCalendar(&quot;".get_bloginfo('template_url')."/PurePHP/GetCalendar.php&quot;, getDocID(&quot;divCalenderBody&quot;), getDocID(&quot;selectCalMonth&quot;), getDocID(&quot;selectCalYear&quot;), 1, 2, 0, null, 1, 0, ".$boolDisplayUserCal.");$(&quot;body,html&quot;).animate({scrollTop: 350}, 800);' href='javascript:void(0)' class='aPostPagingArrows aFontPrevNext'>Next &gt;</a>".
				"</span>".
			"</div>".
			"<div class='customFooter divEventCalendarFooterFooter'></div>".								
		"</div>";
	}//end of if

	//closes the contanter	
	$strDisplayCalendar .= "</div>";
	
	//checks if this is not a sidebar as the leagend will be display here
	if($boolIsSidebar == false || $boolIsSidebar == 0)
	{
		$strDisplayCalendar .= "<div class='customContainer divEventCalendarFooterLegendContainer'>".
			"<div class='customLeft divEventCalendarFooterLegendLeft'>".
				"<div class='divEventCalDayEventsCateEvents divEventCalDayEvents divEventCalendarPopupCateId2'>".
					"<label>e</label>".
				"</div>".
			"</div>".
			"<div class='customRight divEventCalendarFooterLegendRight'>".
				"<label>Events</label>".
			"</div>".
			"<div class='customLeft divEventCalendarFooterLegendLeft'>".
				"<div class='divEventCalDayEventsCateOffers divEventCalDayEvents divEventCalendarPopupCateId2'>".
					"<label>o</label>".
				"</div>".
			"</div>".
			"<div class='customRight divEventCalendarFooterLegendRight'>".
				"<label>Offers</label>".
			"</div>".
			"<div class='customLeft divEventCalendarFooterLegendLeft'>".
				"<div class='divEventCalDayEventsCateWorkshops divEventCalDayEvents divEventCalendarPopupCateId2'>".
					"<label>w</label>".
				"</div>".
			"</div>".
			"<div class='customRight divEventCalendarFooterLegendRight'>".
				"<label>Workshops</label>".
			"</div>".
			"<div class='customLeft divEventCalendarFooterLegendLeft'>".
				"<div class='divEventCalDayEventsCateMeetings divEventCalDayEvents divEventCalendarPopupCateId2'>".
					"<label>m</label>".
				"</div>".
			"</div>".
			"<div class='customRight divEventCalendarFooterLegendRight'>".
				"<label>Meetings</label>".
			"</div>".
			"<div class='customLeft divEventCalendarFooterLegendLeft'>".
				"<div class='divEventCalDayEventsCateHolidays divEventCalDayEvents divEventCalendarPopupCateId2'>".
					"<label style='font-size:10px;'>H</label>".
				"</div>".
			"</div>".
			"<div class='customRight divEventCalendarFooterLegendRight'>".
				"<label>Holidays</label>".
			"</div>".
			"<div class='customFooter divEventCalendarFooterLegendFooter'></div>".
		"</div>";
	}//end of if

    return $strDisplayCalendar;
}//end of genCalendar()

//displays the days event
function genCalendarEventPosting($intYear, $intMonth, $intMonthDays, $intEventCate, $boolIsSidebar, $boolDisplayUserCal)
{
	$strDisplayCalendar = "";//holds the days events being display
	$strSidebarClass = "";//holds the class of the sidebar
	$strCateWhere = "";//holds the where closue
	$arrDaysEvents = false;//holds the days events
	$strCurrentDate = date("Y-m-d",mktime(0, 0, 0, $intMonth, $intMonthDays, $intYear));
	
	//checks if there is a Eevnt Cate ID to use
	if($intEventCate > 0)
		$strCateWhere .= "cate.id = ".$intEventCate." AND ";

	//checks if the user is loged in a that this is there calendar
	//and if the have any events for this day

	if(is_user_logged_in() && $boolDisplayUserCal == true)
	{
		$userCurrent = wp_get_current_user();//holds the current user data
		$strUserCommittees = get_user_meta($userCurrent->ID, "ettcommittee", true);//holds the users commitess the are looking for 

		//sets if the day of the user who is logged in
		$arrDaysEvents = getDataColoumn("*", "", "Where ".$strCateWhere."'".$strCurrentDate."' BETWEEN  AND  AND  = '".$userCurrent->."' AND ='A' Group by ,  Order by ");

	   	//checks if there is any commitess to display if not then do not display
		if(empty($strUserCommittees) == false)
		{
			$arrPageID = explode(",", $strUserCommittees);//holds the Pages ids that will be used to display
			$arrCateID = array();//holds the Category ids that will be used to display
			
			//goes around for each id of the commiteee page find the page slug and getting the
			//category slug that matches it
			for($intIndex = 0;$intIndex < count($arrPageID); $intIndex++)
			{
				$pageDetails = get_post($arrPageID[$intIndex]);//holds the details of the commiteee page
				$cateDetails = get_category_by_slug($pageDetails->post_name);//holds details of category that was found by slug
	
				//checks if there is a category details
				if($cateDetails != false)
					//adds the category id to $arrCateID in order to find the events for this committee
					$arrCateID[] = $cateDetails->;
			}//end of for loop
		
			//sets the options of the posttings
			$options = array('method' => 'loop',
			   'posts_per_page' => 100,
			   'orderby' => "date",
			   'order' => "DSEC",
			   'category__in' => $arrCateID
			);

			//adds the filter to the where in order to remove all events that should not be in there
			add_filter('posts_where', "whatsNewQuery"); 
			
			//resets the getting post to get the other post for this archive
			$queryPost = new WP_Query($options);
				
			//removes the filter on the WP_Query in order not to interfear with the other Queries
			remove_filter('posts_where', "whatsNewQuery"); 

			//checks if there is any postings to display
			if ($queryPost->have_posts())
			{
				//goes around displaying the format the the use wanted to display checking for displaying
				while ($queryPost->have_posts())
				{
					//gets the next post
					$queryPost->the_post();

					//gets the Category for this posting if it has one
					$eventDetailsCate = getEventCate(get_the_ID(), false);
				}//end of while loop
			}//end of if

			//sets the query back to when the page loads
			wp_reset_query();			
		}//end of if
	}//end of if
	else
		//gets all of the events of the day
		$arrDaysEvents = getEventCate(1, true, $strCateWhere."'".$strCurrentDate."' BETWEEN  AND  AND ='A' Group by ,  Order by ");
		
	$strDivEvent = "onMouseOver='javascript:toggleLayer(&quot;divEventCalendarPopup".$intMonth.$intMonthDays."&quot;,&quot;&quot;,&quot;&quot;);' onMouseOut='javascript:toggleLayer(&quot;divEventCalendarPopup".$intMonth.$intMonthDays."&quot;,&quot;&quot;,&quot;&quot;);'";//holds the event for the div in order to actived the pop up day event
	
	//checks if this is a sidebar and changes the CSS to reflect this
	if($boolIsSidebar == true)
		$strSidebarClass = "Sidebar";
	
	//checks if there is any events for this day if so create the popup for this day
	if(count($arrDaysEvents) > 0)
	{
		$arrGoodEventsDays = array();//holds all of the events that can be display to the user
		
		$strDisplayCalendar .= "<div class='divJustHidden divEvent".$strSidebarClass."CalendarPopup' id='divEventCalendarPopup".$intMonth.$intMonthDays."' ".$strDivEvent.">";
		
		//checks if all of the postings for this day is current as the admin may want to not display
		//some events in the calendar, do not ask why
		for($intEventIndex = 0;count($arrDaysEvents) > $intEventIndex; $intEventIndex++)
		{
			$arrPostCate = wp_get_post_categories($arrDaysEvents[$intEventIndex]->post_id);//holds the all of the categories for this post
						
			//checks if this event should be display in the calendar if so then adds it to the array
			if(!in_array(180,$arrPostCate))
				$arrGoodEventsDays[] = $arrDaysEvents[$intEventIndex];
		}//end of for loop
		
		//resets the $arrDaysEvents with the ones the can be display 
		$arrDaysEvents = $arrGoodEventsDays;
		
		//checks if this is on the full calendar as the pop will display on the right side
		if($boolIsSidebar == false)
			$strDisplayCalendar .= "<div class='customLeft divTriangleLeft'></div>";
			
		$strDisplayCalendar .= "<div class='customRight customContainer divEventCalendarPopupContainer'>
				<div class='customHeader divEventCalendarPopupHeader'>".
					date("F j, Y",mktime(0, 0, 0, $intMonth, $intMonthDays, $intYear)).
				"</div>".
				"<div class='divEventCalendarPopupBody'>".
					genCalendarEventPostingPopUp($arrDaysEvents,$intMonth.$intMonthDays, $boolDisplayUserCal);
					
					//checks if the there is more then one event if so then display a footer
					if(count($arrDaysEvents) > 1)
					{
						//setats the event popup footer
						$strDisplayCalendar .= "<div id='divEventPopUpTriggerFooter".$intMonth.$intMonthDays."' class='divEventPopUpTriggerFooter'>";
		
						//goes around creating a glide togglers for each glidecontent on this pop up
						for($intEventIndex = 0;count($arrDaysEvents) > $intEventIndex; $intEventIndex++)
						{
							$strDisplayCalendar .= "<div id='divEventPopUpTriggerFooter".$intEventIndex.$intMonth.$intMonthDays."' class='divEventPopUpTrigger";
		
							//checks if this is the first event if so the selected
							if($intEventIndex == 0)
								$strDisplayCalendar .= " divEventPopUpTriggerSelected";
				
							$strDisplayCalendar .= "' onClick='classToggleLayer(getDocID(&quot;divEventCalendarPopup".$intMonth.$intMonthDays."&quot;),getDocID(&quot;divEventPostingPopUp".$intEventIndex.$intMonth.$intMonthDays."&quot;),&quot;divJustHidden divEventPopUpTriggerContent&quot;,&quot;div&quot;);classToggleLayerChangeClass(getDocID(&quot;divEventPopUpTriggerFooter".$intMonth.$intMonthDays."&quot;),getDocID(&quot;divEventPopUpTriggerFooter".$intEventIndex.$intMonth.$intMonthDays."&quot;),&quot;divEventPopUpTrigger divEventPopUpTriggerSelected&quot;,&quot;divEventPopUpTrigger&quot;,&quot;div&quot;);'>".
								"<a shape='rect' href='javascript:void(0);'>&nbsp;</a>".
							"</div>";
						}//end of for loop
					
						//closes the event popup footer
						$strDisplayCalendar .= "</div>";
					}//end of if
					
		$strDisplayCalendar .= "</div>".
			"</div>";
		
		//checks if this is on the sidebar as the pop will display on the left side
		if($boolIsSidebar == true)
			$strDisplayCalendar .= "<div class='customLeft div".$strSidebarClass."TriangleLeft'></div>";
			
		$strDisplayCalendar .= "<div class='customFooter'></div>".
		"</div>";
	}//end of if
	
	//checks if the is is being display on a sidebar if so then use only links if not 
	//then display 2 panels of events
	if($boolIsSidebar == true)
	{
		//checks if there is any events for this day if so then make it a link if not then 
		//normal number
		if(count($arrDaysEvents) > 0)
			$strDisplayCalendar .= "<a href='javascript:void(0);' ".$strDivEvent.">".$intMonthDays."</a>";
		else
			$strDisplayCalendar .= $intMonthDays;
	}//end of if
	else
		$strDisplayCalendar .= $intMonthDays;
		
	//goes around for the days events and display the first two items
	//if there is more then do ... for the pop-up window
	for($intEventIndex = 0;count($arrDaysEvents) > $intEventIndex; $intEventIndex++)
	{			
		//checks if this is the third item if so then do the ... and that it is not in the sidebar
		//to not display it
		if($intEventIndex == 2 && $boolIsSidebar == false)
			$strDisplayCalendar .= "<div class='divEventCalMoreEvents' ".$strDivEvent.">".
				"<label>...</label>".
			"</div>";
		else if($intEventIndex < 2 && $boolIsSidebar == false)
		{
			$cateCurrentMeta = unserialize($arrDaysEvents[$intEventIndex]->category_meta);//holds the category meta data
			//updates the Caption for the large calender as it iuses the name of the event
			$arrCaption = explode(' ',$arrDaysEvents[$intEventIndex]->event_name);
			
			$strDisplayCalendar .= "<div class='divEventCalDayEvents divEventCalDayEventsCate".$arrDaysEvents[$intEventIndex]->category_name."' ".$strDivEvent.">".
				"<label>".$arrDaysEvents[$intEventIndex]->category_identifier."</label>".
			"</div>".
			"<div class='divEventCalDayEventsCate".$arrDaysEvents[$intEventIndex]->category_name."TextBG' ".$strDivEvent.">".
				"<div class='divEventCalDayEventsText'>".
						"<label>";
						
			//checks if the Caption is larger then 19 words if so then displays the first 50 words
			if(count($arrCaption) > 2)
			{
				//resets the Caption for the first word that is in arrCaption
				$strCaption = $arrCaption[0];
				
				//goes around for the each other word that is in arrCaption for displays 
				for($intCaptionIndex = 1;$intCaptionIndex < 2;$intCaptionIndex++)
				{
					$strCaption .= " ".$arrCaption[$intCaptionIndex];
				}//end of for loop
				
				//ends the strCaption with dots to tell that there is more items to dispaly							
				$strDisplayCalendar .= $strCaption."...";
			}//end of if
			else
				$strDisplayCalendar .= $arrDaysEvents[$intEventIndex]->event_name;
		  
			$strDisplayCalendar .= "</label>".
				  "</div>".
			  "</div>";
		}//end of else
	}//end of for loop
	
    return $strDisplayCalendar;
}//end of genCalendarEventPosting()

//gets all the events of the day for PopUp
function genCalendarEventPostingPopUp($arrDaysEvents, $strDate, $boolDisplayUserCal)
{
	//goes around for the days events and display the first two items
	//if there is more then do ... for the pop-up window
	for($intEventIndex = 0;count($arrDaysEvents) > $intEventIndex; $intEventIndex++)
	{	
		$arrCaption = explode(' ',stripslashes_deep($arrDaysEvents[$intEventIndex]->));//holds the words of the Caption	
		$arrVenuneCaption = false;//holds the words of the Caption for the Venune
		
		//checkes if there is a venue for this event if so then get all of the words for the venue
		if($venueEvent != false)
			$arrVenuneCaption = explode(' ',stripslashes_deep($venueEvent[0]->));
		
		$strDisplayDaysEventPopUp .= "<div id='divEventPostingPopUp".$intEventIndex.$strDate."' class='divJustHidden divEventPopUpTriggerContent'";
		
		//checks if this is the first event if so then display it
		if($intEventIndex == 0)
			$strDisplayDaysEventPopUp .= " style='display:block'";
		
		//this is for the cateogry id on the left side
		
		$strDisplayDaysEventPopUp .= ">".
			"<div class='customLeft divEventCalendarPopupLeft'>";
		
		//checks if this event has a image and if so then uses 
		if (empty($strImageName) == false)
			$strDisplayDaysEventPopUp .= "<div class='divEventCalendarPopupLeftImage divEventCalDayEventsCate".$arrDaysEvents[$intEventIndex]->."TextBG'></div>";
		
		$strDisplayDaysEventPopUp .= "<div class='divEventCalendarPopupCateId divEventCalDayEventsCate".$arrDaysEvents[$intEventIndex]->."'>".
				"<label>".$arrDaysEvents[$intEventIndex]->."</label>".
			"</div>".
		"</div>";
		
		//this is for the Image or Long colour line if there is no image	
		
		$strDisplayDaysEventPopUp .= "<div class='customRight divEventCalendarPopupRight'>";
	
		//checks if there is no image and if so then do the background instead
		if (empty($strImageName) == true)
			$strDisplayDaysEventPopUp .= "<div class='divEventCalendarPopupRightNoImage divEventCalDayEventsCate".$arrDaysEvents[$intEventIndex]->."'></div>".
				"<div class='divEventCalDayEventsCate".$arrDaysEvents[$intEventIndex]->."TextBG'>&nbsp;</div>";
		else
		{
			if($boolDisplayUserCal == true)
                //gets the events thumbnail
			    $strDisplayDaysEventPopUp .= "<a href='".site_url()."/events-pd/event-details?ee=".$arrDaysEvents[$intEventIndex]->."'>";
            else
                //gets the events thumbnail
			    $strDisplayDaysEventPopUp .= "<a href='".site_url()."/events-pd/event-details?ee=".$arrDaysEvents[$intEventIndex]->."'>";
		    $strDisplayDaysEventPopUp .="<img src='".$strImageName."' alt='".stripslashes_deep($arrDaysEvents[$intEventIndex]->)."' />".
			"</a>";
		}//end of if
			
		$strDisplayDaysEventPopUp .= "</div>";
		
		//this is for the content of the event
		
		$strDisplayDaysEventPopUp .= "<div class='customFooter divEventCalendarPopupFooter'>".
		  "<div class='divEventCalendarPopupContentTitle'>".stripslashes_deep($arrDaysEvents[$intEventIndex]->).
			  "</a>".
		  "</div>".
		  "<div class='divEventCalendarPopupContentEventDetails'>";
		 
		 	//checks if this is a user calendar or a normal calendar
			if($boolDisplayUserCal == true)
				//displays the time and veune
				$strDisplayDaysEventPopUp .= displayEventDateVenue($arrDaysEvents[$intEventIndex]->, "", "", true, false, true, false);
			else
  				//displays the time and veune
				$strDisplayDaysEventPopUp .= displayEventDateVenue($arrDaysEvents[$intEventIndex]->, "", "", true, false, true, false);
				
		$strDisplayDaysEventPopUp .= "</div>".
			"<div class='divEventCalendarPopupContentEventDesc'>".
				"<label>";
		
		//checks if the Caption is larger then 19 words if so then displays the first 50 words
		if(count($arrCaption) > 10)
		{
			//resets the Caption for the first word that is in arrCaption
			$strCaption = $arrCaption[0];
			
			//goes around for the each other word that is in arrCaption for displays 
			for($intCaptionIndex = 1;$intCaptionIndex < 10;$intCaptionIndex++)
			{
				$strCaption .= " ".$arrCaption[$intCaptionIndex];
			}//end of for loop
			
			//ends the strCaption with dots to tell that there is more items to dispaly							
			$strDisplayDaysEventPopUp .= $strCaption."...";
		}//end of if
		else
			$strDisplayDaysEventPopUp .= stripslashes_deep($arrDaysEvents[$intEventIndex]->);
			
			$strDisplayDaysEventPopUp .= " <a class='more-link' href='".site_url()."/events-pd/event-details?ee=";
			
            if($boolDisplayUserCal == true)
            	$strDisplayDaysEventPopUp .= $arrDaysEvents[$intEventIndex]->;
            else
				$strDisplayDaysEventPopUp .= $arrDaysEvents[$intEventIndex]->id;

		$strDisplayDaysEventPopUp .= "'>Read More</a>".
					"</label>".
				"</div>".
			"</div>".
		"</div>";
	}//end of for loop
			
	return $strDisplayDaysEventPopUp;
}//end of genCalendarEventPostingPopUp()

//gets all of the users from a user role
function getAllUsersWithRole($strRole) 
{
	//does a search for all users for a protieller role
	$wp_user_search = new WP_User_Query(array('role' => $strRole));
	
	return $wp_user_search->get_results();
}//end of getAllUsersWithRole()

//gets the committee meetings from the data
function getCommitteeMeetings($strSlug, $intNumberPerPage = 1, $arrPageID = array(), $strPostFormat = "", $strAddFiler = "whatsNewQuery")
{
	$strDisplayPost = "";//holds the post being display
	$arrCateID = array();//holds the Category ids that will be used to display

	//checks if there is a category to use
	if(empty($strSlug) == false)
	{
		$cateDetails = get_category_by_slug($strSlug);//holds details of category that was found by slug
	
		//checks if there is a category details
		if($cateDetails != false)
			//adds the category id to $arrCateID in order to find the events for this committee
			$arrCateID[] = $cateDetails->;
	}//end of if
	else
	{
		//goes around for each id of the commiteee page find the page slug and getting the
		//category slug that matches it
		for($intIndex = 0;$intIndex < count($arrPageID); $intIndex++)
		{
			$pageDetails = get_post($arrPageID[$intIndex]);//holds the details of the commiteee page
			$cateDetails = get_category_by_slug($pageDetails->post_name);//holds details of category that was found by slug

			//checks if there is a category details
			if($cateDetails != false)
				//adds the category id to $arrCateID in order to find the events for this committee
				$arrCateID[] = $cateDetails->;
		}//end of for loop
	}//edn of else

	//sets the options of the posttings
	$options = array('method' => 'loop',
	   'posts_per_page' => $intNumberPerPage,
	   'orderby' => "date",
	   'order' => "DSEC",
	   'category__in' => $arrCateID
	);

	//adds the filter to the where in order to remove all events that should not be in there
	add_filter('posts_where', $strAddFiler); 
	
	//resets the getting post to get the other post for this archive
	$queryPost = new WP_Query($options);
		
	//removes the filter on the WP_Query in order not to interfear with the other Queries
	remove_filter('posts_where', $strAddFiler); 

	//checks if there is any postings to display
	if ($queryPost->have_posts())
	{
		//goes around displaying the format the the use wanted to display checking for displaying
		while ($queryPost->have_posts())
		{
			//gets the next post
			$queryPost->the_post();
	
			//checks if the user to set their own post format
			if(!empty($strPostFormat))
			{		
				require_once("content-".$strPostFormat.".php");
		
				//calls the funcation that will get and reutrn the post in a new format	
				$strDisplayPost .= call_user_func(array($strPostFormat, 'displayPostFormat'),get_post(get_the_ID()));
			}//end of if
			else	
				//displays the sidebar format for this post
				$strDisplayPost .= eventSidebarFormat(getEventCate(get_the_ID(), false), false);
		}//end of while loop
	}//end of if
	
	//sets the query back to when the page loads
	wp_reset_query();
	
	return $strDisplayPost;
}//end of getCommitteeMeetings()

//gets all of the user in a particilor user group
function getUserDataInGroup($intTermID, $strWhereTerm = "", $strUserRole = "author")
{
	$arrUserDetails = array();//holds the users details of that group
	
	//checks if there is any where and if not then use the default one
	if(empty($strWhereTerm) == true)
		$strWhereTerm = "Where  = ".$intTermID;

	//gets the exes from the term group table as this si where all user who are exe our locationed
	$queryExe = getDataColoumn("", "", $strWhereTerm);
			
	//checks if there is any data to display
	if ($queryExe != false && empty($strUserRole) == true)
	{
		//goes around displaying the format the the use wanted to display checking for displaying
		foreach ($queryExe as $sqlRow)
		{
			$arrTermDes = explode(":[",$sqlRow->);//holds the arry of the term description and users
			$arrTermUserID = str_replace("\"","",str_replace("}","",str_replace("]","",explode(",",$arrTermDes[1]))));//holds the users ids that are are part of the group

			//goes around for each user that is part of the group
			for($intUserIndex = 0;count($arrTermUserID) > $intUserIndex;$intUserIndex++)
			{
				//puts the details of the user into this arry
				$arrUserDetails[] = get_userdata($arrTermUserID[$intUserIndex]);
			}//end of for loop
		}//end of foreach
	}//end of if
	else if(empty($strUserRole) == false)
	{
		$userRoles = get_users('role='.$strUserRole);//holds all of the user for a single role

		//goes around getting all of the user in a role and adding it to the user details
		foreach ($userRoles as $userDetails)
		{
			//puts the details of the user into this arry
			$arrUserDetails[] = get_userdata($userDetails->);
		}//end of foreach
	}//end of else

	return $arrUserDetails;
}//end of getUserDataInGroup()

//changes the search form to fit the themes search form

add_filter('get_search_form', 'searchForm');

function searchForm() 
{
    echo '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    	<div class="customContainer" id="divHeaderSearchFormContainer">
			<div class="customLeft" id="divHeaderSearchFormLeft">
				<input type="text" placeholder="Search" name="s" id="s" class="inputSearch" />
			</div>
			<div class="customRight" id="divHeaderSearchFormRight">
				<input type="image" id="imgbSearch" src="'.get_bloginfo('template_url').'/images/search.png" />
			</div>
			<div class="customFooter" id="divHeaderSearchFormFooter"></div>
		</div>
    </form>';
}//end of searchForm()

//removes the keywords from the title

add_filter('the_title', 'titleTrim');

function titleTrim($strCurrentTitle) 
{	
	//removes the keywords from the title
	return preg_replace(array('#Protected:#','#Private:#'), '', attribute_escape($strCurrentTitle));
}//end of titleTrim()

//displays the what's new section as it is need in may areas of the site
function whatsNewDisplay($strTitle,$strPostFormat,$strSortBy,$strSort,$strDisplayBodyID,$strDisplayPost,$intDisplayNumberOfPostPerPage,$strCategory,$strTag,$strFormat,$strPostStatus,$boolDisplayTitle,$boolDisplayNotFound,$boolDisplayFilters,$boolDisplayPaging,$strSearchText,$strHeaderExtraClass)
{
	$strDisplayPost = "";//holds the post being display
	$queryDisplayPost = array();//holds all of the postings that can be display
	$options;//holds hte options that is used in the posting query
	$arrCate = array();//holds the Categaries for the drop-down
	$userBrowser = getBrowser();//holds the details of the user browser
	
	$strDisplayPost = "<a name='whatsNewTop' id='aWhatsNewTop'></a>".
		"<div class='customContainer";
	
	//checks if the user whats to display the title
	if($boolDisplayTitle == false || $boolDisplayTitle == "")
		$strDisplayPost .= " divJustHidden";
		
	//checks if there is a class to change the style of the title area
	if(empty($strHeaderExtraClass) == false)
		$strDisplayPost .= " ".$strHeaderExtraClass;

	$strDisplayPost .= "' id='divWidgetWhatsNewHeaderContainer'>". 
		"<div class='customLeft' id='divWidget";
		
		//checks if the filters are needed 
		if($boolDisplayFilters == false || $boolDisplayFilters == "")
			$strDisplayPost .= "Wide";
		
		$strDisplayPost .= "WhatsNewHeaderLeft'>". 
			"<label>".$strTitle."</label>". 
		"</div>". 
		"<div class='customMiddle";
		
		//checks if the filters are needed 
		if($boolDisplayFilters == false || $boolDisplayFilters == "")
			$strDisplayPost .= " divJustHidden";
		
		$strDisplayPost .= "' id='divWidgetWhatsNewHeaderMiddle'>".
			"<div class='customContainer divFieldsContainer'>". 
				"<div class='customLeft divFieldsLeft'>". 
					"<label>Category:</label>". 
				"</div>". 
				"<div class='customRight divFieldsRight' id='divCateSelect'>". 
					"<select id='selectCate".$strPostFormat."' name='selectCate".$strPostFormat."[]' class='selectPost selectGary' onchange='sendCateogryPosting(&quot;".get_bloginfo('template_url')."/PurePHP/GetPostings.php&quot;,getDocID(&quot;".$strDisplayBodyID."&quot;),getDocID(&quot;selectCate".$strPostFormat."&quot;),&quot;".$strTag."&quot;,&quot;".$strSearchText."&quot;,&quot;".$strFormat."&quot;,&quot;".$strPostFormat."&quot;,&quot;$strPostStatus&quot;,getDocID(&quot;selectSortBy".$strPostFormat."&quot;),&quot;".$boolDisplayPaging."&quot;,".$intDisplayNumberOfPostPerPage.",1);'>". 
						"<option value='".$strCategory."'>All</option>";

					//checks if there is any category if so then use that cate id as the 
					//parent for all category undereither it
					if($strCategory != "")
					{
						//gets all of the root cotegories 
						$arrCate = get_categories(array('hierarchical' => 0,
							'orderby' => 'name',
							'order' => 'ASC',
							'child_of' => $strCategory
						));
					}//end of if
					else
					{
						//gets all of the root cotegories 
						$arrCate = get_categories(array('hierarchical' => 0,
							'orderby' => 'name',
							'order' => 'ASC'
						));
					}//end of else
							
					//goes around for each category on site allow the user to choose their category			
					foreach($arrCate as $cateRoot)
					{
						//checks if the cateogry id is not being used
						//and the category is a non parent if so then skip this role
						if($cateRoot->parent == 0 && $categoryName->cat_ID != 1 || $strCategory != "")
						{
							$strDisplayPost .= "<option value='".$cateRoot->cat_ID."'";
		
							//checks one of the categories is being used if so then selected them
							if($strCategory == $cateRoot->cat_ID)
								$strDisplayPost .= " selected";
						
							$strDisplayPost .= ">".$cateRoot->name."</option>";
						}//end of if
					}//end of foreach
						
					$strDisplayPost .= "</select>". 
				"</div>". 
				"<div class='customFooter divFieldsFooter'></div>". 
			"</div>". 
		"</div>". 
		"<div class='customRight";
	
		//checks if the filters are needed 
		if($boolDisplayFilters == false || $boolDisplayFilters == "")
			$strDisplayPost .= " divJustHidden";
	
		$strDisplayPost .= "' id='divWidgetWhatsNewHeaderRight'><div class='customContainer divFieldsContainer'>". 
			"<div class='customLeft divFieldsLeft' id='divSortBy'>". 
				"<label>Sort by:</label>". 
			"</div>". 
			"<div class='customRight divFieldsRight' id='divSortBySelect'>". 
			    "<select id='selectSortBy".$strPostFormat."' name='selectSortBy".$strPostFormat."[]' class='selectPost selectGary' onchange='sendCateogryPosting(&quot;".get_bloginfo('template_url')."/PurePHP/GetPostings.php&quot;,getDocID(&quot;".$strDisplayBodyID."&quot;),getDocID(&quot;selectCate".$strPostFormat."&quot;),&quot;".$strTag."&quot;,&quot;".$strSearchText."&quot;,&quot;".$strFormat."&quot;,&quot;".$strPostFormat."&quot;,&quot;$strPostStatus&quot;,getDocID(&quot;selectSortBy".$strPostFormat."&quot;),&quot;".$boolDisplayPaging."&quot;,".$intDisplayNumberOfPostPerPage.",1);'>". 
					"<option value='0 1'>Date (latest to oldest)</option>". 
					"<option value='0 0'>Date (oldest to latest)</option>". 
					"<option value='1 0'>Name (A to Z)</option>". 
					"<option value='1 1'>Name (Z to A)</option>". 
				"</select>". 
			"</div>". 
			"<div class='customFooter divFieldsFooter'></div>". 
		"</div>";
		
		$strDisplayPost .= "</div>". 
		"<div class='customFooter' id='divWidgetWhatsNewHeaderFooter'></div>". 
	"</div>";
	
	//sets the options of the posttings
	$options = array('method' => 'loop',//'loop','array' - default = 'loop'
	   'posts_per_page' => $intDisplayNumberOfPostPerPage, //default = 5
	   'orderby' => $strSortBy, //'author','date','title','modified','parent','id','rand','comment_count'
	   'order' => $strSort, //'ASC','DESC'
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
	if($strCategory != "")
		//sets the option for the category
		$options['cat'] = $strCategory;
	else
		$options['cat'] = "-,-";
	
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

	$strDisplayPost .= "<div id='".$strDisplayBodyID."'>"; 
	
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

			//sets the post format for display
			$strDisplayPost .= whatsNewDisplayPostings(get_post(get_the_ID()),$strPostFormat);
		}//end of while loop

		//checks if the paging should be display
		if($boolDisplayPaging == true || $boolDisplayPaging == "on")
			//does the paging 
			$strDisplayPost .= whatsNewDisplayPaging($intDisplayNumberOfPostPerPage, $queryPost->found_posts, 1, $strDisplayBodyID, $boolDisplayPaging, $strPostStatus, $strTag, $strFormat, $strSearchText, $strPostFormat);
	}//end of if
	else 
	{
		//checks if a not found is need for display as sometimes it is not need
		if($boolDisplayNotFound == true || $boolDisplayNotFound == "on")
		{
			$strDisplayPost .= "<article id='post-0' class='post no-results not-found'>". 
				"<div class='entry-content divPostContentContainer divPostContentSingleContainer'>". 
					"<div id='divSearchNoResults'>".
						"<label>Apologies, but no results were found for this section.</label>".
					"</div>".						
				"</div>". 
			"</article>";
		}//end of if
	}//end of else 
		
	$strDisplayPost .= "</div>";
	
	//sets the query back to when the page loads
	wp_reset_query();
	
	return $strDisplayPost;
}//end of whatsNewDisplay()

//adds to the whats new query in order for event of past not to display as well as drafts and trash events
function whatsNewQuery($strWhere = '') 
{
	$strWhere .= " AND NOT EXISTS ".
	"(".
		"SELECT  from  WHERE . = ".
	")".
	" OR EXISTS ".
	"(".
		"SELECT  from  WHERE  >= '".date('Y-m-d')."' AND  . =  AND ( = 'A')".
	")".$strWhere;

    return $strWhere;
}//end of whatsNewQuery()

//displays the paging for the total number of postings
function whatsNewDisplayPaging($intDisplayNumberOfPostPerPage, $intTotalPost, $intCurrentPage, $strDisplayBodyID, $boolDisplayPaging, $strPostStatus, $strTag, $strFormat, $strSearchText, $strPostFormat, $strCate = "selectCate", $strOnClickEndNotClose = "")
{
	$strDisplayPost = "";//holds the post being display

	//checks if the paging is need
	if($intTotalPost > $intDisplayNumberOfPostPerPage)
	{
		$intLastPage = ceil(($intTotalPost/$intDisplayNumberOfPostPerPage));//holds the number of pages need
		$intSkipPagesIndex = 0;//holds when to skip pages base how many from and to the current page to skip
		$intSkipPages = 5;//holds how many pages to skip
		$boolGoFromCurrent = false;//holds if the page is now go from the current page as to tell when it is need to do different caluations

		//checks if there is any override to the onclick if not then use
		//the default one
		if(empty($strOnClickEndNotClose) == true)
			$strOnClickEndNotClose = "sendCateogryPosting(&quot;".get_bloginfo('template_url')."/PurePHP/GetPostings.php&quot;,getDocID(&quot;".$strDisplayBodyID."&quot;),getDocID(&quot;".$strCate.$strPostFormat."&quot;),&quot;".$strTag."&quot;,&quot;".$strSearchText."&quot;,&quot;".$strFormat."&quot;,&quot;".$strPostFormat."&quot;,&quot;".$strPostStatus."&quot;,getDocID(&quot;selectSortBy".$strPostFormat."&quot;),&quot;".$boolDisplayPaging."&quot;, ".$intDisplayNumberOfPostPerPage;

		//opens divPagingContainer and sets the Prev Arrow
		$strDisplayPost .= "<div id='divPagingContainer'>".
			"<span class='spanPagelink ";
			
		//checks which Arrow to use a disable one or a active one
		if($intCurrentPage > 1)
			$strDisplayPost .= "spanPostPagingArrows' id='spanLeftPagingArrow'>". 
				"<a class='aPostPagingArrows' href='javascript:void(0)' onclick='".$strOnClickEndNotClose.",".($intCurrentPage - 1).");var tagWhatsNew = $(&quot;#aWhatsNewTop&quot;);var posWhatsNew = tagWhatsNew.position();$(&quot;body,html&quot;).animate({scrollTop: posWhatsNew.top}, 800);' title='Prev Page'>&lt;</a>";
		else
			$strDisplayPost .= "spanPostPagingDisable' id='spanLeftPagingArrowDisable'>". 
				"<label>&lt;</label>";
					
		$strDisplayPost .= "</span>";

		//goes around for each page page of intPerPageMax
		for($intIndex = 1;$intLastPage >= $intIndex;$intIndex++)
		{
			//checks if this is the current page if so then do not use a link
			if($intCurrentPage == $intIndex)
			{
				$strDisplayPost .= "<label class='divPostPagingCurrent spanPagelink aPagelink'>".$intIndex."</label>";
				
				//resets the Skip Pages Index
				$intSkipPagesIndex = 0;
				$boolGoFromCurrent = true;
			}//end of if
			//checks if the rest of the pages to the current pages or from it
			else if($intSkipPagesIndex == $intSkipPages && $intIndex < $intLastPage || ($intIndex - $intCurrentPage) >= (-1 * abs($intSkipPages)) && ($intIndex - $intCurrentPage) <= (-1 * abs($intSkipPages)) && $intIndex != 1)
				$strDisplayPost .= "<label class='divPostPagingSkip spanPagelink'>...</label>";
			//checks if the intIndex is the start or last page or the skip from $intSkipPages from or to the current page
			else if($intLastPage == $intIndex || $intSkipPagesIndex <= $intSkipPages && ($intIndex - $intCurrentPage) < $intSkipPages && ($intIndex - $intCurrentPage) >= (-1 * abs($intSkipPages)) || $intIndex == 1)
			{
				$strDisplayPost .= "<span class='divPostPaging spanPagelink'>".
				"<a class='aPagelink' href='javascript:void(0)' onclick='".$strOnClickEndNotClose.",".$intIndex.");var tagWhatsNew = $(&quot;#aWhatsNewTop&quot;);var posWhatsNew = tagWhatsNew.position();$(&quot;body,html&quot;).animate({scrollTop: posWhatsNew.top}, 800);' title='Goto Page ".$intIndex."'>".$intIndex."</a>". 
				"</span>";
			}//end of else if
	
			//checks if the $intSkipPagesIndex from $intSkipPages from or to the current page
			if(($intCurrentPage - $intIndex) <= $intSkipPages && ($intIndex - $intCurrentPage) >= (-1 * abs($intSkipPages)) && $boolGoFromCurrent == false || $boolGoFromCurrent == true)
				$intSkipPagesIndex++;
		}//end of for loop

		//for the Next Arrow
		$strDisplayPost .= "<span class='spanPagelink ";
				
		//checks which Arrow to use a disable one or a active one
		if($intCurrentPage < $intLastPage)
			$strDisplayPost .= "spanPostPagingArrows' id='spanRightPagingArrow'>". 
				"<a class='aPostPagingArrows' href='javascript:void(0)' onclick='".$strOnClickEndNotClose.",".($intCurrentPage + 1).");var tagWhatsNew = $(&quot;#aWhatsNewTop&quot;);var posWhatsNew = tagWhatsNew.position();$(&quot;body,html&quot;).animate({scrollTop: posWhatsNew.top}, 800);' title='Next Page'>&gt;</a>";
		else
			$strDisplayPost .= "spanPostPagingDisable' id='spanRightPagingArrowDisable'>". 
				"<label>&gt;</label>";

		//closes the Next Arrow and Container
		$strDisplayPost .= "</span>".
			"</div>";
	}//end of if

	return $strDisplayPost;
}//end of whatsNewDisplayPaging()

//displays the what's new postings as it is may need else where
function whatsNewDisplayPostings($postCurrent, $strPostFormat, $boolIsEvent = false)
{
	$strDisplayPost = "";//holds the post being display

	//checks if the user to set their own post format
	if(!empty($strPostFormat))
	{		
		require_once("content-".$strPostFormat.".php");

		//calls the funcation that will get and reutrn the post in a new format	
		$strDisplayPost .= call_user_func(array($strPostFormat, 'displayPostFormat'),$postCurrent);
	}//end of if
	else
	{
		$arrClass = get_post_class();//holds all of the articles class
		$strClassStudrct = "";//holds the stucurture of the class
		$strImageName = "";//holds the thumbnail of the posting
		$strPostTitle = $postCurrent->;//holds the post title
		$strPostDesc = get_the_excerpt();//holds the post Description
		$strPostDate = get_the_date();//holds the post date
		$strPostURL = get_permalink();//holds the url of the posst
		$intPostID = $postCurrent->ID;//holds the post id
		$boolDisplayShare = false;//holds if the share option should be display
		$eventDetailsCate = false;//holds the event category
		$cateList = false;//holds the post category
		$fileDocument = false;//holds the file document of the post if it is a post link
		$arrCaption = array();//holds of text split up by spaces to tell how many words are in the descriptiomn
		
		//checks if this is in a event area and should display the events data instead
		if($boolIsEvent == true)
		{
			$arrDate = explode("-",$postCurrent->);//holds the event date 0 = Year 1 = Month 2 = Day
			
			//sets the posting for be an event
			$strPostTitle = stripslashes_deep($postCurrent->);
			$strPostDate = date("F j, Y",mktime(0, 0, 0, $arrDate[1], $arrDate[2], $arrDate[0]));
			$intPostID = $postCurrent->;
		}//end of if
		else
			//sets the cateList for only post of files
			$cateList = get_the_category($intPostID);

		//checks if the post is a file from the file manager as it uses some of its elements
		if(get_post_format() == "link")
		{	
			//sets the fileDocument with the details of this post 
			$fileDocument = getDataColoumn("*", "", "Where  = ".$postCurrent->);

			$arrDate = explode("-",$fileDocument->);//holds the event date 0 = Year 1 = Month 2 = Day
						
			//disables the share option as the block users should be share files
			$boolDisplayShare = true;
									  
			//gets the parts of the date
			//0 = Year, 1 = Month and 2 = Day
			$arrDate = explode('-',$arrDate[0]);
								
			//sets the posting for be an event
			$strPostTitle = $fileDocument[0]->;
			
			//gets all of the words for the descriptin
			$arrCaption = explode(' ',stripslashes_deep($fileDocument[0]->));
			
			//checks if the number of words is more then 19
			if(count($arrCaption) > 19)
			{
				//resets the Caption for the first word that is in arrCaption
				$strCaption = $arrCaption[0];
				
				//goes around for the each other word that is in arrCaption for displays 
				for($intCaptionIndex = 1;$intCaptionIndex < 19;$intCaptionIndex++)
				{
					$strCaption .= " ".$arrCaption[$intCaptionIndex];
				}//end of for loop
	
				$strPostDesc = $strCaption."...";
			}//end of if
			else
				$strPostDesc = $fileDocument[0]->;
				
			//adds read more to display the link to the detail page
			$strPostDesc .= " <a href='".$strPostURL."' class='more-link'>Read More</a>";
		}//end of if
		else if ($postCurrent->post_type == 'page')
			$strPostDesc = get_post_meta($intPostID, '_aioseop_description', true);
			
		//gets the Category for this posting if it has one
		$eventDetailsCate = getEventCate($intPostID, $boolIsEvent);
		
		//checks if this is a event
		if ($eventDetailsCate != false)	
		{
			//sets the post url to the details page of the event
			$strPostURL = site_url()."/events-pd/event-details?ee=".$eventDetailsCate[0]->id;		
			
			//sets the description of the posting
			$strPostDesc = stripslashes_deep($eventDetailsCate[0]->);
			
			//changes the id and the is event as this is actully an event
			$intPostID = $eventDetailsCate[0]->id;
			$boolIsEvent = true;
		}//end of if
		
		//goes around for each class for the article and adds it to the $strClassStudrct 
		//in order to use the css for this article
		for($intIndex = 0;count($arrClass) > $intIndex;$intIndex++)
		{
			$strClassStudrct .= $arrClass[$intIndex]." ";
		}//end of for loop
		
		$strDisplayPost .= "<article id='post-".$intPostID."' class='".trim($strClassStudrct)."'>".
			"<div class='customContainer divPostContentContainer'>".
				  "<div class='customLeft divPostContentLeft' onMouseOver='javascript:toggleLayer(&quot;divImageShareIcon".$intPostID."&quot;,&quot;&quot;,&quot;&quot;);' onMouseOut='javascript:toggleLayer(&quot;divImageShareIcon".$intPostID."&quot;,&quot;&quot;,&quot;&quot;);'>";
	  
		//gets the thumbnail of the posting
		$strImageName = getEventThumbnail($intPostID, $boolIsEvent);
		
		//checks if the share icons should display
		if($boolDisplayShare === false)
				$strDisplayPost .= displayShareIcons($intPostID, false, $boolIsEvent);
		
		//gets the share icons for this posting's thumbnail
		$strDisplayPost .= "<a href='".$strPostURL."'";
		
		//checks if this is a link if so then uses a different field in order to use a link
		if(($fileDocument != false) && $fileDocument[0]-> == 3 || $fileDocument[0]-> == 2)
			$strDisplayPost .= " target='_blank'";
		
		$strDisplayPost .= ">";
				
		//check if the post has a Post Thumbnail assigned to it.
		//if so then displays it 
		if (has_post_thumbnail() || empty($strImageName) == false || $fileDocument != false)
		{							
			//checks if this is a event or a normal post as events need to displa the venuse
			if (empty($strImageName) == false)
				$strDisplayPost .= "<img src='".$strImageName."' alt='".stripslashes_deep($strPostTitle)."' />";
			//checks if this is a file and if so add the document icon for it
			else if($fileDocument != false)
				$strDisplayPost .= "<img alt='".$fileDocument[0]->file_display_name."' src='".get_bloginfo('template_url')."/images/".displayFileIcon($fileDocument[0])."' class='imgWhatsNewFiles' />";
			//checks to maek sure that only normal posting get the author in order not to confuess the user
			else if($eventDetailsCate == false)
				$strDisplayPost .= get_the_post_thumbnail($intPostID, 'thumbnail');
		}//end of if
		else
			$strDisplayPost .= "<img src='".get_bloginfo('template_url')."/images/ETTLogoPlaceholder.png' alt='".stripslashes_deep($strPostTitle)."' />";
		
		$strDisplayPost .= "</a>";
						
		$strDisplayPost .= "</div>".
			"<div class='customRight divPostContentRight'>".
				"<a class='entry-title' href='".$strPostURL."' title='Permalink to ".the_title_attribute('echo=0')."' rel='bookmark'";
		
				$strDisplayPost .= ">".$strPostTitle."</a>";
				
				//checks if the post type is a post as it uses dates and authors to display them
				if ($postCurrent->post_type == 'post')
				{
					$strDisplayPost .= "<div class='entry-meta'>";

					//checks if this is a event if so then display its event
					if ($eventDetailsCate != false)
						//gets the Date for this event if it has one
						$strDisplayPost .= displayEventDateVenue($eventDetailsCate[0]->);
					else
						$strDisplayPost .= "<label class='lblDisplayPostDate'>".esc_html($strPostDate)."</label>";
						
					//checks if this a normal post if os then display its author
					if($eventDetailsCate == false && $fileDocument == false)
						$strDisplayPost .= "<br/><label>".get_the_author()."</label>";
						
					$strDisplayPost .= "</div>";
				}//end of if
				
				$strDisplayPost .= "<div class='entry-content'>".
					"<p>";
	
				//checks if this is a event if so then use the event details page instead
				if ($eventDetailsCate != false || $postCurrent->post_type == 'page')
				{
					//checks if there is anything in the array if not then 
					$arrCaption = explode(' ',$strPostDesc);
					
					//checks if the Caption is larger then 19 words if so then displays the first 50 words
					if(count($arrCaption) > 19)
					{
						//resets the Caption for the first word that is in arrCaption
						$strCaption = $arrCaption[0];
						
						//goes around for the each other word that is in arrCaption for displays 
						for($intCaptionIndex = 1;$intCaptionIndex < 19;$intCaptionIndex++)
						{
							$strCaption .= " ".$arrCaption[$intCaptionIndex];
						}//end of for loop
						
						//ends the strCaption with dots to tell that there is more items to dispaly							
						$strDisplayPost .= $strCaption."...";
					}//end of if
					else
						$strDisplayPost .= $strPostDesc;
						
					//adds read more to display the link to the detail page
					$strDisplayPost .= " <a href='".$strPostURL."' class='more-link'>Read More</a>";
				}//end of if
				else
					$strDisplayPost .= $strPostDesc;
					
				$strDisplayPost .= "</p>".
				"</div>".
				"<span class='cat-links'>";
		
				//checks if this is a event or a normal post as events need to displa the venuse
				if ($eventDetailsCate != false)
				{ 
					//does around for each cateogry found and display them 
					foreach ($eventDetailsCate as $categoryName) 
					{							
						$strDisplayPost .= "<div class='spanPostTag customLeft'>".
							$categoryName->category_name.
							"</div>";
					}//end of foreach
				}//end of if
				else										
				{				
					//does around for each cateogry found and display them 
					foreach ($cateList as $categoryName) 
					{
						//checks if the cateogry id is not being used
						//and the category is a non parent if so then skip this role
						if($categoryName-> == 0 && $categoryName-> != 1 || $fileDocument != false)	
							$strDisplayPost .= "<div class='spanPostTag customLeft'>".
								"<a href='".site_url()."/category/".$categoryName->."'>".$categoryName->."</a>".
							"</div>";
					}//end of foreach
				}//end of else
				
				$strDisplayPost .= "</span>";
									
		$strDisplayPost .= "<div class='customFooter'></div>".
				"</div>".
				"<div class='customFooter divPostContentFooter'></div>".
			"</div>".
		"</article>";
	}//end of else

	return $strDisplayPost;
}//end of whatsNewDisplayPostings()