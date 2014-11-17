/*
	This code is to allow Javascript to communcted to the Server in order to update it
*/

//
// Define a list of Microsoft XML HTTP ProgIDs.
//
var XMLHTTPREQUEST_MS_PROGIDS = new Array(
  "Msxml2.XMLHTTP.7.0",
  "Msxml2.XMLHTTP.6.0",
  "Msxml2.XMLHTTP.5.0",
  "Msxml2.XMLHTTP.4.0",
  "MSXML2.XMLHTTP.3.0",
  "MSXML2.XMLHTTP",
  "Microsoft.XMLHTTP"
);

//
// Define ready state constants.
//
var XMLHTTPREQUEST_READY_STATE_UNINITIALIZED = 0;
var XMLHTTPREQUEST_READY_STATE_LOADING       = 1;
var XMLHTTPREQUEST_READY_STATE_LOADED        = 2;
var XMLHTTPREQUEST_READY_STATE_INTERACTIVE   = 3;
var XMLHTTPREQUEST_READY_STATE_COMPLETED     = 4;
//

//
// Returns XMLHttpRequest object. 
//
function getXMLHttpRequest()
{
  var httpRequest = null;

  // Create the appropriate HttpRequest object for the browser.
  if (window.XMLHttpRequest != null)
    httpRequest = new window.XMLHttpRequest();
  else if (window.ActiveXObject != null)
  {
    // Must be IE, find the right ActiveXObject.
    var success = false;
    for (var i = 0;i < XMLHTTPREQUEST_MS_PROGIDS.length && !success;i++)
    {
      try
      {
        httpRequest = new ActiveXObject(XMLHTTPREQUEST_MS_PROGIDS[i]);
        success = true;
      }
      catch (ex)
      {}
    }
  }

  // Display an error if we couldn't create one.
  if (httpRequest == null)
    alert("Error in HttpRequest():\n\n"
      + "Cannot create an XMLHttpRequest object.");

  // Return it.
  return httpRequest;
}

//Adds text to any part of the body of a HTML
function addNode(tagParent,strText,boolAddToBack, boolRemoveNode)
{
  var strNode = document.createTextNode(strText);//holds the test which will be added
     
  //gets the properties of the node
  tagParent = getDocID(tagParent);
  
  //checks if the user whats to replace the node in order to start with a clean slate
  //it also checks if there is a chode node to replace
  if (boolRemoveNode == true && tagParent.childNodes.length > 0)
	//replaces the current node with what the user wants
	tagParent.replaceChild(strNode,tagParent.childNodes[0]);
  else
  {
  	//checks if the user whats to added to the back of the id or the front
  	if(boolAddToBack == true)
		tagParent.appendChild(strNode);
  	else
		//This is a built-in function of Javascript will add text to the beginning of the child
  		insertBefore(strNode,tagParent.firstChild);
  }//end of if else
  
  //returns the divParent in order for the user to use it for more uses
  return tagParent;
}//end of addNode()

//changes the login header area to display it in white background and black text change it back to normal
function changeHeaderDropDown(tagDropDownContainter,tagNormalContainter,tagHeaderLogin,tagImgHeaderLoginArrow,strArrowImageLoc,boolsMouseOver)
{
    //gets the properties of tagHeaderLogin, tagImgHeaderLoginArrow
    tagHeaderLogin = getDocID(tagHeaderLogin);
    tagImgHeaderLoginArrow = getDocID(tagImgHeaderLoginArrow);
    
    //checks if the Login Arrow is the black up arrow meaning that the drop down is being used
    if(tagImgHeaderLoginArrow.src == (strArrowImageLoc + "/images/BlackUpArrow.png"))
	{
		//checks if the function is being use as a mouse over so that the containter does not need to be change
		if(boolsMouseOver == false)
			getDocID(tagDropDownContainter).id = tagNormalContainter;
		
		//changes the login and arrow to be normal
		tagHeaderLogin.style.color = '#FFF';
		tagImgHeaderLoginArrow.src = strArrowImageLoc + "/images/WhiteDownArrow.png";
	}//end of if
	else
	{
		//checks if the function is being use as a mouse over so that the containter does not need to be change
		if(boolsMouseOver == false)
			getDocID(tagNormalContainter).id = tagDropDownContainter;
		
		//changes the login and arrow to be normal
		tagHeaderLogin.style.color = '#6f6f6f';
		tagImgHeaderLoginArrow.src = strArrowImageLoc + "/images/BlackUpArrow.png";
	}//end of else
}//end of changeImage()

//changes the image of tagImage to what is in strImageSrc
function changeImage(tagImage,strImageSrc)
{
    //gets the properties of tagImage
    tagImage = getDocID(tagImage);
    
    //checks if there is a properties
    if(tagImage != null)
        tagImage.src = strImageSrc;
}//end of changeImage()

//changes the Text Header and the Image in order for Picture Gallery can display the image fully
function changeImageLightBox(tagImage, tagLightBoxTitle, strImage, strImageID, strLightBoxTitle, tagTitleBar, strStyleName)
{
	//gets the properties of the tags
	tagImage = getDocID(tagImage);
	tagLightBoxTitle = getDocID(tagLightBoxTitle);
	tagTitleBar = getDocID(tagTitleBar);

	//checks if there is a LightBox Title to Change
	if(tagLightBoxTitle != null)
		tagLightBoxTitle.innerHTML = strLightBoxTitle;
		
	//checks if there is a tagTarget on the page and if there is a image to change
	if(tagImage !== null && strImage !== "")
	{
		tagImage.src = strImage;
		tagImage.alt = strImageID;
		
		console.log("Image Being Display: " + strImage);
	}//end of if
	
	//checks if there is a TItle Bar to move in order for the Image to be as big as it wants to be
	if(tagTitleBar != null)
	{
		var intTitleBarStyle = 0;
		
		//checks if the form is IE or the other broswers this is to see if it is need to grow th size to
		//fit the boarder and removes the px at the end of the area
		if (tagTitleBar.currentStyle)
			//IE
			intTitleBarStyle = parseInt(tagTitleBar.currentStyle[strStyleName].substring(0,tagTitleBar.currentStyle[strStyleName].length - 2));
		else
			//other broswers
			intTitleBarStyle = parseInt(document.defaultView.getComputedStyle(tagTitleBar,null).getPropertyValue(strStyleName).substring(0,document.defaultView.getComputedStyle(tagTitleBar,null).getPropertyValue(strStyleName).length - 2));

		//checks if it is need size needs to grow
		if(tagImage.width > intTitleBarStyle)
			tagTitleBar.style.width = (tagImage.width) + "px";
		else
			//removes the style
			tagTitleBar.style.width = '';
	}//end of if
}//end of changeImageLightBox()

//checks for all of the fields that need to be used are used
//It assumes the tagContiner already have the properties
function checkForRequested(tagMessage,tagContainer, strClassName,strTAGName)
{
	var arrTAG = tagContainer.getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer

	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = 0; arrTAG.length > intIndex; intIndex++) 
	{
		//checks if this is the a requested field if so then 
		//checks if there is any value in that the used as selected
		if(arrTAG[intIndex].className.indexOf(strClassName + " ") != -1)
		{
			//checks if there is a select tag
			if(strTAGName == "select")
			{
				//checks if there is a value
				if (getSelectOption(arrTAG[intIndex]) == "")
				{displayMessage(tagMessage,arrTAG[intIndex].title,true,true);
				$('body,html').animate({scrollTop: 350}, 800);
					return false;}
			}//end of if
			//checks if it is a textarea
			if(strTAGName == "textarea")
			{
				//checks if there is a value
				if (arrTAG[intIndex].value == "")
				{displayMessage(tagMessage,arrTAG[intIndex].title,true,true);
				$('body,html').animate({scrollTop: 350}, 800);
					return false;}
			}//end of if
			else
			{
				//finds which input type the user
				switch(arrTAG[intIndex].type)
				{
					case "text":
						//checks if there is a value
						if (arrTAG[intIndex].value == "")
						{displayMessage(tagMessage,arrTAG[intIndex].title,true,true);
						$('body,html').animate({scrollTop: 350}, 800);
							return false;}
					break;
					case "radio":
						//checks if there is a value
						if (getRadioCheck(arrTAG[intIndex].name) == "")
						{displayMessage(tagMessage,arrTAG[intIndex].title,true,true);
						$('body,html').animate({scrollTop: 350}, 800);
							return false;}
					break;
					case "checkbox":
						//checks if there is a value
						if (getRadioCheck(arrTAG[intIndex].name) == "")
						{displayMessage(tagMessage,arrTAG[intIndex].title,true,true);
						$('body,html').animate({scrollTop: 350}, 800);
							return false;}
					break;
				}//end of switch
			}//end of else
		}//end of if
	}//end of for loop
	
	return true;
}//end of checkForRequested()

