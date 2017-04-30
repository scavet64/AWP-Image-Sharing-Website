/*************Helpers****************/

function _(x){
	return document.getElementById(x);
}

function ajaxObj( meth, url ) {
	var x = new XMLHttpRequest();
	x.open( meth, url, true );
	x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	return x;
}

function ajaxReturn(x){
	if(x.readyState == 4 && x.status == 200){
	    return true;	
	}
}

function toggleElement(x){
	var x = _(x);
	if(x.style.display == 'block'){
		x.style.display = 'none';
	}else{
		x.style.display = 'block';
	}
}

function emptyElement(x){
	_(x).innerHTML = "";
}

function restrict(elem){
	var tf = _(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' "]/gi;
	} else if(elem == "username"){
		rx = /[^a-z0-9]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}

/*************Signup****************/

function checkusername(){
	var u = _("username").value;
	if(u != ""){
		_("unamestatus").innerHTML = 'checking ...'; //Can make this an animated gif or something real fancy
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("unamestatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("usernamecheck="+u);
	}
}
function signup(){
	var u = _("username").value;
	var e = _("email").value;
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	var status = _("status");
	if(u == "" || e == "" || p1 == "" || p2 == ""){
		status.innerHTML = "Fill out all of the form data";
	} else if(p1 != p2){
		status.innerHTML = "Your password fields do not match";
	} else if( _("terms").style.display == "none"){
		status.innerHTML = "Please view the terms of use";
	} else {
		_("signupbtn").style.display = "none";
		status.innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "signup_success"){
					status.innerHTML = ajax.responseText;
					_("signupbtn").style.display = "block";
				} else {
					window.scrollTo(0,0);
					_("signupform").innerHTML = "logging you in...";
					var success = login(e,p1);
					if(!success){
						_("signupform").innerHTML = "Trouble loggin you in. :^(";
					};
					
					/***If we want to go back to the email verification method***/
					//_("signupform").innerHTML = "OK "+u+", check your email inbox 
					//and junk mail box at <u>"+e+"</u> in a moment to complete the sign 
					//up process by activating your account. You will not be able to do 
					//anything on the site until you successfully activate your account.";
				}
	        }
        }
        ajax.send("u="+u+"&e="+e+"&p="+p1);
	}
}
function openTerms(){
	_("terms").style.display = "block";
	emptyElement("status");
}

/*************Login****************/

function loginForm(){
	var e = _("email").value;
	var p = _("password").value;
	
	if(e == "" || p == ""){
		_("status").innerHTML = "Fill out all of the form data";
	} else {
	    _("loginbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var success = login(e,p);
		if(!success){
			_("status").innerHTML = "Login unsuccessful, please try again.";
			_("loginbtn").style.display = "block";
		}
	}
}

function login(e,p){
	if(e == "" || p == ""){
		return false;
	} else {
		var ajax = ajaxObj("POST", "login.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText.trim() === "login_failed"){
					return false;
				} else {
					window.location = "user.php?u="+ajax.responseText;
				}
	        }
        }
        
        ajax.send("e="+e+"&p="+p);
	}
}

/*************Comments****************/

function postComment(id){
	
	//get the comment from the inputbox
	var idToFind = "inputOnPhoto"+id;
	var comment = _(idToFind).value;
	
	$.post("comment_controller.php",
    {
        comment: comment,
        photo_id: id
    },
    function(data, status){
    	if(status === "success"){
    		if(!data.trim().includes("error")){
    			_('commentsForPhoto'+id).innerHTML += data;
    			_(idToFind).value = '';
    		} else {
    			//display an error somewhere
    			var str = data.trim().replace("error:", '');
    			alert(str);
    		}

    	} else {
    		//something went wrong
    	}
    });
}

function deleteComment(id){
	
	//for now use this ugly box
	if(confirm("Press OK do confirm your deletion. This action cannot be reversed.")){
		//get the comment from the inputbox
		var idToFind = "comment"+id;
		
		$.post("comment_controller.php",
	    {
	        comment_id: id,
	    },
	    function(data, status){
	    	if(status === "success"){
	    		if(data.trim() !== "error"){
	    			_(idToFind).remove();
	    		}
	    	} else {
	    		//something went wrong
	    		alert("Problem deleting message");
	    	}
	    });
	}
}

/*************Users****************/

function deleteUser(username){
	
	//for now use this ugly box
	if(confirm("Press OK do confirm your deletion. This action cannot be reversed.")){
		
		$.post("delete_user.php",
	    {
	        username: username,
	    },
	    function(data, status){
	    	if(status === "success"){
	    		window.location = "home.php";
	    		//_(idToFind).remove();
	    	} else {
	    		//something went wrong. Display something at some point
	    	}
	    });
	}
}

function blockUser(user_id){
	
	//for now use this ugly box
	if(confirm("Press OK do confirm your deletion. This action cannot be reversed.")){
		
		$.post("block_user.php",
	    {
	        user_id: user_id,
	    },
	    function(data, status){
	    	if(status === "success"){
	    		window.location = "home.php";
	    		//_(idToFind).remove();
	    	} else {
	    		//something went wrong. Display something at some point
	    	}
	    });
	}
}

/*************HashTags****************/

function SearchHashtags(){
	var search = _("searchBar").value.replace(/#/g,'%23');
	var uri = "hashtag.php?q="+search;
	var res = encodeURI(uri);
	window.location = res;
}

function loadImages(offset){
	console.log("Loading images from offset: "+offset)
	var ajax = ajaxObj("GET", "photo_display_gen.php?offset="+offset);
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			console.log(ajax.responseText);
			if(ajax.responseText.trim() === ""){
				//clear the scroll function to stop the flood
				$(window).unbind('scroll');
			}
            _('photoDisplayContainer').innerHTML += ajax.responseText
		}
	}
	ajax.send("");
}