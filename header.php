<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />

<?php  
$arrPostBanCategory = array();//holds the post ban cateogries from the search engien

//checks if this is a post page if so then get its postings
if(is_single())
{
	$arrCategories = get_the_category($post->ID);//holds all of the 
	
	//goes around checking each of the cateogries and finding the ban one
	foreach($arrCategories as $category) 
	{ 
		//checks if this is the ban category if so then put it into the array
    	if($category->cat_ID == 25)
			$arrPostBanCategory[] = $category->cat_ID;
	}//end of foreach
}//end of if

//checks if this is a page that is ban from indexing from a Search engien is_category() || is_tag() || 
if (in_array(25, $arrPostBanCategory)) 
	echo '<meta name="robots" content="noindex,nofollow" />';
?>

<title></title>

<script src="<?php bloginfo('template_url'); ?>/JS/Master.js"></script>

<?php
	$userBrowser = getBrowser();//holds the details of the user browser
	
	//checks if the user is not using the IE8 as there is any error with it and the javascript compare
	if($userBrowser['name'] == "Internet Explorer" && $userBrowser['version'] != "8.0" || $userBrowser['name'] != "Internet Explorer")
		echo "<script src='".get_template_directory_uri()."/JS/EHCscripts.js'></script>";
?>

<script type="text/javascript">
	$(document).ready(function()
	{		
		$('#divFlickr').jflickrfeed({
			limit: 3,
			qstrings: {
				method: 'flickr.photosets.getPhotos',
				user_id: '',
				api_key: '',
				photoset_id: '',
			},
			itemTemplate:
			'<div class="customLeft divLatestImages">' +
				'<a href="javascript:void(0);" onclick=\'javascript:toggleLayer("divLatestImage","divGrayBG","");getDocID("lblSetTotal").value = "{{total}}";setEvent("{{id}}",getDocID("lblLatestImageSetsLightBoxTitle").innerHTML,"{{image}}");\'>' + 
					'<img src="{{image_s}}" alt="{{title}}" id="{{counting}}" />' + 
				'</a>' +
			'</div>'
		});

		$('#social-stream').dcSocialStream({
			feeds: {
				twitter: {
					id: 'ElemTeachersTO'
				},
				facebook: {
					id: '165777096819193'
				},
				youtube: {
					id: 'ElemTeachersTO',
					out: 'thumb'
				},
				flickr: {
					id: '67282614@N05'
				}
			},
			rotate: {
				delay: 0
			},
			control: false,
			filter: true,
			wall: true,
			order: 'random',
			days: 15,
			iconPath: '<?php bloginfo('template_url'); ?>/images/SocialFeed/',
			imagePath: '<?php bloginfo('template_url'); ?>/images/SocialFeed/'
		});
	});
</script>

