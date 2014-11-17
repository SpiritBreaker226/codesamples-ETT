// globle valuables I know it is dauagues however I need to feed two functions the same data please choose either Viemo or YouTube as your video provider to stop this

var intPageIndexTotal = 6;//holds the total number of videos for page
var intVimeoPageTotal = 1;//holds the Start of Where Vimeo Starts its Video Displays pages
var intPageTotal = 1;//holds the total pages
var intVideoTotal = 0;//holds the total Videos
var intPageIndex = 1;//Counts the total number of videos for page
var htmlDetailVideos = ['<div class="divDetailVideos">'];
  
function detailVideoBody(intIndex,entries,boolYouTube,strVideoExtraID)
{
	var title = "";
	var thumbnailUrl = "";
	var strDuration = "";
	var strOddVideoClass = "divVideoThumb";
	var strVideoTitle = "";//holds the videos title
	var strVideoDesc = "";//holds the videos description
	var strVideoURL = "";//holds the videos URL
	var strClass = "";//holds the class for each site
	var strLinkAction = "";//holds the link onClick action
	
	//checks if the video comse from YouTube or Vimzeo
	if(boolYouTube == true)
	{
		title = entries[intIndex].media$group.media$title.$t;
		
		strVideoTitle = entries[intIndex].media$group.media$title.$t;
		strVideoDesc = entries[intIndex].media$group.media$description.$t;
		strVideoURL = entries[intIndex].media$group.media$content[0].url;
		thumbnailUrl = entries[intIndex].media$group.media$thumbnail[2].url;
		strDuration = Math.floor(entries[intIndex].media$group.yt$duration.seconds / 60) + ":" + (entries[intIndex].media$group.yt$duration.seconds % 60).toFixed().pad(2, "0");
		strLinkAction = 'display_youtube(\'' + strVideoURL + '\',\'' + strVideoTitle.replace(/\'/g, '&rsquo;') + '\',\'' + strVideoDesc.replace(/\'/g, '\\&rsquo;').replace(/"/g, '&quot;').replace(/-/g, '&shy;').replace(/\r/g, '').replace(/\n/g, '<br/>') + '\');';
		strClass = " divYouTube";
	}//end of if 
	 
  //checks if intPageIndex is greater then intPageIndexTotal and if so then resets intPageIndex and closes the current div with class divPage
  if(intPageIndex > intPageIndexTotal)
  {
	  //checks if there is an odd number and not the first number in order to added in a clear for the two videos
	  //in order not to have the videoes line up
	  if(intPageIndex == 3 && intIndex > 0)
		  htmlDetailVideos.push('<div class="customFooter"></div>');
	  
	  intPageIndex = 1;
	  intPageTotal++;

	  htmlDetailVideos.push('</div><div class="divPage' + strClass + '" id="divPageID' + intPageTotal + '">');
  }//end of if
  
  //checks if intIndex is the first item and if so adds the first block it to
  if(intIndex == 0 && intPageIndex == 1)
	  htmlDetailVideos.push('<div class="divPage' + strClass + '" id="divPageID' + intPageTotal + '">');

	htmlDetailVideos.push('<div class="divVideoThumbPicDetails">' +	
			'<a href="javascript:void(0);" onclick="javascript:toggleLayer(\'divEmbedVideo\',\'divGrayBG\',\'objYouTube\');' + strLinkAction + '">' + 
				'<img src="', thumbnailUrl, '" alt="', title.replace(/\'/g, '&rsquo;'), '" />' + 
			'</a>' + 																																																																																																																																																															
		'</div>' + 
		'<div class="divVideoTitle">' + 
			'<a href="javascript:void(0);" onclick="javascript:toggleLayer(\'divEmbedVideo\',\'divGrayBG\',\'objYouTube\');' + strLinkAction + '" class="lblVideoTitle">', title.replace(/\'/g, '&rsquo;'), '</a>' + 																																																																																								
		'</div>' + 
	'</div>');

  	//checks if there is an odd number and not the first number in order to added in a clear for the two videos
	//in order not to have the videoes line up
	if(intPageIndex == 6 && intIndex > 0 || intPageIndex == 3 && intIndex > 0)
		htmlDetailVideos.push('<div class="customFooter"></div>');
  
  intPageIndex++;
  intVideoTotal++;
}//end of detailVideo()

function loadVideo(playerUrl, autoplay) {
	var params = { allowScriptAccess: "always", allowfullscreen: 'true' };
	var atts = { id: "objYouTube" };
  swfobject.embedSWF(
      playerUrl + '&rel=1&border=0&fs=&enablejsapi=1&playerapiid=ytplayer&' + (autoplay?1:0), 'player', '540', '505', '9.0.0', false, false, params,atts);
}//end of loadVideo()

function showDetailVideos(data) 
{
	var feed = data.feed;
	var entries = feed.entry || [];
		
	//goes around for each item in the YouTube item
	for (var intIndex = 0; intIndex < entries.length; intIndex++) 
	{
		detailVideoBody(intIndex,entries,true,'');
	}//end of for loop
	
	//this is close the divs and put them onto the page as it has to be here 
	//because the html does not carry back to showDetailVideos()
	  
	htmlDetailVideos.push('</div>');
	
	//gets the current total of intPageTotal in order to display Viemo items side by side with YouTube
	//resets the intPageTotal and intPageIndex for the Viemo turn
	intVimeoPageTotal = intPageTotal;
	intPageTotal = 1;
	intPageIndex = 1;
	
	//adds the YouTube items to the site
	if(document.getElementById('divVideo') != null)
		document.getElementById('divVideo').innerHTML = htmlDetailVideos.join('');
		  
	//resets the htmlDetailVideos as this is a different feed to set up
	htmlDetailVideos = ['<div class="divDetailVideo">'];
	
	var oldonload=window.onload;//holds any prevs onload function from the js file
	
	//gets the onload window event checks if there is a function that is already in there
	window.onload=function(){
		if(typeof(oldonload)=='function')
			oldonload();
	
		//checks if there is a video display first && getDocID('videoViemo') != null
		if(entries.length > 0 && getDocID('divVideo') != null)
		{
			var intEntries = 0;//holds the Entries index in order
			var strPage = 1;//holds the Page Number
			var strPlayer = "y";//holds the which player the user wants to use
			var strVideoTitle = entries[intEntries].media$group.media$title.$t.replace(/\'/g, '&rsquo;');							
			var hash = getUrlVars();//holds the URL variables
			
			//checks if the user wants to start a video and if so then puts the URL valiables into the JS valiables
			if(hash['v'] != null && hash['p'] != null && hash['y'] != null)
			{
				intEntries = hash['v'];
				strPage = hash['p'];
				strPlayer = hash['y'];
			}//end of if
	
			//checks which Player to use
			if(strPlayer == 'y')
				display_youtube(entries[intEntries].media$group.media$content[0].url,strVideoTitle,entries[intEntries].media$group.media$description.$t.replace(/\'/g, '&rsquo;'));
		}//end of if
	}//end of window.onload=function()
}//end of showDetailVideos()

function displayHiddenYouTube(new_url)
{
	var tagMedia = getDocID("objYouTube");
	var embedNode = document.createElement("embed");

	embedNode.setAttribute("id","embed_url");
	tagMedia.appendChild(embedNode);
    //&autoplay=1
	$('#object_url').replaceWith('<param id="object_url" name="movie" value="' +new_url+ '&rel=0" />');
    $('#embed_url').replaceWith('<embed id="embed_url" src="' +new_url+ '&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="630" height="505" flashvars="objYouTube" />');
    $('#shade').css('display', 'block');
    $('#youtube_player').css('display', 'block');
    $('#exit_youtube').css('display', 'block');
}//end of displayHiddenYouTubee()
	
function exit_youtube() {
   	$('#object_url').replaceWith('<param id="object_url" />');
   	$('#embedYouTube').replaceWith('<embed id="embedYouTube" />');
   	$('#shade').css('display', 'none');
   	$('#youtube_player').css('display', 'none');
   	$('#exit_youtube').css('display', 'none');		
}//end of exit_youtube()

//Vimeo's Videos

function getViemoVideo(url,oEmbedEndpoint,oEmbedCallback,strTitle,strDecsription) 
{
	$.getScript(oEmbedEndpoint + '?url=' + url + '&width=630&height=505&callback=' + oEmbedCallback);
		
	getDocID("lblVideoTitle").innerHTML = strTitle;
	getDocID("lblVideoDesc").innerHTML = strDecsription;
	
	getDocID("divEmbedVideo").style.display = 'block';
	getDocID("objDetailYouTube").style.display = 'none';
}//end of getVideo
	
function setupViemoGallery(entries) 
{
	var intEntries = 0;//holds the Entries index in order
	var strPlayer = "y";//holds the which player the user wants to use
		  
	var hash = getUrlVars();//holds the URL variables
	
	//checks if the user wants to start a video and if so then puts the URL valiables into the JS valiables
	if(hash['y'] != null && hash['v'] != null)
	{
		intEntries = hash['v'];
		strPlayer = hash['y'];
	}//end of if
	
	//checks which Player to use for the Viemo it has to be here in order to get the entries from the feed
	if(strPlayer == 'v')
		getViemoVideo(entries[intEntries].url,'http://vimeo.com/api/oembed.json', 'switchViemoVideo',entries[intEntries].title.replace(/\'/g, '&rsquo;'),entries[intEntries].description.replace(/\'/g, '&rsquo;'));
	
	// Add the videos to the gallery
	for (var intViemoIndex = 0; intViemoIndex < entries.length; intViemoIndex++) 
  	{
		detailVideoBody(intViemoIndex,entries,false,'Viemo');
	}//end of for loop

	//this is close the divs and put them onto the page as it has to be here 
	//because the html does not carry back to showDetailVideos()
	
	htmlDetailVideos.push('</div></div>');
	
	if(document.getElementById('videoViemo') != null)
		document.getElementById('videoViemo').innerHTML = htmlDetailVideos.join('');
}//end of setupViemoGallery()

function showViemoGallery(entries) 
{
	var html = ['<div class="videos">'];
  
  //goes around for each item to be display on the Homepage for YouTube
  for (var intIndex = 0; intIndex < entries.length; intIndex++) 
  {
	 var strVideoTitle = entries[intIndex].title;
	 var title = entries[intIndex].title.substr(0, 20)
	 var thumbnailUrl = entries[intIndex].thumbnail_small;
	 var strDuration = Math.floor(entries[intIndex].duration / 60) + ":" + (entries[intIndex].duration % 60).toFixed().pad(2, "0");
	  
	 html.push('<div class="customContent divVideoThumb"><div class="divBlackVideothumb" id="divViemoVideoThumb' + intIndex + '" onmouseout="changeDivImage(\'divViemoVideoThumb' + intIndex + '\',\'url(/Portals/_default/Skins/ck/images/videothumbblack.png)\');" onmouseover="changeDivImage(\'divViemoVideoThumb' + intIndex + '\',\'url(/Portals/_default/Skins/ck/images/videothumbred.png)\');"><div class="divVideoThumbPicDetails"><a href="Watch.aspx?v=' + intIndex + '&y=v&p=1"><img width="134" src="', thumbnailUrl, '" /></a><div class="textgrey videothumbtext">', strDuration, '</div></div></div><label class="textgrey lblVideoTitle">', strVideoTitle, '</label></div>');
	 	 
	//checks if the amount of itemd for display has been reach
	if(intIndex == 3)
		break;
  }//end of for loop
  
  html.push('<div class="customFooter"></div></div>'); 
  
 if(document.getElementById('videoViemo') != null)
	document.getElementById('videoViemo').innerHTML = html.join('');
}//end of showViemoGallery()

function switchViemoVideo(video) 
{
	$('#divEmbedVideo').html(unescape(video.html));
}//end of switchVideo()