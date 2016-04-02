$(window).load(function(){

});

// validates registration form
function validateForm() {
	
	// event.preventDefault();
	console.log('asda');
	var form = document.forms["register"],
		fName = form["fname"].value,
		lName = form["lname"].value,
		email = form["email"].value,
		pwd = form["password"].value,
		rpwd = form["rpassword"].value,
		regexLetters = /^[a-zA-Z0-9- ]*$/,
		flag = true,
		message = '\n';
		
    if (fName == null || fName == "" || fName == "First Name") { 
		document.getElementById('fName').style.borderColor = "red"; flag = false;
	} else { 
		document.getElementById('fName').style.borderColor = "green"; 
	}
	
    if (lName == null || lName == "" || lName == "Last Name" ) { 
		document.getElementById('lName').style.borderColor = "red"; flag = false; 
	} else { 
		document.getElementById('lName').style.borderColor = "green"; 
	}
	
	if (email == null || email == "" || email == "E-Mail") {
		document.getElementById('email').style.borderColor = "red"; flag = false; 
	} else { 
		document.getElementById('email').style.borderColor = "green"; 
	}
	
    if (pwd == null || pwd == "" || pwd == 'Password') { 
		document.getElementById('password').style.borderColor = "red"; flag = false; 
	} else {
		document.getElementById('password').style.borderColor = "green"; 
	}
	
    if (rpwd == null || rpwd == "" || rpwd == "Password" ) {
		document.getElementById('rpassword').style.borderColor = "red"; flag = false;
	} else if(pwd != rpwd){
		message += 'Passwords do not match.\n';
		document.getElementById('rpassword').style.borderColor = "red"; flag = false;		
	} else {
		document.getElementById('rpassword').style.borderColor = "green"; 
	}
	console.log(flag);
	document.getElementById('errMessage').innerHTML = message;
	return flag;
}