//checks if the max attaend
function checkMaxAttend(strFileName, strMessage, strEventID, tagContainer, strClassName, strTAGName, intPageType)
{
	var htmlJavaServerObject = getXMLHttpRequest();//holds the object of the server
	var intNumberOfTickets = checkRegQTY(tagContainer, strClassName, strTAGName, intPageType);//holds the number of tickets for this user in theis event
	//var boolNextScreen = false;//holds the if the user can go to the the next screen
	
	//checks if there is any tickets
	if(intNumberOfTickets === 0)
	{
		displayMessage(strMessage,'Choose a Pricing Type',true,true);
		return false;
	}//end of if
	
	//Abort any currently active request.
	htmlJavaServerObject.abort();
	
	// Makes a request
 	htmlJavaServerObject.open("Post", strFileName, true);
	htmlJavaServerObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	htmlJavaServerObject.onreadystatechange = function(){
    	if(htmlJavaServerObject.readyState == 4 && htmlJavaServerObject.status == 200)
		{
			var strFromServer = htmlJavaServerObject.responseText;//holds the number of attends left
			var arrNumberTickets = strFromServer.trim().split("|");//holds the number for what of either the number tickets allowed to buy or number of tickets left and which one to use
			
			//checks if the user can add that many attends to an event
			//if it returns false then they have to many attendeds
			if(parseInt(strFromServer) > 0)
			{
				//checks if this is for either the number tickets allowed to buy or number of tickets left
				if(Boolean(arrNumberTickets[1]) === true)
					//tells the user they cannot get that many items
					displayMessage(strMessage, "The amount of tickets ordered you are allowed to buy is exceeded. You may purchase up to " + arrNumberTickets[0] + " tickets",true,true);
				else
					//tells the user they cannot get that many items
					displayMessage(strMessage, "The amount of tickets ordered exceeds the amount we have available. You may purchase up to " + arrNumberTickets[0] + " tickets",true,true);
			}//end of if
			else
			{
				//checks which page the user is on 1 = Reistration, 2 = Confirmation
				if(intPageType === 2)
					document.form1.submit();	
				else
				{
					//goes to the next setp
					duelToggleLayer("divRegStep2","divRegStep1","");
					getDocID("divTicketMessage").innerHTML = "";
				}//end of else
			}//end of if
		}//end of if
		else if(htmlJavaServerObject.readyState == 2 && htmlJavaServerObject.status == 500)
		{
		}//end of else if
	}//end of function()

    htmlJavaServerObject.send("EventID=" + strEventID + "&intNumberOfTickets=" + intNumberOfTickets);
	
	return true;
}//end of checkMaxAttend()

//checks if the entered was press as some 
function checkPressEnter(objKeybard)
{
    var intKeyboardNum = (objKeybard.keyCode ? objKeybard.keyCode : objKeybard.which);//holds the the number of the key that has been pressed

    //checks if the key was the enter key if so then return true
    if(intKeyboardNum == 13)
        return true;
    else
        return false;
}//end of checkPressEnter()

//checks if the Registration QTY has at least one 
function checkRegQTY(tagContainer, strClassName, strTAGName, intPageType)
{
	var arrTAG = tagContainer.getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer
	var intNumberOfTickets = 0;//holds the number of tickets for this user in theis event

	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = 0; arrTAG.length > intIndex; intIndex++) 
	{
		//checks if this is the a requested field if so then 
		//checks if there is any value in that the used as selected
		if(arrTAG[intIndex].className.indexOf(strClassName) != -1)
		{
			//checks which page the user is on 1 = Reistration, 2 = Confirmation
			if(intPageType === 2)
			{
				//checks if this QTY's value is greater then 0 if so then 
				//boolIsQTYAmountGood should be true and allow the user to go to the next screen
				if(parseInt(arrTAG[intIndex].innerHTML) > 0)
					//adds to the number of tickets the user whats
					intNumberOfTickets += parseInt(arrTAG[intIndex].innerHTML);
			}//end of if
			else
			{
				//finds which input type the user
				switch(arrTAG[intIndex].type)
				{
					case "text":
						//checks if this QTY's value is greater then 0 if so then 
						//boolIsQTYAmountGood should be true and allow the user to go to the next screen
						if(parseInt(arrTAG[intIndex].value) > 0)
							//adds to the number of tickets the user whats
							intNumberOfTickets += parseInt(arrTAG[intIndex].value);					
					break;
				}//end of switch
			}//end of else
		}//end of if
	}//end of for loop
	
	return intNumberOfTickets;
}//end of checkRegQTY()

//sets the school ID and display the other text box
function chooseSchool(intSchoolID, tagSchoolID, tagSchoolOther)
{
	//sets the id of school id
	tagSchoolID.value = intSchoolID;
	
	//checks if the school id is a number
	if(intSchoolID != "Other")
		//remove the other textbox from view
		tagSchoolOther.style.display = '';
	else
		//displays the other textbox
		tagSchoolOther.style.display = 'block';
}//end of chooseSchool()

function ChooseCommittee(strCommitteeName) 
{
    document.getElementById("txtUpdateCommittee").value = strCommitteeName.value;
}//end of ChooseCommittee()

//removes from view all tags in tagContainer with the expection of tagActive
//It assumes the tagActive and tagContiner already have the properties
function classToggleLayer(tagContainer,tagActive,strClassName,strTAGName)
{
	var arrTAG = tagContainer.getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer

	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = arrTAG.length - 1; intIndex > -1 ; intIndex--) 
	{
		//checks if the class name is the same as strClassName and it is not active if it is active then change the dispaly to block
		if(arrTAG[intIndex].className == strClassName && arrTAG[intIndex].id != tagActive.id)
			arrTAG[intIndex].style.display = arrTAG[intIndex].style.display? "":"";
		else if(arrTAG[intIndex].id == tagActive.id && tagActive.style.display == "" )
			arrTAG[intIndex].style.display = arrTAG[intIndex].style.display? "":"block";
	}//end of for loop
}//end of classToggleLayer()

//removes from view all tags in tagContainer with the expection of tagActive
//It assumes the tagActive and tagContiner already have the properties
function classToggleLayerChangeClass(tagContainer,tagActive,strActiveClassName,strClassName,strTAGName)
{
	var arrTAG = tagContainer.getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer
	
	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = arrTAG.length - 1; intIndex > -1 ; intIndex--) 
	{
		//checks if the class name is the same as strClassName and it is not active if it is active then change the dispaly to block
		if(arrTAG[intIndex].id != tagActive.id)
			arrTAG[intIndex].className = strClassName;
		else if(arrTAG[intIndex].id == tagActive.id)
			arrTAG[intIndex].className = strActiveClassName;
	}//end of for loop
}//end of classToggleLayerChangeClass()

//removes from view all tags in tagContainer with the expection of tagActive
//It assumes the tagActive and tagContiner already have the properties
function classToggleLayerChangeActiveClass(tagContainer, strActiveClassName, strClassName, strTAGName)
{
	var arrTAG = tagContainer.getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer
	
	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = arrTAG.length - 1; intIndex > -1 ; intIndex--) 
	{
		//checks if the class name is the same as the one we are searching for
		if(arrTAG[intIndex].className.indexOf(strClassName) == 0)
			//resets the class name to the current set
			arrTAG[intIndex].className = strActiveClassName;
	}//end of for loop
}//end of classToggleLayerChangeActiveClass()

//counts from view all tags in tagContainer
//It assumes the tagContiner already have the properties
function classToggleLayerCounting(tagContainer,strClassName,strTAGName)
{
	var arrTAG = tagContainer.getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer
	var intTag = 0;//holds the number of tags that is using the same class name in the tagContainer
	
	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = arrTAG.length - 1; intIndex > -1; intIndex--) 
	{
		//checks if the class name is the same as strClassName and if so then count it to the number of tags with the same class name
		if(arrTAG[intIndex].className == strClassName)
			intTag++;
	}//end of for loop
	
	return intTag;
}//end of classToggleLayerCounting()

//removes from view all tags in tagContainer with the expection of Image Active and adds the images to the non active image
//It assumes the tagActive and tagContiner already have the properties
function classToggleLayerImg(tagContainer,tagActive,strClassName,strTAGName,strActiveImg,strNonActiveImg)
{
	var arrTAG = tagContainer.getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer
	
	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = arrTAG.length - 1; intIndex > -1 ; intIndex--) 
	{
		//checks if the class name is the same as strClassName arrTAG[intIndex].className == strClassName && 
		if(arrTAG[intIndex].id != tagActive.id)
			arrTAG[intIndex].src = strNonActiveImg.format(arrTAG[intIndex].id.replace("img",""));
		else if(arrTAG[intIndex].id == tagActive.id)
			arrTAG[intIndex].src = strActiveImg;
	}//end of for loop
}//end of classToggleLayerImg()

//decodes str to be a normal string in order to read it
function decodeURL(strDecode)
{
     return unescape(strDecode.replace(/\+/g, " "));
}//end of decodeURL()

