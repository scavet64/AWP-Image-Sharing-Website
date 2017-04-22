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
    		_('commentsForPhoto'+id).innerHTML += data;
    	} else {
    		//something went wrong
    	}
    });
}