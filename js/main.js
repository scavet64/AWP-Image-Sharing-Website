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
    		_(idToFind).value = '';
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
	    		_(idToFind).remove();
	    	} else {
	    		//something went wrong
	    	}
	    });
	}
}

// $('button[name="remove_levels"]').on('click', function(e) {
//   var $form = $(this).closest('form');
//   e.preventDefault();
//   $('#confirm').modal({
//       backdrop: 'static',
//       keyboard: false
//     })
//     .one('click', '#delete', function(e) {
//       $form.trigger('submit');
//     });
// });