//removes all strTAGName in tagContainer
function deleteTags(tagContainer,strTAGName)
{
	//checks if there is a tagContainer to use
	if(tagContainer != null)
	{
		var arrTAG = tagContainer.getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer
	
		//goes around the making sure that there is a tag name on the page for this tagContainer
		//there is one then remove the first item and updates the array
		while(arrTAG.length > 0) 
		{
			//removes the first div found in the tagContainer from the pages 
			tagContainer.removeChild(arrTAG[0]);
			
			//updates the array with the new number of items still on the page
			arrTAG = tagContainer.getElementsByTagName(strTAGName)
		}//end of while loop
	}//end of if
}//end of deleteTags()

//does the display the a message in a on the page weather then an alert
function displayMessage(tagMessage,strMessText,boolAddToBack, boolRemoveNode)
{
	//gets the message properties and sets the text furthermore it does the display
	tagMessage = addNode(tagMessage,strMessText,boolAddToBack, boolRemoveNode);
	tagMessage.style.display = "block";	
	
	return tagMessage;
}//end of displayMessage()

//display text boxes for user edit or display the content
function displayEditUser(boolDisplayEdit,tagTextFName, tagTextLName, tagTextPhone, tagTextEmail, tagSchoolName, tagSavebtn, tagCancelBtn, tagLabelFName, tagLabelLName, tagLabelPhone, tagLabelEmail, tagLabelSchool, tagEditBtn, tagFNameHolder, tagLNameHolder, tagPhoneHolder, tagEmailHolder, tagSchoolHolder, tagOtherSchool)
{
    //checks if the user is going into a edit or display mode
	if(boolDisplayEdit == true)
	{
		tagTextFName.style.display = 'block';	
		tagTextLName.style.display = 'block';
		tagTextPhone.style.display = 'block';
		tagTextEmail.style.display = 'block';
		//tagTextSchool.style.display = 'block';
		tagSchoolName.style.display = 'block';
		
		//Hide all labels
		tagLabelFName.style.display = 'none';
		tagLabelLName.style.display = 'none';
		tagLabelPhone.style.display = 'none';
		tagLabelEmail.style.display = 'none';
		tagLabelSchool.style.display = 'none';
		
		//checks if the school id is a number
		if(getSelectOption(tagSchoolName) != "Other")
			//remove the other textbox from view
			tagOtherSchool.style.display = '';
		else
			//displays the other textbox
			tagOtherSchool.style.display = 'block';
		
		//displays all holder to the user in order for them to edit
		tagFNameHolder.className = '';
		tagLNameHolder.className = '';
		tagPhoneHolder.className = '';
		tagEmailHolder.className = '';
		tagSchoolHolder.className = '';
						
		//show save and cancel button and hides the edit button
		tagSavebtn.style.display = 'block';
		tagCancelBtn.style.display = 'block';
		tagEditBtn.style.display = 'none';
	}//end of if
	else
	{
		//Hide all text boxes
		tagTextFName.style.display = '';
		tagTextLName.style.display = '';
		tagTextPhone.style.display = '';
		tagTextEmail.style.display = '';
		//tagTextSchool.style.display = '';
		tagSchoolName.style.display = '';
		
		//checks if there is a first name to display
		if(tagLabelFName.innerHTML != "")
			//displays the frist name label
			tagLabelFName.style.display = 'block';
		else
			//removes the first name to not display
			tagFNameHolder.className = 'divJustHidden';
			
		//checks if there is a last name to display
		if(tagLabelLName.innerHTML != "")
			//displays the frist name label
			tagLabelLName.style.display = 'block';
		else
			//removes the last name to not display
			tagLNameHolder.className = 'divJustHidden';
			
		//checks if there is a phone to display
		if(tagLabelPhone.innerHTML != "")
			//displays the phone label
			tagLabelPhone.style.display = 'block';
		else
			//removes the phone to not display
			tagPhoneHolder.className = 'divJustHidden';
			
		//checks if there is a email to display
		if(tagLabelEmail.innerHTML != "")
			//displays the email label
			tagLabelEmail.style.display = 'block';
		else
			//removes the email to not display
			tagEmailHolder.className = 'divJustHidden';
		
		//checks if there is a school to display
		if(tagLabelSchool.innerHTML != "")
			//displays the school label
			tagLabelSchool.style.display = 'block';
		else
			//removes the school to not display
			tagSchoolHolder.className = 'divJustHidden';
			
		//hids the other school text box
		tagOtherSchool.style.display = 'none';
						
		//hide save and cancel button and displays the edit button
		divSavebtn.style.display = 'none';
		divCancelBtn.style.display = 'none';
		divEditBtn.style.display = 'block';
	}//end of else
}//end of displayEditUser()

//this is for the duel layers that sometimes is need
function duelToggleLayer(whichLayer,layer1,layer2)
{
	var activeLayer = "";//holds the active Layer	
	var style2 = "";//holds the style of layer1
	var style3 = "";//holds the style of layer2

	// this is the way the standards work
	if (whichLayer != ''){activeLayer = getDocID(whichLayer);}
	if (layer1 != ''){style2 = getDocID(layer1);}
	if (layer2 != ''){style3 = getDocID(layer2);}

	//Checks if there is an active layer
	if (activeLayer != "")
	{
		//checks if the activeLayer is already active and if so then skips code
		//since the layer cannot be turn off and leave a hole in the review layer
		if (activeLayer.style.display == "")
		{
			//removes the block from the display in order to make the layer to disapper	
			if (style2 != ''){style2.style.display = style2.style.display? "":"";}

			//checks if there is a style3
			if (style3 != ''){style3.style.display = style3.style.display? "":"";}
	
			//displays the new active Layer and updates its id
			activeLayer.style.display = activeLayer.style.display? "":"block";
		}//end of if
	}//end of if
}//end of duelToggleLayer()

//encodes str to a URL so it can be sent over the URL address
function encodeURL(strEncode)
{
	var strResult = "";
	
	for (intIndex = 0; intIndex < strEncode.length; intIndex++) {
		if (strEncode.charAt(intIndex) == " ") strResult += "+";
		else strResult += strEncode.charAt(intIndex);
	}
	
	return escape(strResult);
}//end of encodeURL()

//for the exevtives regoin map onClick does all of its events as there is many images that uses the same
//events
function executivesRegionMapClick(strImageName,strNewRegionValue)
{
	//changes the region
	sendExecutives("../../wp-content/themes/ETT/PurePHP/GetExecutives.php", getDocID("divByFamilyofSchools"), "divRegianlMap", null, 10, strNewRegionValue, 1, 0, "vw_executive_section_name", 0, "contactusbyfamiliesschools", strImageName, getDocID("inputCurrentSel"), "divRegianlMap", 0, 0, "", 1);
}//end of executivesRegionMapClick()

//for the exevtives regoin map onMouseOut does all of its events 
//as there is many images that uses the same events
function executivesRegionMapMouseOut(strImageName, boolIsOn)
{
	var strImageStatus = "Off";//holds which status the image should be on on blue or off gray
	
	//checks if this strImageName selected
	if(getDocID('inputCurrentSel').value != strImageName)
	{
		//checks if the boolIsOn is on,
		//if not then change it back to the current selected value
		if(boolIsOn == false && getDocID('inputCurrentSel').value != "")
			classToggleLayer(getDocID('divRegianlMap'),getDocID("img" + getDocID('inputCurrentSel').value),'divJustHidden','img');
		//change it back to the main map if there is no selected region
		else if(boolIsOn == false && getDocID('inputCurrentSel').value == "")
			classToggleLayer(getDocID('divRegianlMap'),getDocID("imgMain"),'divJustHidden','img');
		else
			classToggleLayer(getDocID('divRegianlMap'),getDocID("img" + strImageName),'divJustHidden','img');
			//changeImage("img" + strImageName,'/ett/wp-content/themes/ETT/images/Map/' + strImageName + '_' + strImageStatus + '.png');
	}//end of if
}//end of executivesRegionMapMouseOut()

//gives the user the message has been sent or not
function endMessage(tagMessage, tagBody, boolDisplayErrorMessage)
{
	//checks if there is a message if so then reset it
	if(tagMessage != "")
		//resets the message
		displayMessage(tagMessage,"",true,true);
		
	//checks if there is a body if so then display it again
	if(tagBody != null)
		//turn back on the body
		tagBody.style.display = '';
	
	//checks if the error message should be display	
	if(boolDisplayErrorMessage == true && tagMessage != "")
		displayMessage(tagMessage,"Unable to Connect to the Server.",true,true);
}//end of endMessage()

//checks gets the user to authize the site to post to their wall
function fbInit(strPostLink,strPostImage,strPostName,strPostDescrition)
{
	FB.init({appId: "338889296198162", status: true, cookie: true});
	
	//checks the status of the users log in to facebook
	FB.getLoginStatus(function(response) 
	{
		if (response.status === 'connected') 
		{
		  // the user is logged in and has authenticated your
		  // app, and response.authResponse supplies
		  // the user's ID, a valid access token, a signed
		  // request, and the time the access token 
		  // and signed request each expire
		  var uid = response.authResponse.userID;
		  var accessToken = response.authResponse.accessToken;
		  
		  fbPostToFeed(strPostLink,strPostImage,strPostName,strPostDescrition);
		}//end of if
		else
		{
			//tries to log in the user
			FB.login(function(response) {
			// handle the response
			}, {scope: 'publish_stream'});
		}//end of else
	});
}//end of fbInit()

