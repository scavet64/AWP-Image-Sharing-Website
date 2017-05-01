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
					var success = login(u,p1);
					if(success == undefined){
						_("signupform").innerHTML = "Welcome! :^)";
					} else{
						_("signupform").innerHTML = "Trouble loggin you in. :^(";
					}
					
					/***If we want to go back to the email verification method***/
					//_("signupform").innerHTML = "OK "+u+", check your email inbox 
					//and junk mail box at <u>"+e+"</u> in a moment to complete the sign 
					//up process by activating your account. You will not be able to do 
					//anything on the site until you successfully activate your account.";
				}
	        }
        };
        ajax.send("u="+u+"&e="+e+"&p="+p1);
	}
}
function openTerms(){
	_("terms").style.display = "block";
	emptyElement("status");
}

/*************Login****************/

function loginForm(){
	var u = _("username").value;
	var p = _("password").value;
	
	if(u == "" || p == ""){
		_("status").innerHTML = "Fill out all of the form data";
	} else {
	    _("loginbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var success = login(u,p);
		if(success === false){
			_("status").innerHTML = "Login unsuccessful, please try again.";
			_("loginbtn").style.display = "block";
		}
	}
}

function login(u,p){
	if(u == "" || p == ""){
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
        
        ajax.send("u="+u+"&p="+p);
	}
}

function forgotpass(){
	var u = _("username").value;
	if(u == ""){
		_("status").innerHTML = "Type in your username";
	} else {
		_("forgotpassbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "forgot_pass.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
				var response = ajax.responseText;
				if(response == "success"){
					_("forgotpassform").innerHTML = '<h3>Step 2. Check your email inbox in a few minutes</h3><p>You can close this window or tab if you like.</p>';
				} else if (response == "no_exist"){
					_("status").innerHTML = "Sorry that username is not in our system";
				} else if(response == "email_send_failed"){
					_("status").innerHTML = "Mail function failed to execute";
				} else {
					_("status").innerHTML = "An unknown error occurred";
				}
	        }
        }
        ajax.send("u="+u);
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
	bootbox.confirm("Press OK to block this user", function(result){ 
		if(result){
			$.post("block_user.php",
		    {
		        user_id: user_id,
		    },
		    function(data, status){
		    	if(status === "success" && data.trim() === "Success"){
		    		//reload the page for now
		    		location.reload();
		    	} else {
		    		//something went wrong. Display something at some point
		    	}
		    });
		} else {
			//do nothing
		}
	})
}

function unblockUser(blockee, blocker){
	
	//for now use this ugly box
	bootbox.confirm("Press OK to unblock this user", function(result){ 
		if(result){
			$.post("block_user.php",
		    {
		        blockee: blockee,
		        blocker: blocker,
		    },
		    function(data, status){
		    	if(status === "success" && data.trim() === "Success"){
		    		//reload the page for now
		    		location.reload();
		    	} else {
		    		//something went wrong. Display something at some point
		    	}
		    });
		} else {
			//do nothing
		}
	})
}

function changePassword(username){
	bootbox.confirm("<form id='infos' method='post' action='change_pass.php'>\
		<label class='uploadHeading' style='margin:10px;' >Enter your new password twice</label>\
	    <input id='changepass1' type='password' name='password1' class='form-control' /><br/>\
	    <input id='changepass2' type='password' name='password2' class='form-control' />\
	    </form>", function(result) {
	        if(result){
	        	
	        	if(_('changepass1').value === _('changepass2').value){
	        		
		        	$.post("change_pass.php",
				    {
				        user_id: username,
				        password1: _('changepass1').value,
				        password2: _('changepass2').value,
				    },
				    function(data, status){
				    	if(status === "success" && data.trim() === "success"){
				    		toggleElement('changedSuccessfully');
				    	} else {
				    		//something went wrong.
				    		toggleElement('changedFailed');
				    		_('changedFailed').innerHTML += data.trim();
				    	}
				    });
	        	} else {
	        		toggleElement('changedFailed');
	        		_('changedFailed').innerHTML += "Passwords did not match!";
	        	}
	        }
	});
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

/*************Uploading****************/

$(function() {

  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });

  // We can watch for our custom `fileselect` event like this
  $(document).ready( function() {
      $(':file').on('fileselect', function(event, numFiles, label) {

          var input = $(this).parents('.input-group').find(':text'),
              log = numFiles > 1 ? numFiles + ' files selected' : label;

          if( input.length ) {
              input.val(log);
          } else {
              if( log ) alert(log);
          }

      });
  });
  
});