<?php

	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
    <div id="divPageBG">
		<div id="divPage">
            <div id="page" class="hfeed">
            	<div id="divGrayBG" class="divBasicHiddlenBackground"></div>
                <div class="divBasicHidden divBasicHiddenStyle divHeaderLB" id="divContactFamily">
                    <div class="customContainer divLightboxTitleContainer boardBottomSoild">
                        <div class="customLeft divLightboxTitleLeft">
                            <label>Contact Form</label>
                        </div>
                        <div class="customRight divLightboxTitleRight">
                            <div class="divClose">
                                <a href="javascript:void(0);" onClick="javascript:toggleLayer('divContactFamily','divGrayBG','');"><img src="<?php echo get_bloginfo('template_url') ?>/images/Form_Close.png" alt="Close"/></a>
                            </div>
                        </div>
                        <div class="customFooter divLightboxTitleFooter"></div>
                    </div>
                    <div class="divHiddlenBody">
                        <div class="divHiddlenBodyTopStyle">
                            <div class="customLeft divTo entry-title">
                                <label>To:</label>
                            </div>
                            <div class="customLeft divContactFormContentLeft">
                                <a id="aContactTextImage"></a>
                            </div>
                            <div class="customLeft divContactFormContentRight">
                                <div>
                                    <a class="entry-title" id="aContactTextName"></a>
                                </div>
                                <div id="divContactSectionNames"></div>                                    
                                <div class="ContactUsFormPhoneEmail">
                                    <div class="divETTExecutivesLabels customLeft">
                                        <label>T:</label>
                                    </div>
                                    <div class="divContactFormInfo customLeft">
                                        <label id="lblContactPhone"></label>
                                    </div>
                                    <div class="customFooter"></div>
                                    <div class="divETTExecutivesLabels customLeft">
                                        <label>E:</label>
                                    </div>
                                    <div class="divContactFormInfo customLeft">
                                        <a id="aContactEmail"></a>
                                    </div>
                                    <div class="customFooter"></div>
                                </div>
                            </div>
                            <div class="customFooter"></div>
                         </div>                         
                         <div class="divBasicMessage" id="divMessageFamily"></div>
                         <div class="divHiddlenBodyBottomStyle" id="divContactFamilyBody">
                            <div class="customLeft divFrom entry-title">
                                <label>From:</label>
                            </div>
                            <div class="customLeft ContactForm">
                                <div class="customLeft ContactFormLabel">
                                    <label>First Name *</label>
                                </div>
                                <div class="customLeft txtboxContactForm">
                                    <input type="text" id="txtFNameFamily"/>
                                </div>
                                <div class="customFooter"></div>
                                
                                <div class="customLeft ContactFormLabel">
                                    <label>Last Name *</label>
                                </div>
                                <div class="customLeft txtboxContactForm">
                                    <input type="text" id="txtLNameFamily"/>
                                </div>
                                <div class="customFooter"></div>
                                
                                <div class="customLeft ContactFormLabelEmail">
                                    <label>Email *</label>
                                </div>
                                <div class="customLeft txtboxContactForm">
                                    <input type="text" id="txtEmailFamily"/>
                                </div>
                                <div class="customFooter"></div>
                                
                                <div class="customLeft ContactFormLabelPhone">
                                    <label>Phone *</label>
                                </div>
                                <div class="customLeft txtboxContactForm">
                                    <input type="text" id="txtPhoneFamily"/>
                                </div>
                                <div class="customFooter"></div>
                                
                                <div class="customLeft ContactFormLabelYourMessage">
                                    <label>Your Message *</label>
                                </div>
                                <div class="customLeft txtboxContactForm">
                                    <textarea name="comments" id="txtYourMessageFamily"></textarea>
                                </div>
                                <div class="customFooter"></div>
                                
                                <div class="FormSend">
                                    <a href="javascript:void(0);" onClick="sendShareEMail(&quot;<?php echo get_bloginfo('template_url'); ?>/PurePHP/SendEmail.php&quot;,&quot;divMessageFamily&quot;,getDocID(&quot;divContactFamilyBody&quot;),getDocID(&quot;txtFNameFamily&quot;),getDocID(&quot;txtLNameFamily&quot;),getDocID(&quot;txtPhoneFamily&quot;),getDocID(&quot;txtEmailFamily&quot;),getDocID(&quot;txtYourMessageFamily&quot;),getDocID(&quot;aContactEmail&quot;))">
                                        <img src="<?php echo get_bloginfo('template_url') ?>/images/Form_Send.png" alt="Send" />
                                    </a>
                                </div>
                            </div> 
                            <div class="customFooter"></div>
                        </div>
                    </div>
                </div>
                <header id="branding" role="banner">
                    <h1 id="site-title" class="divJustHidden"><?php bloginfo( 'name' ); ?></h1>
                    
                    <div class="customContainer" id="divHeaderContainer">
                    	<div class="customLeft" id="divHeaderLeft">
                        	<a name="Top" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img alt="Elementary Teachers of Toronto" src="<?php bloginfo('template_url'); ?>/images/ETTLogo.png" /></a>
                        </div>
                     
                        <div class="customMiddle tweet" id="divHeaderMiddle">
                        	<?php if (is_home())
							{ ?>
                        		<div id="twitter"></div>
							<?php }//end of if ?>
                        </div>
                        <?php
						
							echo '<div class="customContainer" id=';
						
                        	//checks if the user is logged in and if so then display the Member Barner
					  		//if not then do display the Number Search
							if (is_user_logged_in())
                        		echo '"divHeaderMemberBarContainer';
					  		else
	                            echo '"divHeaderSearchContainer';
								
							echo '">';
							
							//checks if the user is logged in and if so then display the Member Barner
							if (is_user_logged_in())
							{ 
								$userDetials = wp_get_current_user();//holds the users details
								$userDetialsMeta = get_user_meta($userDetials->);//holds the users meta data
								$userGroups = getDataColoumn("", "", 'Where ( = "") and  LIKE "%\"'.$userDetials->.'\"%" or  LIKE "%['.$userDetials->.']%"');//holds all the user groups the user is in
							
							?>
	                            <div class="customLeft" id="divHeaderMemberBarLeft">
    	                            <?php
									//displays the users thumbnail
									echo "<a href='".site_url()."/members/member-profile'><img alt='".$userDetials->." ".$userDetials->."' src='";
									//checks if the user has a thumbnail if so display if not 
									//then display the placebholder
									if(empty($userDetialsMeta['userphoto_thumb_file']) == false)
									{
										$arrUploadDir = wp_upload_dir();//holds the location of the upload dir
										
										echo $arrUploadDir['baseurl']."/userphoto/".$userDetialsMeta['userphoto_thumb_file'][0];
									}//end of if
									else
										echo bloginfo('template_url')."/images/UserPlaceholder.png";
									
									echo "' /></a>";
									?>
    	                        </div>
        	                    <div class="customRight" id="divHeaderMemberBarRight">
                                	<label>Welcome <?php echo "<a href='".site_url()."/members/member-profile'>".$userDetials->." ".$userDetials->."</a>"; ?></label>

                                    <?php
									//checks if the user is part 'of a user group and if so then display 
									//that as well
									if(count($userGroups) > 0)
									{						
										$userGroups = getDataColoumn("", "", 'Where  = '.$userGroups[0]->);//holds all the user groups the user is in

										echo "<label>, ".$userGroups[0]->."!</label>";
									}//end of if
									?>
            	                </div>  
					  <?php }//end of if ?>     
                            <div class="customLeft" id="divHeaderSearchLeft">
                                <?php searchForm(); ?>
                            </div>

                            <div class="customRight" id="divHeaderSearchRight">
                                <div class="customContainer" id="divHeaderLoginContainer">
                                	<?php 
                                    //showing top pup up for all pages except login page
                                    $currURL = htmlentities($_SERVER["REQUEST_URI"]);
                                    if (strpos($currURL,'login') == false){
									//checks if the user is logged in or not
									if (!is_user_logged_in())
									{ ?>
                                        <div class="customLeft" id="divHeaderLoginLeft">
                                            <a id="aHeaderLogin" class="aHeaderLogin" href="javascript:void(0);" onClick="javascript:toggleLayer('divHeaderDropLogin','','');changeHeaderDropDown('divHeaderLoginContainerOpenDropDown','divHeaderLoginContainer','aHeaderLogin','imgHeaderLoginArrow','<?php bloginfo('template_url'); ?>',false);">Login</a>
                                        </div>
                                        <div class="customRight" id="divHeaderLoginRight">
                                            <a id="aHeaderLoginArrow" href="javascript:void(0);" onClick="javascript:toggleLayer('divHeaderDropLogin','','');changeHeaderDropDown('divHeaderLoginContainerOpenDropDown','divHeaderLoginContainer','aHeaderLogin','imgHeaderLoginArrow','<?php bloginfo('template_url'); ?>',false);"><img alt="" src="<?php bloginfo('template_url'); ?>/images/WhiteDownArrow.png" id="imgHeaderLoginArrow"/></a>
                                        </div>
                                        <div class="customFooter" id="divHeaderLoginFooter"></div>
                                    <?php }//end of if
									else 
									{ ?>
										<a href="<?php echo wp_logout_url(htmlentities($_SERVER['REQUEST_URI'])); ?>" class="aHeaderLogin">Logout</a>
	                                <?php }//end of else ?>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="customFooter" id="divHeaderSearchFooter"></div>
                            
                            <div id="divHeaderDropLogin" class="divJustHidden">
                                <div class='divMessageHomeLoginPopup' id='divMessageHomeLoginPopup'></div>
                                <div id='divMessageFromServerHomeLoginPopup'></div>
                                <?php
								//checks if this the login page if so then hide the form as it is already being used
								//on the page
								if ($_SERVER["PHP_SELF"] != '/ett/index.php/members/login')
								{
								?>
                                    <form action="<?php echo get_bloginfo('template_url') ?>/PurePHP/LoginAccess.php" method="post">
                                        <div id="divHeaderDropLoginBody">
                                            <div>
                                                <label>Email or ETFO Member #</label>
                                            </div>
                                            <div id="divDropLoginUsername">
                                                <input id='txtUserName' name="txtUserName" type="text" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" />
                                            </div>
                                            <div>
                                                <label>Password</label>
                                            </div>
                                            <div id="divDropLoginPassword">
                                                <input id='txtPassword' name="txtPassword" type="password" />
                                            </div>
                                            <div id="divDropLoginRemeber">
                                                <div class="customContainer" id="divHeaderLoginRemeberContainer">
                                                    <div class="customLeft" id="divHeaderLoginRemeberLeft">
                                                        <input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" />
                                                    </div>
                                                    <div class="customRight" id="divHeaderLoginRemeberRight">
                                                        <label id="lblRemember">Remember me on this computer</label>
                                                    </div>
                                                    <div class="customFooter" id="divHeaderLoginRemeberFooter"></div>
                                                </div>
                                            </div>
                                            <div id="divDropForgotButton">
                                                <a href="<?php echo site_url(); ?>/members/reset-password">Forgot your password?</a>
                                            </div>
                                            <div id="divDropLoginButton">
                                                <input type="submit" value="" class="memberLoginButton">
                                                <input type="hidden" id="hidURLRedirct" name="hidURLRedirct" value="<?php
												//checks if there is a redirect and that it is 
												if(isset($_GET['url']))
													echo $_GET['url'];
												else
													echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                                                ?>" />
                                            </div>
                                        </div>
                                    </form>
                                <?php }//end of if  ?>
                            </div>                 
                        </div>
                        
                        <div class="customRight" id="divHeaderRight">
                        	<div id="divHeaderRightSideBG">
                        		<img alt="" src="<?php bloginfo('template_url'); ?>/images/HeaderRightSideBG.png" />
                            </div>
                            
                            <div class="customContainer" id="divHeaderRightTabContainer">
                                <a href="
                                	<?php
    	                            //checks if the user is logged in or not
									if (is_user_logged_in())
		                                echo site_url()."?page_id=94";
                                	else
										echo site_url()."/members/login";
                                	?>
                                    ">
                                	<div class="customLeft" id="divHeaderRightTabLeft">
                                	    Members
                                	</div>
                                </a>
                                <a href="<?php echo site_url()?>?page_id=96">
                                	<div class="customRight" id="divHeaderRightTabRight">
                                	    Contact Us
                                	</div>
                                </a> 
                                <div class="customFooter" id="divHeaderRightTabFooter"></div>
                            </div>
                        </div>
                    	<div class="customFooter" id="divHeaderFooter"></div>
                    </div>
					<div id="divHeaderBottomBorder"></div>
                </header><!-- #branding -->
                <a name="top"></a>
				<div id="divMainBody" class="customContainer divMainBodyContainer">
                     <div class="divBasicHidden divBasicHiddenStyle divHeaderLB" id="divLatestImage">
                        <div class="customContainer divLightboxTitleContainer boardBottomSoild">
							<div class="customLeft divLightboxTitleLeft">
								<label id="lblLatestImageSetsLightBoxTitle" class="divJustHidden"></label>
                                <label>Feature Images</label>
							</div>
							<div class="customRight divLightboxTitleRight">
	                            <div class="divClose">
									<a href="javascript:void(0);" onClick="javascript:toggleLayer('divLatestImage','divGrayBG','objYouTube');"><img alt="Close" src="<?php bloginfo('template_url'); ?>/images/Form_Close.png"></a>
								</div>
							</div>
							<div class="customFooter divLightboxTitleFooter"></div>
						</div>
                        <div class="customContainer infoImageDetailsContainer">
                            <div class="customLeft infoImageDetailsLeft">
                                <a href="javascript:void(0);" class="img_prev" id="aLeftArrow"><img src="<?php bloginfo('template_url'); ?>/images/flickerPrevBtn.jpg" alt="Prev" /></a> 
                            </div>
                            <div class="customMiddle infoImageDetailsMiddle">
                                <img alt="" id="imgLightBoxTitle" />
                            </div>
                            <div class="customRight infoImageDetailsRight">
                                <a href="javascript:void(0);" class="img_next" id="aRightArrow"><img src="<?php bloginfo('template_url'); ?>/images/flickerNextBtn.jpg" alt="Next" /></a>
                            </div>
                            <div class="customFooter infoImageFooter"></div>
                            
                            <label id="lblSetTotal" class="divJustHidden"></label>
                            <label id="lblSetID" class="divJustHidden"></label>
                        </div>
                    </div><!-- end of Hidden Div -->         
                
                	<div class='divBasicHidden divBasicHiddenStyle divHeaderLB' id='divEmbedVideo'>
                    	<div class="customContainer divLightboxTitleContainer boardBottomSoild">
							<div class="customLeft divLightboxTitleLeft">
								<label id="lblYouTubeLightBoxTitle"></label>
							</div>
							<div class="customRight divLightboxTitleRight">
	                            <div class="divClose">
									<a href="javascript:void(0);" onClick="javascript:toggleLayer('divEmbedVideo','divGrayBG','objYouTube');"><img alt="Close" src="<?php bloginfo('template_url'); ?>/images/Form_Close.png"></a>
								</div>
							</div>
							<div class="customFooter divLightboxTitleFooter"></div>
						</div>
                        <div class="divHiddlenBody">
                            <object width="523" height="294" id="objEmbedYouTube">
                    	        <param id="object_url" />
                	            <param value="objYouTube" name="flashvars" />
            	                <param value="true" name="allowFullScreen" />
        	                    <param value="always" name="allowscriptaccess" />
    	                        <embed id="embedYouTube" />
	                        </object>
                            <div class="divVideoDesc">
	                            <label id="lblVideoDesc"></label>
                            </div>
                        </div>
                    </div>
                    
                    <!--Reset password pop for member page-->
                    
                    <div class="divBasicHidden divBasicHiddenStyle" id="divLoginPasswordReset">
                        <div class="customContainer divLightboxTitleContainer boardBottomSoild">
                            <div class="customLeft divLightboxTitleLeft">
                                <label class="divHeaderChangePasswordPopupMessage">Welcome to the ETT Website: Password Update.</label>
                            </div>
                            <div class="customRight divLightboxTitleRight">
                                <div class="divClose">
                                    <a href="javascript:void(0);" onClick="javascript:toggleLayer('divLoginPasswordReset','divGrayBG','');"><img src="<?php echo get_bloginfo('template_url') ?>/images/Form_Close.png" alt="Close"/></a>
                                </div>
                            </div>
                            <div class="customFooter divLightboxTitleFooter"></div>
                        </div>
                        <div class="divHiddlenBody">
                            <div class="divHiddlenBodyTopStyle">
                                <div class="customLeft divPasswordReset">
                                	<label class="divHeaderChangePasswordPopupMessage">Please take a moment to set a new password. This password will replace your temporary password.<br/><br/><span class="lblFontBold">Please be advised that all passwords must be at least six characters in length, and contain at least one numeric character and one alphanumeric character.</span></label>
								</div>
                            </div>
                            <div class="divBasicMessagePopUp" id="divMessagePopUp"></div>
                            <div class="divHiddlenBodyBottomStyleLogin divJustHidden" id="divLoginPopUpBody" style="display:block;">
	                            <div id="divMessageFromServerPopUp"></div>
                                <div class="divLoginPasswordResetForm">
                                    <div class="customLeft PasswordResetFormLabel">
                                    	<label>Old Password</label>
                                    </div>
                                    <div class="customLeft txtboxPasswordResetForm">
                                    	<input type="password" id="txtOldPassword"/>
                                    </div>
                                    <div class="customFooter"></div>
                                    
                                    <div class="customLeft PasswordResetFormLabel">
                                    	<label>New Password</label>
                                    </div>
                                    <div class="customLeft txtboxPasswordResetForm">
                                    	<input type="password" id="txtNewPassword"/>
                                    </div>
                                    <div class="customFooter"></div>
                                    
                                    <div class="customLeft PasswordResetFormLabel">
                                    	<label>Confirm New Password</label>
                                    </div>
                                    <div class="customLeft txtboxPasswordResetForm">
                                    	<input type="password" id="txtCNewPassword"/>
                                    </div>
                                    <div class="customFooter"></div>
                                </div>
                                <div class="FormSend">
                                	<?php 
									//checks if this is in the login page or other parts of the site
                                    if (htmlentities($_SERVER["PHP_SELF"])=='/members/login')
                                      	echo "<a href='javascript:void(0);' onClick='var tagPassword = getDocID(&quot;txtpassword&quot;); if (navigator.userAgent.indexOf(&quot;MSIE&quot;) != -1 && navigator.appVersion.indexOf(&quot;MSIE 8&quot;) != -1){tagPassword = getDocIDFromBottom(getDocID(&quot;divLoginBody&quot;),&quot;txtpassword&quot;,&quot;input&quot;);}sendLoginCredentialsFirstTime(&quot;".get_bloginfo('template_url')."/PurePHP/LoginAccess.php&quot;,getDocID(&quot;divLoginPopUpBody&quot;),&quot;divMessagePopUp&quot;,getDocID(&quot;divMessageFromServerPopUp&quot;), getDocID(&quot;txtusername&quot;),tagPassword";
                                    else
                                      	echo "<a href='javascript:void(0);' onClick='var tagPasswordHome = getDocID(&quot;txtpasswordHomeLogin&quot;); if (navigator.userAgent.indexOf(&quot;MSIE&quot;) != -1 && navigator.appVersion.indexOf(&quot;MSIE 8&quot;) != -1){tagPasswordHome = getDocIDFromBottom(getDocID(&quot;divHeaderDropLoginBody&quot;),&quot;txtpasswordHomeLogin&quot;,&quot;input&quot;);}sendLoginCredentialsFirstTime(&quot;".get_bloginfo('template_url')."/PurePHP/LoginAccess.php&quot;,getDocID(&quot;divLoginPopUpBody&quot;),&quot;divMessagePopUp&quot;,getDocID(&quot;divMessageFromServerPopUp&quot;), getDocID(&quot;txtusernameHomeLogin&quot;),tagPasswordHome";
                                            
                                        echo ", getDocID(&quot;txtOldPassword&quot;),getDocID(&quot;txtNewPassword&quot;),getDocID(&quot;txtCNewPassword&quot;),1);'>".
                                		"<img src='".get_bloginfo('template_url')."/images/Form_Send.png' alt='Reset Password' />".
                                    "</a>";
									?>
                                </div>
                           	</div>
                        </div>
   	                </div>
                    
                    <!--End of reset password for member page-->