function fbPostToFeed(strPostLink,strPostImage,strPostName,strPostDes) 
{
	//calling the API ...
	var obj = 
	{
		method: 'feed',
		link: strPostLink,
		picture: strPostImage,
		name: strPostName,
		description: strPostDes
	};

	function callback(response) 
	{
	}

	FB.ui(obj, callback);
}//end of fbPostToFeed()

//Hooks the footer to the bottom of the screen if it is to small
function footerHook(tagFooterBG, tagMainContentBody)
{
	var tagFooterBG = getDocID(tagFooterBG);//holds the Footer
	var tagMainContentBody = getDocID(tagMainContentBody);//holds the main place where the content is displayed

	//checks if there is a user is on the page
	if(tagFooterBG != null && tagMainContentBody != null)
	{
		//checks the size of the window is greater then the body and header combin if so
		//use the fix footer instead in order to make sure that the footer is always at the buttom of the screen
		if(tagMainContentBody.offsetHeight < 300)
			//changes the id to the fix footer one
			tagFooterBG.id = 'divFooterBGFixFooter';
		else
			tagFooterBG.id = 'divFooterBG';
	}//end of if
}//end of footerHook()

//gets the document properties in order to use them as there are many types of browers with different versions
function getDocID(tagLayer)
{
	var tagProp = "";//holds the proerties of tagLayer

	//gets the whichLayer Properties depending of the differnt bowers the user is using
	if (document.getElementById)//this is the way the standards work
		tagProp = document.getElementById(tagLayer);
	else if (document.all)//this is the way old msie versions work
		tagProp = document.all[tagLayer];
	else if (document.layers)//this is the way nn4 works
		tagProp = document.layers[tagLayer];
		
	return tagProp;
}//end of getDocID()

//finds a document ID starting from the bottom
//It assumes the tagContiner already have the properties
function getDocIDFromBottom(tagContainer,strFieldID,strTAGName)
{
	var arrTAG = tagContainer.getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer

	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = arrTAG.length - 1; intIndex > -1 ; intIndex--) 
	{
		//checks if this is the current field id if so then sent return it
		if(arrTAG[intIndex].id == strFieldID)
			return arrTAG[intIndex];
	}//end of for loop
}//end of getDocIDFromBottom()

//gets the locaton of the strAddress
function getLocationGeo(strAddress,mapGoogle)
{
	geocoder = new google.maps.Geocoder();//holds the geocoder service object
	
	//finds the location of the address and then displays it on the map
	geocoder.geocode({'address': strAddress}, function(results, status) 
	{
		//checks if the locaiton of the can be found on a map
		if (status == google.maps.GeocoderStatus.OK) 
		{
			//centers the map to the location
			mapGoogle.setCenter(results[0].geometry.location);
			
			var marker = new google.maps.Marker({
				map: mapGoogle,
				position: results[0].geometry.location
			});//end of marker
		}//end of if
		else
			alert('Geocode was not successful for the following reason: ' + status);
	});//end of geocoder.geocode
}//end of getLocationGeo()

//gets the prev and next
function getPhoto(strID) 
{
	$.getJSON("http://api.flickr.com/services/rest/?method=flickr.photos.getContext&format=json&api_key=767aedff3d1e97921629dffc9395dd48&photo_id=" + strID + "&amp;JSONcallback=?");//end of function()
}//end of getPhoto

//gets the radio button option from tagSelect
function getRadioCheck(tagSelect)
{
    var strSelectOption = "";//holds the select option the user has choosen
	
	//gets the name of the radio button as it uses the name to group all together
	tagSelect = document.getElementsByName(tagSelect);

    //goes around finding the current seleted value from tagSelection
    for (var intIndex = 0;intIndex < tagSelect.length; intIndex++)
    {
        if (tagSelect[intIndex].checked == true)
            strSelectOption = tagSelect[intIndex].value;
    }//end of for loop
   
    return strSelectOption;
}//end of getRadioCheck()

//gets the Registration QTY and Pricing from Ticket Type
function getRegQTYPricing(tagContainer, strClassName, strTAGName)
{
	var arrTAG = tagContainer.getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer
	var strQTYPricing = "";//holds the QTY and Pricning which are going over to the next page

	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = 0; arrTAG.length > intIndex; intIndex++) 
	{
		//checks if this is the a requested field if so then 
		//checks if there is any value in that the used as selected
		if(arrTAG[intIndex].className.indexOf(strClassName) != -1)
		{
			//finds which input type the user
			switch(arrTAG[intIndex].type)
			{
				case "text":
					var tagRowPricing = getDocID(arrTAG[intIndex].id.replace("inputRegQTY","rowPricing"));//holds the pricing of the row
					var tagRowPricingType = getDocID(arrTAG[intIndex].id.replace("inputRegQTY","rowPricingType"));//holds the pricing type of the row
					
					//combines the current QTY and Pricing that will need to go over to the next
					//page to display to the user  in the Order Summary
					strQTYPricing += arrTAG[intIndex].value + "@" + tagRowPricing.innerHTML + "@" + tagRowPricingType.innerHTML + "!";
				break;
			}//end of switch
		}//end of if
	}//end of for loop
	
	//remvoes the extra ! from strQTYPricing to provete an extra row in order summary
	strQTYPricing = strQTYPricing.substring(0, (strQTYPricing.length - 1));
	
	return strQTYPricing;
}//end of getRegQTYPricing()

//gets the Registration Pricing and Ticket Type
function getRegSingleQTYPricing(tagPriceOption)
{
	var strSelectedOpt = getSelectOption(tagPriceOption);//holds the pricing option the user seleted
	var arrSelected = strSelectedOpt.split("|");//holds the pricing and type of the what the user has selected
	
	//checks if there is any items in the Selectoin if not then
	//try using value for inputs as events will use a non drop down for one pricing type
	if(arrSelected.length <= 1 && tagPriceOption != null)
		arrSelected = tagPriceOption.value.split("|");

	//checks if arrSelected is three for the number of values that is used for the dropdown
	//as it uses the first one for the pricing id and the it is price name and pricing
	if(arrSelected.length == 3)
		return "1@" + arrSelected[2] + "@" + arrSelected[1];	
	//checks if arrSelected has and items to use if so then uses it
	else if(arrSelected.length > 0)
		return "1@" + arrSelected[0] + "@" + arrSelected[1];
	else
		return "";
}//end of getRegQTYPricing()

//gets the select option from tagSelect
function getSelectOption(tagSelect)
{
	var strSelectOption = "";//holds the select option the user has choosen

	//checks if there is a tagSelect to use
	if(tagSelect != null && tagSelect.options != null)
	{
		//goes around finding the current seleted value from tagSelection
		for (var intIndex = 0;intIndex < tagSelect.options.length; intIndex++)
		{
			if (tagSelect.options[intIndex].selected == true)
				strSelectOption = tagSelect.options[intIndex].value;
		}//end of for loop
	}//end of if
	
	return strSelectOption;
}//end of getSelectOption()

//Read a page's GET URL variables and return them as an associative array.
function getUrlVars()
{
    var vars = [], hash;//holds the valuable value from the URL
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');//holds the section of of the value and valuable

	//goes around for each valuable in the URL
    for(var i = 0; i < hashes.length; i++)
    {
		//splites the value and valuable into half
        hash = hashes[i].split('=');
		
		//adds the valuable into first part ant the value into the secound pard
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }//end of for loop

    return vars;
}//end of getUrlVars()

//is the Flick API function that get Photo has called
function jsonFlickrApi(data)
{	
	//checks if the data is good
	if(data.stat != "fail") 
	{
		//changes the left arrow event so that it can change the imaage when the user click on it
		$("#aLeftArrow").on("click", function() {
  			//checks if this is the last of the images
			if(data.prevphoto.id != "0")
				//sets the event for the next image
				setEvent(data.prevphoto.id,data.prevphoto.title,data.prevphoto.thumb.replace('_s', ''));				
		});
		
		//changes the right arrow event so that it can change the imaage when the user click on it
		$("#aRightArrow").on("click", function() {
  			//checks if this is the last of the images and if not then sets the event for the next image
			if(data.nextphoto.id != "0")
				setEvent(data.nextphoto.id,data.nextphoto.title,data.nextphoto.thumb.replace('_s', ''));
		});
	}//end of if
}//end of jsonFlickrApi()

//removes all new lines and replaces them with a <br/> html tag
function nl2br(strText)
{
	//checks if there is anything inside strText
	if (strText != "")
	{
 		var re_nlchar = "";//holds the different newlines that the OS uses
		strText = escape(strText);//in codes strText to be more like a URL to find the newlines
			
		//finds the either \r or \n or both since \r is for Linex and Apple and \n is for MS
		if(strText.indexOf('%0D%0A') > -1)
			re_nlchar = /%0D%0A/g ;
		else if(strText.indexOf('%0A') > -1)
			re_nlchar = /%0A/g ;
		else if(strText.indexOf('%0D') > -1)
			re_nlchar = /%0D/g ;
	
		//checks if there is any new lines in strText
		if (re_nlchar != "")
			//changes the strText back to normal with all of the newlines changes to <br/> tag
			return unescape(strText.replace(re_nlchar,'<br />'));
	}//end of if
	
	return strText;
}//end of nl2br()

