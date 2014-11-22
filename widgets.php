<?php
/**
 * Learn more: http://codex.wordpress.org/Widgets_API#Developing_Widgets
 *
 * @package WordPress
 * @subpackage ETT
 * @since ETT 1.0
 */
 
//Displays the path to the page
class BannersRotation extends WP_Widget
{
//adds the widget to wordpress
add_action('widgets_init', create_function('', 'return register_widget("TeacherResourcesSidebar");')); 
 
//Displays the newest postings, events and documents
class WhatsNew extends WP_Widget
{
	//creates a widget for wordpress
	function WhatsNew()
	{
		$widget_ops = array( 'classname' => 'whatsnew', 'description' => __('Displays the newest postings, events and documents', 'ett' ) );
		$this->WP_Widget( 'whatsnew', __('What\'s New Section', 'ett'), $widget_ops);
		$this->alt_option_name = 'whatsnew';
	}//end of default constructor
 
 	//creates the form need for the admin area to update this widget
	function form($instance)
	{
		//extracts the arguments from the user if the are not being use then set its default
		$instance = wp_parse_args((array) $instance, array(
	      'title' => '',
	      'postformat' => '',
	      'sortby' => 'date',
	      'sort' => 'DESC',
	      'displaybodyid' => 'divWhatsNew',
	      'displaynumberofpostperpage' => 10,
		  'cateid' => '',
		  'tagname' => '',
		  'format' => '',
		  'searchtext' => '',
		  'headerextraclass' => '',
		  'poststatus' => '',
		  'displaytitle' => true,
		  'displaynotfound' => true,
		  'displayfilters' => true,
		  'displaypaging' => true,
		));//holds the args that will be used in any shortcode
						
		$strTitle = apply_filters('widget_title', $instance['title']);//holds the title
		$strPostFormat = $instance['postformat'];//holds the format of the post
		$strSortBy = $instance['sortby'];//holds the what is going to be sort by
		$strSort = $instance['sort'];//holds the what is going to be sort 'ASC' or 'DESC'
		$strDisplayBodyID = $instance['displaybodyid'];//holds the div id that can be sued to change the body in real time
		$intDisplayNumberOfPostPerPage = $instance['displaynumberofpostperpage'];//holds the number of post that will be display per page
		$strCategory = $instance['cateid'];//holds the Category of the post
		$strTag = $instance['tagname'];//holds the tag of the post for searching
		$strFormat = $instance['format'];//holds the format of the post for searching
		$strPostStatus = $instance["poststatus"];//holds the status of the post
		$strSearchText = $instance['searchtext'];//holds the search text
		$strHeaderExtraClass = $instance['headerextraclass'];//holds the extra class for the header
		$boolDisplayTitle = $instance['displaytitle'];//holds the if this title should be display
		$boolDisplayNotFound = $instance['displaynotfound'];//holds the if this Not Found Section should be display
		$boolDisplayFilters = $instance['displayfilters'];//holds the if the filters for search need to be display
		$boolDisplayPaging = $instance["displaypaging"];//holds the if paging should be display
	?>
    	<p>
        	<label for="<?php echo $this->get_field_id('title'); ?>">Title: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($strTitle); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('postformat'); ?>">Post Format: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('postformat'); ?>" name="<?php echo $this->get_field_name('postformat'); ?>" type="text" value="<?php echo attribute_escape($strPostFormat); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('sortby'); ?>">Sort By: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('sortby'); ?>" name="<?php echo $this->get_field_name('sortby'); ?>" type="text" value="<?php echo attribute_escape($strSortBy); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('sort'); ?>">Sort: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('sort'); ?>" name="<?php echo $this->get_field_name('sort'); ?>" type="text" value="<?php echo attribute_escape($strSort); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('displaybodyid'); ?>">Body ID: 
  			<input class="displaybodyid" id="<?php echo $this->get_field_id('displaybodyid'); ?>" name="<?php echo $this->get_field_name('displaybodyid'); ?>" type="text" value="<?php echo attribute_escape($strDisplayBodyID); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('displaynumberofpostperpage'); ?>">Number of Post Per Page: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('displaynumberofpostperpage'); ?>" name="<?php echo $this->get_field_name('displaynumberofpostperpage'); ?>" type="text" value="<?php echo attribute_escape($intDisplayNumberOfPostPerPage); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('cateid'); ?>">Search for Category ID: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('cateid'); ?>" name="<?php echo $this->get_field_name('cateid'); ?>" type="text" value="<?php echo attribute_escape($strCategory); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('tagname'); ?>">Search for Tag: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('tagname'); ?>" name="<?php echo $this->get_field_name('tagname'); ?>" type="text" value="<?php echo attribute_escape($strTag); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('format'); ?>">Search for Post Format: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" type="text" value="<?php echo attribute_escape($strFormat); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('poststatus'); ?>">Search for Post Status: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('poststatus'); ?>" name="<?php echo $this->get_field_name('poststatus'); ?>" type="text" value="<?php echo attribute_escape($strPostStatus); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('searchtext'); ?>">Search for Text: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('searchtext'); ?>" name="<?php echo $this->get_field_name('searchtext'); ?>" type="text" value="<?php echo attribute_escape($strSearchText); ?>" /></label>
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('headerextraclass'); ?>">Add CSS Class to Header: 
  			<input class="inputAdminExe" id="<?php echo $this->get_field_id('headerextraclass'); ?>" name="<?php echo $this->get_field_name('headerextraclass'); ?>" type="text" value="<?php echo attribute_escape($strHeaderExtraClass); ?>" /></label>
        </p>
        <p>
  			<input class="inputAdminExe" type="checkbox" <?php checked((bool) $instance['displaytitle'], true ); ?> id="<?php echo $this->get_field_id('displaytitle'); ?>" name="<?php echo $this->get_field_name('displaytitle'); ?>" />
			<label for="<?php echo $this->get_field_id('displaytitle'); ?>">Display Title</label>
        </p>
        <p>
  			<input class="inputAdminExe" type="checkbox" <?php checked((bool) $instance['displaynotfound'], true ); ?> id="<?php echo $this->get_field_id('displaynotfound'); ?>" name="<?php echo $this->get_field_name('displaynotfound'); ?>" />
			<label for="<?php echo $this->get_field_id('displaynotfound'); ?>">Display Not Found</label>
        </p>
        <p>
  			<input class="inputAdminExe" type="checkbox" <?php checked((bool) $instance['displayfilters'], true ); ?> id="<?php echo $this->get_field_id('displayfilters'); ?>" name="<?php echo $this->get_field_name('displayfilters'); ?>" />
			<label for="<?php echo $this->get_field_id('displayfilters'); ?>">Display Filters</label>
        </p>
        <p>
  			<input class="inputAdminExe" type="checkbox" <?php checked((bool) $instance['displaypaging'], true ); ?> id="<?php echo $this->get_field_id('displaypaging'); ?>" name="<?php echo $this->get_field_name('displaypaging'); ?>" />
			<label for="<?php echo $this->get_field_id('displaypaging'); ?>">Display Paging</label>
        </p>

	<?php
	}//end of form()
	
