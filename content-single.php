<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage ETT
 * @since ETT 1.0
 */
 
$eventDetails = false;//holds the details of the event if there is one
$intEventID = $_GET['ee'];//holds the event id
$strFileURL = "";//holds the file url for any post that is format link
$fileDocument = false;//holds the file document of the post if it is a post link
$boolAccessPost = true;//holds if the user can access this post
$userCurrentDetails = wp_get_current_user();//holds the details of the current user

//Check to see if user id is registered for this event

//checks if there is a event id to use
if(isset($intEventID))
	//gets the Category for this posting if it has one
	$eventDetails = getEventCate($intEventID, true); 
//checks if the post is a file from the file manager as it uses some of its elements
else if(get_post_format() == "link")
{
	$strFileURL = site_url()."/members/login";//holds of the url for the file if it is resetuted or not			
	
	//sets the fileDocument with the details of this post 
	$fileDocument = getDataColoumn("*", "", "Where  = ".get_the_ID());
	
	$arrDate = explode("-",$fileDocument->file_date);//holds the event date 0 = Year 1 = Month 2 = Day

    $arrDocumentType = $fileDocument[0]->file_category_name;//holds document category

	//gets the parts of the date
	//0 = Year, 1 = Month and 2 = Day
	$arrDate = explode('-',$arrDate[0]);
	
	//gets if the user can access this post and file
	$boolAccessPost = checkFileAccess($fileDocument[0]->);
	
	//checks if the user can access this url if not and they are able to log in
	//then it should skip 
	if($boolAccessPost == true)
	{
		//checks if this is a link if so then uses a different field in order to use a link
		if($fileDocument[0]-> == 3 || $fileDocument[0]-> == 2)
			$strFileURL = $fileDocument[0]->;
		else
			$strFileURL = site_url()."?wpfb_dl=".$fileDocument[0]->;
	}//end of if
	else if(is_user_logged_in())
		$strFileURL = "javascript:void(0);";
			
	//sets the posting for be an event
	//$strPostDate = date("F j, Y",mktime(0, 0, 0, $arrDate[1], $arrDate[2], $arrDate[0]));
}//end of if
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php 
	//checks if this user can access this post as there is times where the user
	//can access it
	if($boolAccessPost == true)
	{
        //check to see if user can access content of the post based on Content permission plugin
        if (ett_members_content_permissions_protect($content) == $content)
        {
	    ?>
            <header class="entry-header">
                <div class="entry-title divContentTitle" id="divSinglePostTitle">
                    <?php 
                        //checks if this is a event if so then use the event details page instead
                        if ($eventDetails != false)
                            echo "<label>".stripslashes_deep($eventDetails[0]->)."</label>";
                        else if($fileDocument != false)
                            echo "<label>".$fileDocument[0]->."</label>";
                        else
                            the_title(); 
                    ?>
                </div>
		
		    <?php 
			    //checks if there is a event id to use
			    if(isset($intEventID))
				    echo displayEventDateVenue($intEventID);
			    else
			    {
				    //checks if there is a file or normal post and display its meta data
				    if($fileDocument != false)
					    echo "<label class='lblEventDate'>".esc_html(get_the_date())."</label>";
				    else if (get_post_type() == 'post')
					    echo "<div class='entry-meta' id='divSinglePostMeta'>".
						    "<label class='lblEventDate'>".esc_html(get_the_date())."</label>".
						    "<br/>".
						    "<label class='lblEventPlace'>".get_the_author()."</label>".
				      "</div>";
			    }//end of else 
            ?>
	    </header><!-- .entry-header -->

	    <div class="entry-content divPostContentContainer divPostContentSingleContainer">
		    <?php 
		    //checks if this is a event if so then use the event details page instead
		    if ($eventDetails != false)
		    {
			    $cateEvent = get_the_category($eventDetails[0]->);//holds all of the cateogries this post belongs to

			    //deserialize the text in the event meta in order to read the value in it
			    $eventCurrentMeta = unserialize($eventDetails[0]->);
	
			    //checks if there is a image to display this event
			    if(empty($eventCurrentMeta['event_thumbnail_url']) == false) 
				    echo "<img src='".$eventCurrentMeta['event_thumbnail_url']."' alt='".stripslashes_deep($eventDetails[0]->)."' />";
				
			    //checks if this is a event and if it is part of a committees
			    if($cateEvent != null)
			    {
				    //goes around for each category that the post belong to and if
				    //it is a committeees then display a link back to the committee page
				    foreach($cateEvent as $cateDetails)
				    {
					    //checks if this the parent is in the committee 
					    //if so then display the link
					    if($cateDetails-> == 36)
						    echo "<div class='divEventCommittee'>".
							    "<a href='".site_url()."/committees/".$cateDetails->."'>".$cateDetails->."</a>".
						    "</div>";
				    }//end of foreach
			    }//end of if
			
			    echo "<div id='divEventDetailsDesc'>".str_replace("\n","<br/>",stripslashes_deep($eventDetails[0]->))."</div>";
		    }//end of if
		    else if($fileDocument != false)
			    echo "<div id='divEventDetailsDesc'>".str_replace("\n","<br/>",stripslashes_deep($fileDocument[0]->))."</div>";
		    else
		    {
			    //checks if there is a image to display this event 
			    if(has_post_thumbnail(get_the_ID())) 
				    echo get_the_post_thumbnail(get_the_ID(), 'full');
		
			    //displays the content of this posting
			    echo the_content();
		    }//end of else
		    ?>
        
             <div class="customContainer" id="divSingleFooterContainer">
                <div class="customLeft" id="divSingleFooterLeft">
                    <?php 
				    //checks if this is a event if so then use the event details page instead
				    if ($eventDetails != false)
				    {
					    $arrEventStatus = event_espresso_get_is_active($intEventID);//holds the current status of this event	
					    //checks the status of this event
					    //and displays information base on each status the event is in
					    switch ($arrEventStatus['status']) 
					    {
						    case 'EXPIRED':
							    echo "<div class='divEventStatusProblem'>".
								    "<label class='lblFontBold'>This event has passed.</label>".
							    "</div>";
						    break;
						    case 'REGISTRATION_CLOSED':
							    echo "<div class='divEventStatusProblem'>".
								    "<label class='lblFontBold'>We are sorry but registration for this event is now closed.</label>".
							    "</div>";
						    break;
						    case 'REGISTRATION_NOT_OPEN':
							    $arrRegStartDate = explode("-",$eventDetails[0]->);//holds the event start date 0 = Year 1 = Month 2 = Day

							    echo "<div class='divEventStatusProblem'>".
								    "<label class='lblFontBold'>We are sorry but this event is not yet open for registration.</label>".
								    "<br />".
								    "<label class='lblFontBold'>You will be able to register starting ".
									    date("F j, Y",mktime(0, 0, 0, $arrRegStartDate[1], $arrRegStartDate[2], $arrRegStartDate[0])).
								    "</label>".
							    "</div>";
						    break;
						    default:
							    $boolIsSecetDisplay = 1;//holds if the button should be display for a user who uses this link
							    //checks if it is false if so then set it to zero meaning false
							    if(isset($_GET['sp']) == false)
							    {
								    //checks if this event has a waiting list
								    if($eventDetails[0]-> > 0)
								    {							
									    //checks if there is any people this list
									    //if there are some then skip everything
									    if(count(getDataColoumn("", "", "WHERE  = ".$eventDetails[0]->)) > 0)
										    $boolIsSecetDisplay = 2;
									    else
										    //display the Just Waitng or regiter button
										    $boolIsSecetDisplay = 0;
								    }//end of if
								    else
									    //display the normal register button 
									    $boolIsSecetDisplay = 0;
							    }//end of if
							
							    //checks if there is a registerion form to display 
							    //if so then display the values buttons or labels for it
							    if($eventDetails[0]->display_reg_form == 'Y')
							    {
								    //checks if this is the secrt display is activate and that the user is loged in
								    if($eventDetails[0]-> == 'N' && $boolIsSecetDisplay == 1 || $eventDetails[0]-> == 'Y' && is_user_logged_in() && $boolIsSecetDisplay == 1)
								    {
									    echo "<a href='../events-pd/registration?ee=".$intEventID."'>".
										    "<div class='divGreenButton'>".
											    "Register Now!".
										    "</div>".
									    "</a>";
								    }//end of if
								    //checks if this is a members only event and if the user is loged in
								    else if($eventDetails[0]-> == 'N' || $eventDetails[0]-> == 'Y' && is_user_logged_in())
								    {
									    $registeredAttendee = getDataColoumn("", "", "Where  = ".$intEventID." AND  = '".$userCurrentDetails->."' AND  = 'Completed'");
                                        //if user is already registered then enable the Unregister button
									    if (count($registeredAttendee) > 0)
									    {
                                            if($registeredAttendee[0]-> == '0.00')
                                            {
										        echo "<a href='javascript:void(0);' onclick='sendUserUnregisterEvent(&quot;".get_bloginfo('template_url')."/PurePHP/DeleteUserEventRegistration.php&quot;,&quot;".$userCurrentDetails->."&quot;,&quot;".$intEventID."&quot;)'>".
											        "<div class='divGreenButton'>".
												        "Unregister".
											        "</div>".
										        "</a>";
                                            }
									    }//end of if
									    //if user is not registered then enable the Register button after check if this event has enough space
									    else if ($eventDetails[0]-> > count(getDataColoumn("*", "", "Where  = 'Completed' AND  = ".$intEventID)) && $boolIsSecetDisplay == 0)
									    {
										    echo "<a href='../events-pd/registration?ee=".$intEventID."'>".
											    "<div class='divGreenButton'>".
												    "Register Now".
											    "</div>".
										    "</a>";
									    }//end of else if
									    //if above event does not have enough space then it must have waitlist setup then enable waitlist link
									    else if ($eventDetails[0]-> > 0)
									    {
										    //check if user is regitered for the waitlist 
										    if (count(getDataColoumn("*", "", "Where  = ".$eventDetails[0]->." AND  = '".$userCurrentDetails->."' AND  = 'Completed'")) > 0)
										    {
											    echo "<div class='divEventStatusProblem'>".
												    "<label class='lblFontBold'>You are already registered for waitlist and will be contacted.</label>".
											    "</div>";
										    }//end of if
										    else if($boolIsSecetDisplay == 0)
										    {
											    echo "<a href='../events-pd/registration?ee=".$eventDetails[0]->."'>".
												    "<div class='divGreenButton'>
													    Join Waiting List
												    </div>
											    </a>";
										    }//end of else
										    else
											    echo "<div class='divEventStatusProblem'>".
												    "<label class='lblFontBold'>Registration is full. All purchases are final.</label>".
											    "</div>";
									    }//end of else if
									    else
										    echo "<div class='divEventStatusProblem'>".
											    "<label class='lblFontBold'>Registration is full. All purchases are final.</label>".
										    "</div>";
								    }//end of if
								    else
									    echo "&nbsp;";
							    }//end of if
							    else
								    echo "&nbsp;";
						    break;
					    }//end of switch
				    }//end of if
				    else
					    echo "&nbsp;";
				    ?>
                </div>
                <div class="customRight" id="divSingleFooterRight">
	        	    <?php 
				    //makes srue this is not a link post as it should not share a link
				    if($fileDocument == false)
				    {
					    //checks if this is a event if so then use the event details page instead
					    if ($eventDetails != false)
						    echo displayShareIcons($intEventID, true, true);
					    else
						    echo displayShareIcons(get_the_ID(), true); 
				    }//end of if
				    else
					    echo "&nbsp;";
				    ?>
                </div>
                <div class="customFooter" id="divSingleFooterFooter"></div>
            </div>
	    </div><!-- .entry-content -->

	    <footer class="entry-meta divSingleFooter">
		    <?php
			    //translators: used between list items, there is a space after the comma
			    $categories_list = get_the_category_list(__('</div><div class="spanPostTag customLeft">', 'ett'));

			    //translators: used between list items, there is a space after the comma
			    $tag_list = get_the_tag_list('',__('</div><div class="spanPostTag customLeft">','ett'));
			
			    //checks if the post is a file if so then do not display the oategories as
			    //the tags will use it instead
			    if($fileDocument == false)
			    {
				    //checks if this is a event or a normal post as events need to displa the venuse
				    if ($eventDetails != false)
				    {
					    //does around for each cateogry found and display them 
					    foreach ($eventDetails as $categoryName) 
					    {							
						    $utility_text .= "<div class='spanPostTag customLeft'>".$categoryName->."</div>";
					    }//end of foreach
				    }//end of if
				    else if ($categories_list != '')
					     $utility_text .= "<div class='spanPostTag customLeft'>".$categories_list."</div>";
			    }//end of if
			
			    //checks if there is any tags to display
			    if ($tag_list != '')
				     $utility_text .= "<div class='spanPostTag customLeft'>".$tag_list."</div>";
			
			    $utility_text .= "<div class='customFooter'></div>";
		
			    edit_post_link( __( 'Edit', 'ett' ), '<span class="edit-link">', '</span>' ); 
		    ?>
	    </footer><!-- .entry-meta -->
    
        <?php }//end of if
              else
	            {
		            echo "<header class='entry-header'>". 
				            "<h1 class='entry-title'>Access Denied</h1>". 
			            "</header>". 
			        "<div class='entry-content divPostContentContainer divPostContentSingleContainer'>". 
				        "<p>Apologies, you do not have sufficient permission to access this posting.";
				
		            if(is_user_logged_in() == false)
			            echo "<br /><br /><a href='".site_url()."/members/login?url=".htmlentities($_SERVER['REQUEST_URI'])."'>Click here</a> to login to access this content.";
		            echo 	"</p>".
			            "</div>";
	            }//end of else
        }//end of if 
	    else
	    {
		    echo "<header class='entry-header'>". 
				    "<h1 class='entry-title'>Access Denied</h1>". 
			    "</header>". 
			    "<div class='entry-content divPostContentContainer divPostContentSingleContainer'>". 
				    "<p>Apologies, you do not have sufficient permission to access this posting.";
				
		    if(is_user_logged_in() == false)
			    echo "<br /><br /><a href='".site_url()."/members/login?url=".htmlentities($_SERVER['REQUEST_URI'])."'>Click here</a> to login to access this content.";
				
		    echo 	"</p>".
			    "</div>";
	    }//end of else
    ?>
</article><!-- #post-<?php the_ID(); ?> -->