//set up the form to not be used while sending the message
function preSendEMail(tagMessage,tagEMailBody)
{
	//display to the user their message is beening sent and disables the textbox area
	displayMessage(tagMessage,'Sending...',true,true);
	tagEMailBody.style.display = 'none';
}//end of preSendEMail()

//the search bar onForcus Event
function searchBarForce(tagContainer,strClassName,strTAGName,strColor,strValue)
{
	//gets the search bar properties
	var arrTAG = getDocID(tagContainer).getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer
	
	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = arrTAG.length - 1; intIndex > -1 ; intIndex--) 
	{
		//checks if the class name is the same as strClassName
		if(arrTAG[intIndex].className == strClassName)
		{
			//changes the color and text to blank when the user frocus on the textbox
		    arrTAG[intIndex].style.color = strColor;
    		arrTAG[intIndex].value = strValue;
		}//end of if
	}//end of for loop
}//end of searchBarForce()

//the search bar onblur Event(focus off)
function searchBarFocusOff(tagContainer,strClassName,strTAGName, strTAGValue, strColor)
{
	//gets the search bar properties
	var arrTAG = getDocID(tagContainer).getElementsByTagName(strTAGName);//holds all strTAGName in tagContainer

	//goes around the for each tag that getElementsByTagName found in tagContainter
	for(var intIndex = arrTAG.length - 1; intIndex > -1 ; intIndex--)
	{
		//checks if the class name is the same as strClassName
		if(arrTAG[intIndex].className == strClassName)
		{
			//checks if there is some there is not value to use if so then change the text back to 
			//the last input value
			if(arrTAG[intIndex].value=="")
			{
				//changes the color and text when the user leave the textbox
	    		arrTAG[intIndex].style.color = strColor;
   				arrTAG[intIndex].value = strTAGValue;
			}//end of if
		}//end of if
	}//end of for loop
}//end of searchBarFocusOff()

//re-creates the calendar
function sendCalendar(strFileName, tagBody, tagMonthOption, tagYearOption, intDayNameLength, intNextPerv, intFirstDay, tagEventCateOption, boolDisplayOtherMonth, boolSidebar, boolDisplayUserCal)
{
	var htmlJavaServerObject = getXMLHttpRequest();//holds the object of the server
	var intMonthOption = 0;//holds the month option
	var intYearOption = 0;//holds the year text
	var intEventCateOption = 0;//holds the event category id
		
	//Abort any currently active request.
	htmlJavaServerObject.abort();
	
	//checks if there is a month option on the srceen
	//and if so then gets its value
	if(tagMonthOption != null)
		intMonthOption = parseInt(getSelectOption(tagMonthOption));
		
	//checks if there is a year option on the srceen
	//and if so then gets its value
	if(tagYearOption != null)
		intYearOption = parseInt(getSelectOption(tagYearOption));
		
	//checks if there is a event option option on the srceen
	//and if so then gets its value
	if(tagEventCateOption != null)
		intEventCateOption = parseInt(getSelectOption(tagEventCateOption));
	
	var intNumDaysLastMonth = intMonthOption - 1;//holds the days for last month
	var intNumDaysNextMonth = intMonthOption + 1;//holds the days for next month
	
	//checks if the last of the month is zero menaing that this month is the first of the year
	//and should go to the last month of the year also adjusted the Year as well
	//and checks if the intNextPerv is 1 for Prev Use
	if(intNumDaysLastMonth < 1 && intNextPerv == 1)
	{
		intYearOption = intYearOption - 1;
		intMonthOption = 12;
	}//end of if
	else if(intNextPerv == 1)
		intMonthOption = intNumDaysLastMonth;
	
	//checks if the next of the month is zero menaing that this month is the last of the year
	//and should go to the first month of the year also adjusted the Year as well
	//and checks if the intNextPerv is 2 for Next Use
	if(intNumDaysNextMonth > 12 && intNextPerv == 2)
	{
		intYearOption = intYearOption + 1;
		intMonthOption = 1;	
	}//end of if
	else if(intNextPerv == 2)
		intMonthOption = intNumDaysNextMonth;
			
	// Makes a request
 	htmlJavaServerObject.open("Post", strFileName, true);
	htmlJavaServerObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	htmlJavaServerObject.onreadystatechange = function(){
    	if(htmlJavaServerObject.readyState == 4 && htmlJavaServerObject.status == 200)
		{
			//checks the selection to a new month and Year
			if(tagMonthOption != null && tagYearOption != null)
			{
				setSelectOption(tagMonthOption, intMonthOption);
				setSelectOption(tagYearOption, intYearOption);
				
				//removes the old custom skin dropdown list in order to display the current 
				//informaiton
				deleteTags(getDocID('divEventCalendarHeaderLeft'),'div');
				deleteTags(getDocID('divEventCalendarHeaderMiddle'),'div');
				deleteTags(getDocID('divCateShowDate'),'div');
				deleteTags(getDocID('divEventSidebarCalendarMonth'),'div');
				deleteTags(getDocID('divEventSidebarCalendarYear'),'div');
				deleteTags(getDocID('divCateSelect'),'div');
				deleteTags(getDocID('divSortBySelect'),'div');
								
				//reloads the custom skin of the dropdown to display the
				//new selected options
				$("select").styleSelect({
				   styleClass: "selectGary",
				   jScrollPane: 1
				});
			}//end of if
			
			tagBody.innerHTML = htmlJavaServerObject.responseText;
		}//end of if
		else if(htmlJavaServerObject.readyState == 2 && htmlJavaServerObject.status == 500)
		{
		}//end of else if
	}//end of function()

    htmlJavaServerObject.send("intMonth=" + intMonthOption + "&intYear=" + intYearOption + "&intDayNameLength=" + intDayNameLength + "&intFirstDay=" + intFirstDay + "&intEventCate=" + intEventCateOption + "&boolDisplayOtherMonth=" + boolDisplayOtherMonth + "&boolSidebar=" + boolSidebar + "&boolDisplayUserCal=" + boolDisplayUserCal);
	
	return true;
}//end of sendCalendar()

//sends an category posting
function sendCateogryPosting(strFileName, tagBody, tagCate, strTag, strSearchText, strFormat, strPostFormat, strPostStatus, tagSortBy, boolDisplayPaging, intDisplayNumberOfPostPerPage, intGoToPage)
{
	var htmlJavaServerObject = getXMLHttpRequest();//holds the object of the server
	
	//Abort any currently active request.
	htmlJavaServerObject.abort();
		
	// Makes a request
 	htmlJavaServerObject.open("Post", strFileName, true);
	htmlJavaServerObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	htmlJavaServerObject.onreadystatechange = function(){
    	if(htmlJavaServerObject.readyState == 4 && htmlJavaServerObject.status == 200)
		{
			tagBody.innerHTML = htmlJavaServerObject.responseText;
		}//end of if
		else if(htmlJavaServerObject.readyState == 2 && htmlJavaServerObject.status == 500)
		{
		}//end of else if
	}//end of function()

    htmlJavaServerObject.send("Category=" + encodeURL(getSelectOption(tagCate)) + "&Tag=" + encodeURL(strTag) + "&SearchText=" + encodeURL(strSearchText) + "&Format=" + encodeURL(strFormat) + "&PostFormat=" + encodeURL(strPostFormat) + "&PostStatus=" + encodeURL(strPostStatus) + "&Sort=" + encodeURL(getSelectOption(tagSortBy)) + "&DisplayNumberOfPostPerPage=" + intDisplayNumberOfPostPerPage + "&GoToPage=" + intGoToPage + "&DisplayPaging=" + boolDisplayPaging + "&Body=" + encodeURL(tagBody.id));
	
	return true;
}//end of sendCateogryPosting()