	//updates the content for this widget from the admin area
	function update($new_instance, $old_instance)
	{
		//gets the old instance
		$instance = $old_instance;
		
		//sets the new instance into the the old instance values
		
		//makes sure there is some data to use
		
		if(empty($new_instance['title']) == false)
			$instance['title'] = $new_instance['title'];
			
		if(empty($new_instance['postformat']) == false)
			$instance['postformat'] = $new_instance['postformat'];
			
		if(empty($new_instance['sortby']) == false)
			$instance['sortby'] = $new_instance['sortby'];
			
		if(empty($new_instance['sort']) == false)
			$instance['sort'] = $new_instance['sort'];
			
		if(empty($new_instance['displaybodyid']) == false)
			$instance['displaybodyid'] = $new_instance['displaybodyid'];
					
		if(empty($new_instance['displaynumberofpostperpage']) == false || $new_instance['displaynumberofpostperpage'] > 0)
			$instance['displaynumberofpostperpage'] = $new_instance['displaynumberofpostperpage'];
		
		if(empty($new_instance['cateid']) == false)
			$instance['cateid'] = $new_instance['cateid'];
			
		if(empty($new_instance['tagname']) == false)
			$instance['tagname'] = $new_instance['tagname'];
			
		if(empty($new_instance['format']) == false)
			$instance['format'] = $new_instance['format'];
			
		if(empty($new_instance['poststatus']) == false)
			$instance['poststatus'] = $new_instance['poststatus'];
			
		if(empty($new_instance['searchtext']) == false)
			$instance['searchtext'] = $new_instance['searchtext'];
			
		if(empty($new_instance['headerextraclass']) == false)
			$instance['headerextraclass'] = $new_instance['headerextraclass'];
				
		$instance['displaytitle'] = $new_instance['displaytitle'];
		$instance['displaynotfound'] = $new_instance['displaynotfound'];
		$instance['displayfilters'] = $new_instance['displayfilters'];
		$instance["displaypaging"] = $new_instance['displaypaging'];

		return $instance;
	}//end of update()
	