//sends an user info to update
function sendEditUserInfo(strFileName, tagBody, tagMessage, tagMessageFromServer, tagTextPassword, tagTextCPassword, tagTextEFTONo, tagTextFName, tagTextLName, tagTextPhone, tagTextEmail, tagTextSchool, intEditType, intCommiteeID, tagSchoolName, divSavebtn, divCancelBtn, tagLabelFName, tagLabelLName, tagLabelPhone, tagLabelEmail, tagLabelSchool, divEditBtn, tagFNameHolder, tagLNameHolder, tagPhoneHolder, tagEmailHolder, tagSchoolHolder, tagTxtOtherSchool, tagDivOtherSchool)
{
    var htmlJavaServerObject = getXMLHttpRequest(); //holds the object of the server
	var strSchoolID = tagTextSchool.value;//holds the school id or name from other

    //Abort any currently active request.
    htmlJavaServerObject.abort();

    //checks if there is a User Name and Password
    if (tagTextPassword.value != tagTextCPassword.value) {
    	displayMessage(tagMessage, 'Password do not match', true, true);
    	return false;}
		
	//checks if the strSchoolID is other if so then use tagTxtOtherSchool Instead
	if(isNaN(strSchoolID) == true)
		strSchoolID = tagTxtOtherSchool.value;

    // Makes a request
    htmlJavaServerObject.open("Post", strFileName, true);
    htmlJavaServerObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    htmlJavaServerObject.onreadystatechange = function () {
        if (htmlJavaServerObject.readyState == 4 && htmlJavaServerObject.status == 200) 
		{
			var arrActullyEndMassage = htmlJavaServerObject.responseText.split("</head>");//gets the acrtully end message because ASP.NET has alot of useless overhead
		
			//checks if there is a message from the server
			//as the only message should be the name of the school
			if(arrActullyEndMassage.length == 1)
			{
				var strMessageFromServer = arrActullyEndMassage[0];//holds the messaaage from the server
				
				//returns the body to normal postion
				endMessage(tagMessage, null, false);
				       	
				//checks if the message is a number 
				//as only the school would use a number
				if (intEditType > 0 && intEditType < 2)
				{
					//resets the Password
                	tagTextPassword.value = "";
                	tagTextCPassword.value = "";
					
                	//displays the message to the user and reinable the login body
                	tagMessageFromServer.innerHTML = strMessageFromServer;
				}//end of if
				else
				{
					//updates the labels from the text boxes as they are now different
					tagLabelFName.innerHTML = tagTextFName.value;
					tagLabelLName.innerHTML = tagTextLName.value;
					tagLabelPhone.innerHTML = tagTextPhone.value;
					tagLabelEmail.innerHTML = tagTextEmail.value;
					tagLabelSchool.innerHTML = strMessageFromServer;
									
					//resets the page that displays
					displayEditUser(false, tagTextFName, tagTextLName, tagTextPhone, tagTextEmail, tagSchoolName, divSavebtn, divCancelBtn, tagLabelFName, tagLabelLName, tagLabelPhone, tagLabelEmail, tagLabelSchool, divEditBtn, tagFNameHolder, tagLNameHolder, tagPhoneHolder, tagEmailHolder, tagSchoolHolder, tagDivOtherSchool);
					
					//resets the mssage from the server
                	tagMessageFromServer.innerHTML = "";
				}//end of else
            }//end of if
			else
				//tells the user that there is an error with the Server
				displayMessage(tagMessage,arrActullyEndMassage[1], true, true);
        } //end of if
        else if (htmlJavaServerObject.readyState == 2 && htmlJavaServerObject.status == 500) 
		{
			//displays an error message and returns the body to normal postion
			endMessage(tagMessage, tagBody, true);
        } //end of else if
    } //end of function()

    htmlJavaServerObject.send("Password=" + encodeURL(tagTextPassword.value) + "&CPassword=" + encodeURL(tagTextCPassword.value) + "&ETFONo=" + encodeURL(tagTextEFTONo.value) + "&FName=" + encodeURL(tagTextFName.value) + "&LName=" + encodeURL(tagTextLName.value) + "&Phone=" + encodeURL(tagTextPhone.value) + "&Email=" + encodeURL(tagTextEmail.value) + "&School=" + encodeURL(strSchoolID) + "&EditType=" + encodeURL(intEditType) + "&CommitteeID=" + encodeURL(intCommiteeID));

    return true;
}//end of sendEditUserInfo()

//sends an search for an exective to a server
function sendExecutives(strFileName, tagBody, strLetter, tagSearchOption, intDisplayNumberOfPostPerPage, tagSearchText, boolUseDatabase, intTermID, strDatabaseTableName, boolDisplayFirstName, strExeFormat, strImageID, tagCurrentSelection, tagImageMapContainer, boolDisplayPaging, boolDisplayTitle, strUserRole, intGoToPage)
{
	var htmlJavaServerObject = getXMLHttpRequest();//holds the object of the server
	var strSearchOption = "";//holds the search option
	var strSearchText = "";//holds the search text
		
	//Abort any currently active request.
	htmlJavaServerObject.abort();
	
	//checks if there is a search option on the srceen
	//and if so then gets its value
	if(tagSearchOption != null)
	    strSearchOption = getRadioCheck(tagSearchOption);
    //checks if search text area is on the screen then make it null else donot
		
	//checks if there is a search textbox on the srceen
	//and if so then gets its value
	if(tagSearchText != null && strLetter != "divRegianlMap")
		strSearchText = tagSearchText.value;
	//checks if this is an option for the map of regioanls using letter if it is divRegianlMap
	else if(strLetter == "divRegianlMap")
	{
		strSearchText = tagSearchText;
		strSearchOption = "RegianlMap";
	}//end of if
		
	// Makes a request
 	htmlJavaServerObject.open("Post", strFileName, true);
	htmlJavaServerObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	htmlJavaServerObject.onreadystatechange = function () {
	    if (htmlJavaServerObject.readyState == 4 && htmlJavaServerObject.status == 200) {

	        //checks if there is a image to change
	        if (strImageID != null || strImageID == "") {
	            //turns all images to the off mode on the map
	            classToggleLayer(getDocID(tagImageMapContainer), getDocID("img" + strImageID), 'divJustHidden', 'img');
	            //classToggleLayerImg(getDocID(tagImageMapContainer),getDocID("img" + strImageID),"","img",'/ett/wp-content/themes/ETT/images/Map/' + strImageID + '_On.png',"/ett/wp-content/themes/ETT/images/Map/{0}_Off.png");

	            //changes the image to be the one that is selected
	            changeImage("img" + strImageID, '/wp-content/themes/ETT/images/Map/' + strImageID + '_On.png');

	            //checks if there is a hidden input for the current image being selected
	            if (tagCurrentSelection != null)
	                tagCurrentSelection.value = strImageID;
	        } //end of if

	        tagBody.innerHTML = htmlJavaServerObject.responseText;
			
            //checks if search text area exist than make it null after result is displayed
	        if (tagSearchText != null && strLetter != "")
	            tagSearchText.value = "";
	    } //end of if
	    else if (htmlJavaServerObject.readyState == 2 && htmlJavaServerObject.status == 500) {
	    } //end of else if
	}  //end of function()

    htmlJavaServerObject.send("Letter=" + encodeURL(strLetter) + "&SearchOption=" + encodeURL(strSearchOption) + "&DisplayNumberOfPostPerPage=" + intDisplayNumberOfPostPerPage + "&UserRole=" + encodeURL(strUserRole) + "&SearchText=" + encodeURL(strSearchText) + "&TermID=" + intTermID + "&DisplayFirstName=" + boolDisplayFirstName + "&UseDatabase=" + boolUseDatabase + "&DatabaseTableName=" + encodeURL(strDatabaseTableName) + "&ExeFormat=" + encodeURL(strExeFormat) + "&Body=" + encodeURL(tagBody.id) + "&DisplayPaging=" + boolDisplayPaging + "&DisplayTitle=" + boolDisplayTitle + "&GoToPage=" + intGoToPage);
	
	return true;
} //end of sendExecutives()

//sends an search for an loading page to a server
function sendLoadingPage(strFileName, tagBody, strLetter, strSortBy, strSort, intDisplayNumberOfPostPerPage, intParentID, boolDisplayPaging, intGoToPage)
{
    var htmlJavaServerObject = getXMLHttpRequest(); //holds the object of the server
    var strSearchOption = ""; //holds the search option
    var strSearchText = ""; //holds the search text

    //Abort any currently active request.
    htmlJavaServerObject.abort();

    // Makes a request
    htmlJavaServerObject.open("Post", strFileName, true);
    htmlJavaServerObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    htmlJavaServerObject.onreadystatechange = function () {
        if (htmlJavaServerObject.readyState == 4 && htmlJavaServerObject.status == 200) {

            tagBody.innerHTML = htmlJavaServerObject.responseText;
        } //end of if
        else if (htmlJavaServerObject.readyState == 2 && htmlJavaServerObject.status == 500) {
        } //end of else if
    } //end of function()

    htmlJavaServerObject.send("Letter=" + encodeURL(strLetter) + "&sortby=" + encodeURL(strSortBy) + "&sort=" + encodeURL(strSort) + "&DisplayNumberOfPostPerPage=" + intDisplayNumberOfPostPerPage + "&landingpageid=" + intParentID + "&GoToPage=" + intGoToPage + "&displaypaging=" + boolDisplayPaging + "&Body=" + encodeURL(tagBody.id));

    return true;
} //end of sendLoadingPage()

function getInternetExplorerVersion()
    // Returns the version of Windows Internet Explorer or a -1
    // (indicating the use of another browser).
{
    var rv = -1; // Return value assumes failure.
    if (navigator.appName == 'Microsoft Internet Explorer') {
        var ua = navigator.userAgent;
        var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
        if (re.exec(ua) != null)
            rv = parseFloat(RegExp.$1);
    }
    return rv;
}

//sends an username and password for login page to a server
function sendLoginCredentials(strFileName, tagBody, tagMessage, tagMessageFromServer, strUserName, strPassword) {
    //getting what is current url 
    var strCurrUrllocation = document.URL;
    var hash = getUrlVariables()["url"];//holds the URL variables
    //check to see if it is IE 8 or higher if so then u

    //login page look for txtpassword else look for txtpasswordHomeLogin
    var tagPasswordLoginPage = getDocID("txtpassword");
    var tagPasswordHomeLogin = getDocID("txtpasswordHomeLogin");
    var tagPassword = "";

    if (tagPasswordLoginPage != null) {
        var msg = "You're not using Windows Internet Explorer.";
        var ver = getInternetExplorerVersion();
        if (ver > -1) {
            if (ver >= 8.0) {
                msg = "You're using Windows Internet Explorer 8.";
                tagPasswordLoginPage = getDocIDFromBottom(getDocID("divLoginBody"), "txtpassword", "input");
                tagPassword = tagPasswordLoginPage;
            }
        }
        tagPassword = tagPasswordLoginPage;
        //alert(msg + " " + tagPassword.value);
    }
    else if (tagPasswordHomeLogin != null) {
        var msg = "You're not using Windows Internet Explorer.";
        var ver = getInternetExplorerVersion();
        if (ver > -1) {
            if (ver >= 8.0) {
                msg = "You're using Windows Internet Explorer 8.";
                tagPasswordHomeLogin = getDocIDFromBottom(getDocID("divHeaderDropLoginBody"), "txtpasswordHomeLogin", "input");
                tagPassword = tagPasswordHomeLogin;
            }
        }
        tagPassword = tagPasswordHomeLogin;
        //alert(msg + " " + tagPassword.value);
    }
    //alert(msg + " " + tagPassword.value);
    
    var htmlJavaServerObject = getXMLHttpRequest(); //holds the object of the server

    //Abort any currently active request.
    htmlJavaServerObject.abort();

    //setting text box value to nothing when its IE8
    
    //if username and password text box is empty
    if (strUserName.value == "" || strPassword.value == "") {
        displayMessage(tagMessage, 'You must enter username and password', true, true);
        return false;
    }

	//sets the page for send to the server
	preSendEMail(tagMessage, "Sending...", tagBody);

    // Makes a request
    htmlJavaServerObject.open("Post", strFileName, true);
    htmlJavaServerObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    htmlJavaServerObject.onreadystatechange = function () {
        if (htmlJavaServerObject.readyState == 4 && htmlJavaServerObject.status == 200) {
            //checks if there is a message from the server 
            if (htmlJavaServerObject.responseText.length > 5) {
                //resets the Password and message
                //strPassword.value = "";
                displayMessage(tagMessage, "", true, true);

                //displays the message to the user and reinable the login body
                tagMessageFromServer.innerHTML = htmlJavaServerObject.responseText;
                tagBody.style.display = 'block';
                //hide tag body when user see continue button
                var aContinueTag = getDocID('aContinue');
                if (aContinueTag != null)
                    tagBody.style.display = 'none';
            }//end of if
            else 
			{
                //if hash is not null redirect to the page
                if (hash != null)
                    window.location = hash;
                    //go to member page
                else if (strCurrUrllocation != null)
                    //window.location = '/ett/index.php/members/';
                    window.location = strCurrUrllocation;
                else 
                    window.location = '/ett/index.php/members/';
            }

        } //end of if
        else if (htmlJavaServerObject.readyState == 2 && htmlJavaServerObject.status == 500) {
        } //end of else if
    } //end of function()

    htmlJavaServerObject.send("UserName=" + encodeURL(strUserName.value) + "&Password=" + encodeURL(tagPassword.value));
    
    return true;
} //end of sendLoginCredentials()


//sends an username and password for users who are logging in for the first time
function sendLoginCredentialsFirstTime(strFileName, tagBody, tagMessage, tagMessageFromServer, strUserName, strPassword, strOldPassword, strNewPassword, strCNewPassword, intFirstTime) {
    var htmlJavaServerObject = getXMLHttpRequest(); //holds the object of the server

    //Abort any currently active request.
    htmlJavaServerObject.abort();
    //var validPass = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{4,8}$/;
    var validPass = /(?=.*\d)(?=.*[a-z]).{6,}/;

    if (strOldPassword.value == "" || strNewPassword.value == "" || strCNewPassword.value == "") {
        displayMessage(tagMessage, 'You must enter your password', true, true);
        return false;
    }
    else if (strOldPassword.value == strNewPassword.value) {
        displayMessage(tagMessage, 'Your new password must be different from your old password', true, true);
        return false;
    }
    else if (strNewPassword.value.search(validPass)) {
        displayMessage(tagMessage, 'Please enter a valid Password 1 number, 1 letter and at least six characters', true, true);
        return false;
    }

    //prepares the body to be sent to the server
    preSendEMail(tagMessage, tagBody);

    // Makes a request
    htmlJavaServerObject.open("Post", strFileName, true);
    htmlJavaServerObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    htmlJavaServerObject.onreadystatechange = function () {
        if (htmlJavaServerObject.readyState == 4 && htmlJavaServerObject.status == 200) {
            //checks if there is a message from the server 
            if (htmlJavaServerObject.responseText.length > 5) {
                //resets the Password and message
                //strPassword.value = "";
                strOldPassword.value = "";
                strNewPassword.value = "";
                displayMessage(tagMessage, "", true, true);

                //displays the message to the user and reinable the login body
                tagMessageFromServer.innerHTML = htmlJavaServerObject.responseText;
                tagBody.style.display = 'block';
            } //end of if
            else
            //sends the user to the members area if the can login
                window.location = '/index.php/members/member-profile';
        } //end of if
        else if (htmlJavaServerObject.readyState == 2 && htmlJavaServerObject.status == 500) {
        } //end of else if
    } //end of function()

    htmlJavaServerObject.send("UserName=" + encodeURL(strUserName.value) + "&Password=" + encodeURL(strPassword.value) + "&OldPassword=" + encodeURL(strOldPassword.value) + "&NewPassword=" + encodeURL(strNewPassword.value) + "&FirstTime=" + intFirstTime);

    return true;
} //end of sendLoginCredentialsFirstTime()

//sends an the promo code to get the amount
function sendPromo(strFileName, tagMessage, tagPromo, tagPromoAmount, tagGrandTotal, strAttendeeID, strEventID) 
{
    var htmlJavaServerObject = getXMLHttpRequest(); //holds the object of the server

    //Abort any currently active request.
    htmlJavaServerObject.abort();
	
	//checks if there is a promo text field on the page if not then stop it right here
	//also checks if there is any promo code to use
	if(tagPromo == null)
		return false;
	else if(tagPromo.value == "")
		return false;

    // Makes a request
    htmlJavaServerObject.open("Post", strFileName, true);
    htmlJavaServerObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    htmlJavaServerObject.onreadystatechange = function () {
        if (htmlJavaServerObject.readyState == 4 && htmlJavaServerObject.status == 200) 
		{
			//checks if return string is a number if so then
			//adds it to the total
			if(isNaN(htmlJavaServerObject.responseText) == false)
			{
				//sets the total of the what the event will cost after the promo is active
				tagGrandTotal.innerHTML = htmlJavaServerObject.responseText
				
				//resets the message
				displayMessage(tagMessage, "", true, true);
			}//end of if
			else
				displayMessage(tagMessage, htmlJavaServerObject.responseText, true, true);
        } //end of if
        else if (htmlJavaServerObject.readyState == 2 && htmlJavaServerObject.status == 500) {
        } //end of else if
    } //end of function()

    htmlJavaServerObject.send("Promo=" + encodeURL(tagPromo.value) + "&EventID=" + strEventID + "&AttendeeID=" + strAttendeeID + "&GrandTotal=" + encodeURL(tagGrandTotal.innerHTML));

    return true;
} //end of sendPromo()