	//does the actully widget for the page
	function widget($args, $instance)
	{
		extract($args, EXTR_SKIP);
		
		$strTitle = '';//holds the title
		$strPostFormat = '';//holds the format of the post
		$strSortBy = 'date';//holds the what is going to be sort by
		$strSort = 'DESC';//holds the what is going to be sort 'ASC' or 'DESC'
		$strDisplayBodyID = 'divWhatsNew';//holds the div id that can be sued to change the body in real time
		$intDisplayNumberOfPostPerPage = 10;//holds the number of post that will be display per page
		$strCategory = '';//holds the Category of the post
		$strTag = '';//holds the tag of the post for searching
		$strFormat = '';//holds the format of the post for searching
		$strPostStatus = '';//holds the status of the post
		$strSearchText = '';//holds the search text
		$strHeaderExtraClass = '';//holds the extra class for the header
		$boolDisplayTitle = true;//holds the if this title should be display
		$boolDisplayNotFound = true;//holds the if this Not Found Section should be display
		$boolDisplayFilters = true;//holds the if the filters for search need to be display
		$boolDisplayPaging = true;//holds the if paging should be display
	
		//checks if any of those have a values to use

		if(empty($instance['title']) == false)
			$strTitle = apply_filters('widget_title', $instance['title']);
			
		if(empty($instance['postformat']) == false)
			$strPostFormat = $instance['postformat'];
			
		if(empty($instance['sortby']) == false)
			$strSortBy = $instance['sortby'];
			
		if(empty($instance['sort']) == false)
			$strSort = $instance['sort'];
			
		if(empty($instance['displaybodyid']) == false)
			$strDisplayBodyID = $instance['displaybodyid'];
		
		if(empty($instance['displaynumberofpostperpage']) == false || $instance['displaynumberofpostperpage'] > 0)
			$intDisplayNumberOfPostPerPage = $instance['displaynumberofpostperpage'];
			
		if(empty($instance['cateid']) == false)
			$strCategory = $instance['cateid'];
			
		if(empty($instance['tagname']) == false)
			$strTag = $instance['tagname'];
			
		if(empty($instance['format']) == false)
			$strFormat = $instance['format'];
			
		if(empty($instance['searchtext']) == false)
			$strSearchText = $instance['searchtext'];
			
		if(empty($instance['headerextraclass']) == false)
			$strHeaderExtraClass = $instance['headerextraclass'];
			
		if(empty($instance['poststatus']) == false)	
			$strPostStatus = $instance["poststatus"];
				
		if((bool) $instance['displaytitle'] == false)
			$boolDisplayTitle = (bool) $instance['displaytitle'];
	
		if((bool) $instance['displaynotfound'] == false)
			$boolDisplayNotFound = (bool) $instance['displaynotfound'];
			
		if((bool) $instance['displayfilters'] == false)
			$boolDisplayFilters = (bool) $instance['displayfilters'];

		if((bool) $instance['displaypaging'] == false)
			$boolDisplayPaging = (bool) $instance['displaypaging'];

		echo $before_widget;

		echo whatsNewDisplay($strTitle,$strPostFormat,$strSortBy,$strSort,$strDisplayBodyID,$strDisplayPost,$intDisplayNumberOfPostPerPage,$strCategory,$strTag,$strFormat,$strPostStatus,$boolDisplayTitle,$boolDisplayNotFound,$boolDisplayFilters,$boolDisplayPaging,$strSearchText,$strHeaderExtraClass);
		
		echo $after_widget;
	}//end of widget()
	