//sends an email to a Friend of the user who ment
function sendShareEMail(strFileName,tagMessage,tagEMailBody,tagFName,tagLName,tagPhone,tagSendFrom,tagBody,tagSendTo)
{		
	var strFilter = /^.+@.+\..{2,3}$/;//holds the filtter for the Email
	var htmlJavaServerObject = getXMLHttpRequest();//holds the object of the server
	//var tagSendFrom = tagSendFrom.value.split(';');//holds all of the e-mail address that are going to be sent out

	//checks if there is a Name
	if (tagFName.value == "")
  		{displayMessage(tagMessage,'You must have a first name',true,true);
			return false;}
			
	//checks if there is a Name
	if (tagLName.value == "")
  		{displayMessage(tagMessage,'You must have a last name',true,true);
			return false;}
		
	//checks if they have a E-Mail
	if (tagSendFrom.value == "")
  		{displayMessage(tagMessage,'You must have e-mail address, that you are sending from',true,true);
			return false;}
		
	//goes around each e-mail the user entered
	for(var intIndex = tagSendFrom.length - 1; intIndex > -1;intIndex--)
	{
		//checks if there the E-Mail Format is current
		if (strFilter.test(tagSendFrom[intIndex]) == false)
  			{displayMessage(tagMessage,"Please input Valid e-mail address for " + tagSendFrom[intIndex],true,true);
				return false;}
		else if (tagSendFrom[intIndex].match(/[\(\)\<\>\,\;\:\\\/\"\[\]]/))
  			{displayMessage(tagMessage,"The e-mail address, " + tagSendFrom[intIndex]  + ", that you are sending from, contains illegal Characters.",true,true);
				return false;}
	}//end of for loop
	
	//checks if they have a phone
	if (tagPhone.value == "")
  		{displayMessage(tagMessage,'You must have a phone number',true,true);
			return false;}
	
	//checks if they have a eMail Body
	if (tagBody.value == "")
  		{displayMessage(tagMessage,'You must have a message',true,true);
			return false;}
				
	//Abort any currently active request.
	htmlJavaServerObject.abort();
	
	//prepers the form for sending a e-mail
	preSendEMail(tagMessage,tagEMailBody);
	
	// Makes a request
 	htmlJavaServerObject.open("Post", strFileName, true);
	htmlJavaServerObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	htmlJavaServerObject.onreadystatechange = function(){
    	if(htmlJavaServerObject.readyState == 4 && htmlJavaServerObject.status == 200)
		{
			//closes the pop up and removes the textbox so the user cannot use them again until they refresh the page
			//endMessage(htmlJavaServerObject.responseText,"",tagMessage);
			displayMessage(tagMessage,htmlJavaServerObject.responseText,true,true);
							
			//resets the fields and disables the field area
			tagFName.value = "";
			tagLName.value = "";
			tagPhone.value = "";
			tagSendFrom.value = "";
			tagBody.value = "";
			
			//displays the send part
			tagEMailBody.style.display = "";
		}//end of if
		else if(htmlJavaServerObject.readyState == 2 && htmlJavaServerObject.status == 500)
		{
			//closes the pop up and removes the textbox so the user cannot use them again until they refresh the page
			endMessage('<head></head>Unable to Connect to the Server.</head>',strPopUpID,tagMessage);
		}//end of else if
	}//end of function()

	htmlJavaServerObject.send("txtFName=" + encodeURL(tagFName.value) + "&txtLName=" + encodeURL(tagLName.value) + "&txtPhone=" + encodeURL(tagPhone.value) + "&txtFromEMail=" + encodeURL(tagSendFrom.value) + "&txtToEMail=" + tagSendTo + "&txtMessage=" + encodeURL(nl2br(tagBody.value)));
	
	return true;
}//end of sendShareEMail()

//set an event for tagEvent
//THIS CAN BE FOR ANY EVENT PLEASE CUSTOM THE PREMATIES IN ORDER TO REUSE THIS FUNXTION
function setEvent(intImageIndex,strTitle,strImageURL)
{
	var tagLBTitle = getDocID("imgLightBoxTitle");//holds the current image being display
	var tagSetTotal = getDocID('lblSetTotal');//holds the total of the set
	var tagSetID = getDocID('lblSetID');//holds the id of the set
	var strSetLastNumber = "0";//holds the Last Set Number
	var strSetID = "0";//holds the ID of the Set
	
	//checks if the Set Total is on the page and if so then removes one as the ids start at zero
	if(tagSetTotal !== null && tagSetID !== null)
	{
		//converts int to string for the total
		strSetLastNumber = (parseInt(tagSetTotal.value) - 1) + '';
		
		//gets the id of the set
		strSetID = tagSetID.innerHTML;
	}//end of if

	var tagFirstImage = getDocID("0");//holds the first of the set imagesd
	//var tagLastImage = getDocID(strSetLastNumber);//holds the last of the set images
	
	//displays the arrows back again
	getDocID('aLeftArrow').style.display = "";
	getDocID('aRightArrow').style.display = "";
	
	//changes the lightbox image to the next/prev image	
	changeImageLightBox('imgLightBoxTitle', 'lblLatestImageLightBoxTitle2', strImageURL, intImageIndex, strTitle, '', '');
	
	//removes the event from right arrow
	$("#aRightArrow").off("click");
	
	//removes the event from left arrow
	$("#aLeftArrow").off("click");

	//gets the image details
	getPhoto(intImageIndex);
	
	//checks if this is the first of the images and if so make it disappear
	if(tagFirstImage.alt === tagLBTitle.alt)
		getDocID('aLeftArrow').style.display = "none";
}//end of setEvent()

//sets the select option from tagSelect
function setSelectOption(tagSelect, strValue)
{
	var strSelectOption = "";//holds the select option the user has choosen
	
	//goes around finding the current seleted value from tagSelection
	for (var intIndex = 0;intIndex < tagSelect.options.length; intIndex++)
	{
		//checks if this is the value that the use wants to selected
		if (tagSelect.options[intIndex].value == strValue)
			strSelectOption = tagSelect.options[intIndex].selected = true;
	}//end of for loop
		
	return strSelectOption;
}//end of setSelectOption()

//sets the the executives information in the Lightbox
function setsExecutivesInforLB(tagContactTextImage, tagContactTextName, tagContactEmail, tagContactPhone, tagContactSectionNames, strContactName, strContactID, strContactEmail, strContactPhone, strContactTextImage, arrContactSectionNames) 
{
	//checks if there is a tagContactTextImage, tagContactTextName, tagContactEmail, tagContactPhone, tagContactSectionNames if so then add values to them
	if(tagContactTextImage != null && tagContactTextName != null && tagContactEmail != null && tagContactPhone != null && tagContactSectionNames != null)
	{
		//sets the basic properties to the contact us lightbox
		
		//sets the contact image
		tagContactTextImage.href = strContactID;
		tagContactTextImage.innerHTML = "<img src='" + strContactTextImage + "' alt='" + strContactName + "' />";
		
		//sets the contact name
		tagContactTextName.href = strContactID;
		tagContactTextName.innerHTML = strContactName;
		
		//sets the contact email
		tagContactEmail.href = "mailto:" + strContactEmail;
		tagContactEmail.innerHTML = strContactEmail;
		
		//sets the contact phone number
		tagContactPhone.innerHTML = strContactPhone;

		//checks if there is any items to display this seciton
		if(arrContactSectionNames != null && arrContactSectionNames.length > 0)
		{
			//sets the section to display the Schools this uses in in
			tagContactSectionNames.innerHTML = "<div class='divETTExecutivesLabels customLeft'>" + 
					"<label>Family of Schools:</label>" + 
				"</div>" + 
				"<div class='ContactUsForumFamilyofSchools customLeft'>";
			
			//goes around adding the contact's schools they are part of
			for (var intIndex = 0;intIndex < arrContactSectionNames.length;intIndex++)
			{
				tagContactSectionNames.innerHTML += arrContactSectionNames[intIndex] + " ,";
			}//end of for loop
			
			//ends the section to display the Schools this uses in in	
			tagContactSectionNames.innerHTML = tagContactSectionNames.innerHTML.substring(0,tagContactSectionNames.innerHTML.length - 1) + 
				"</div>" + 
				"<div class='customFooter'></div>";
		}//end of if
	}//end of if
}//end of setsExecutivesInforLB()

//shoes and hides a <div> using display:block/none from the CSS
function toggleLayer(tagLayer,tagGrayOut,tagMedia)
{
	var tagStyle = '';//holds the style of tagLayer

	//gets the tagLayer and tagGrayOut Properties
	tagStyle = getDocID(tagLayer);
	tagGrayOut = getDocID(tagGrayOut);
	tagMedia = getDocID(tagMedia);
		
	if (tagStyle != null)
	{tagStyle.style.display = tagStyle.style.display? "":"block";}
	
	if (tagGrayOut != null)
	{
		tagGrayOut.style.display = tagGrayOut.style.display? "":"block";

		//for IE
		if (navigator.userAgent.indexOf('MSIE') != -1)
		{
			tagGrayOut.attachEvent('onclick',function () {
				toggleLayer(tagStyle.id,tagGrayOut.id)
								
				//checks if there is any Media to stop also pleace remove when REUSING THIS FUNCTION 
				if (tagMedia != null && document.getElementById("embed_url") != null)
					tagMedia.removeChild(document.getElementById("embed_url"));
			});
		}//end of if
		//for the other browsers
		else
		{
			tagGrayOut.addEventListener('click',function () {
				toggleLayer(tagStyle.id,tagGrayOut.id);
				
			if (tagMedia != null && document.getElementById("embed_url") != null)
				tagMedia.removeChild(document.getElementById("embed_url"));

			},false);
		}//end of else
	}//end of if
}//end of toggleLayer()

//Prototypes

//sets the string format to be like printf in PHP
String.prototype.format = function() {
  var args = arguments;
  return this.replace(/{(\d+)}/g, function(match, number) { 
    return typeof args[number] != 'undefined'
      ? args[number]
      : match
    ;
  });
};