	//does the actully widget for the page
	function shortcode($atts)
	{
		//extracts the arguments from the user if the are not being use then set its default
		$arrArgs = shortcode_atts(array(
	      'title' => '',
	      'postformat' => '',
	      'sortby' => 'date',
	      'sort' => 'DESC',
	      'displaybodyid' => 'divWhatsNew',
	      'displaynumberofpostperpage' => 10,
		  'cateid' => '',
		  'tagname' => '',
		  'format' => '',
		  'searchtext' => '',
		  'headerextraclass' => '',
		  'poststatus' => '',
		  'displaytitle' => true,
		  'displaynotfound' => true,
		  'displayfilters' => true,
		  'displaypaging' => true,
		), $atts);//holds the args that will be used in any shortcode
		
		$strTitle = $arrArgs["title"];//holds the title
		$strPostFormat = $arrArgs["postformat"];//holds the format of the post
		$strSortBy = $arrArgs["sortby"];//holds the what is going to be sort by
		$strSort = $arrArgs["sort"];//holds the what is going to be sort 'ASC' or 'DESC'
		$strDisplayBodyID = $arrArgs["displaybodyid"];//holds the div id that can be sued to change the body in real time
		$intDisplayNumberOfPostPerPage = $arrArgs["displaynumberofpostperpage"];//holds the number of post that will be display per page
		$strCategory = $arrArgs["cateid"];//holds the Category of the post
		$strTag = $arrArgs["tagname"];//holds the tag of the post
		$strFormat = $arrArgs["format"];//holds the format of the post
		$strSearchText = $arrArgs["searchtext"];//holds the search text
		$strHeaderExtraClass = $arrArgs["headerextraclass"];//holds the extra class for the header
		$strPostStatus = $arrArgs["poststatus"];//holds the format of the post
		$boolDisplayTitle = $arrArgs["displaytitle"];//holds the if this title should be display
		$boolDisplayNotFound = $arrArgs["displaynotfound"];//holds the if this Not Found Section should be display
		$boolDisplayFilters = $arrArgs["displayfilters"];//holds the if the filters for search need to be display
		$boolDisplayPaging = $arrArgs["displaypaging"];//holds the if paging should be display
		
		//checks if to make sure that the DisplayNumberOfPostPerPage is at lest greater then zero
		if($arrArgs['displaynumberofpostperpage'] < 0)
			$intDisplayNumberOfPostPerPage = 0;
					
		return whatsNewDisplay($strTitle,$strPostFormat,$strSortBy,$strSort,$strDisplayBodyID,$strDisplayPost,$intDisplayNumberOfPostPerPage,$strCategory,$strTag,$strFormat,$strPostStatus,$boolDisplayTitle,$boolDisplayNotFound,$boolDisplayFilters,$boolDisplayPaging,$strSearchText,$strHeaderExtraClass);
	}//end of shortcode()
}//end of class

//adds the shortcode to access it in the admin section
add_shortcode('whatsnew', array('WhatsNew', 'shortcode'));

//adds the widget to wordpress
add_action('widgets_init', create_function('', 'return register_widget("WhatsNew");')); 
?>