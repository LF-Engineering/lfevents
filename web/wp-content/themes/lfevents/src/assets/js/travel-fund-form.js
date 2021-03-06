/**
 * JS code to run only on pages with a Travel Fund form.
 *
 * @package WordPress
 */

window.onTFSubmit = onTFSubmit; // need to do this to export the onTFSubmit function from the module scope created by WebPack.
window.addnewForm = addnewForm;
window.toggleOtherInput = toggleOtherInput;
window.removeThis = removeThis;
window.fileSizeValidation = fileSizeValidation;


let travelFundForm = document.getElementById( "travelFundForm" );
let formSubmission = 0;
var message = document.getElementById( "message" );
function onTFSubmit(token) {
	travelFundForm.style.display = "none";
	$( "#message" ).html( "Sending your travel fund request..." ).addClass( "callout warning" );
	message.scrollIntoView( { behavior: "smooth", block: 'center' } );

	let fd = new FormData( travelFundForm );

	var checkboxes = document.getElementsByName( 'group' );
	var checkedValues = "";
	for (var i = 0, n = checkboxes.length; i < n; i++) {
		if (checkboxes[i].checked) {
			checkedValues += checkboxes[i].value + ",";
		}
	}
	fd.set( 'group', checkedValues );

	var i = 0;
	var filesToUpload = [];

	while ( fd.get( 'expenses.' + i + '.Name' ) != null ) {
		filesToUpload.push(
			{
				id: fd.get( 'expenses.' + i + '.Name' ),
				file: fd.get( 'expenses.' + i + '.fileToUpload' )
			}
		);
		fd.delete( 'expenses.' + i + '.fileToUpload' );
		i++;
	}

	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			let response = JSON.parse( this.responseText );
			if ( response.status === 1 ) {
				uploadFiles( response.data.expenses, filesToUpload );
			}
		}
		if ( this.readyState == 4 && this.status == 500 ) {
			let response = JSON.parse( this.responseText );
			if ( response.status === 0 ) {
				let msg = response.message;
				if ( msg.includes( "DUPLICATES_DETECTED" ) ) {
					$( "#message" ).html( "ERROR: You have already submitted a travel funding request for this event. Please contact travelfund@linuxfoundation.org for further assistance." ).removeClass( "warning" ).addClass( "alert" );
					message.scrollIntoView( { behavior: "smooth", block: 'center' } );
				}
			}
		}
	}
	xhttp.onerror = function () {
		$( "#message" ).html( "There was an error processing your submission.  Please try again or contact us directly at events@linuxfoundation.org." ).removeClass( "warning" ).addClass( "alert" );
		message.scrollIntoView( { behavior: "smooth", block: 'center' } );
	}
	xhttp.open( 'POST', travelFundForm.getAttribute( "action" ), true );
	xhttp.send( fd );

}

$( document ).ready(
	function() {
		var f = $( "#travelFundForm" )
		f.on(
			"click",
			"#submitbtn",
			function(e) {
				if (f[0].checkValidity()) {
					e.preventDefault();
					if ( fileSizeValidation() == false ) {
						return false;
					}
					grecaptcha.execute();
				}
			}
		);
	}
);


// Code to Add the Multiple Forms.
var count = 1;
function addnewForm() {
	var items = document.getElementById( "lineItem0" );
	var clonedItems = items.cloneNode( true );
	var inputs = clonedItems.getElementsByClassName( "cloneThis" );
	clonedItems.id = "lineItem" + count;
	var numElements = inputs.length;
	for (var i = 0; i < numElements; i++) {
		if (inputs[i].type !== 'button') {
			var labelArr = inputs[i].name.split( "." );
			labelArr[1] = count;
			var label = labelArr.join( "." );
			inputs[i].name = label;
			inputs[i]['data-line-item'] = count;
			if ( ! inputs[i].name.includes( 'Type' )) {
				inputs[i].value = "";
			}
		} else {
			inputs[i].setAttribute( 'data-line-item', count );
			inputs[i].style.display = "block";
		}

	}
	count++;
	document.getElementById( "lineItemFormList" ).appendChild( clonedItems );
}

function removeThis(elem) {
	let lineItem = elem.dataset.lineItem;
	let lineItemFormList = document.getElementById( 'lineItemFormList' );
	let childLength = lineItemFormList.children.length;

	if (childLength > 1 && lineItem !== "0") {
		let lineItemForm = document.getElementById( "lineItem" + lineItem );
		lineItemForm.remove();
	} else {
		alert( "There must be at least one line item." );
	}
}

$( "#receivedFunds" ).change(
	function() {
		if ( this.value == "Partial" ) {
			  $( "#orgPayingDiv" ).show();
			  $( "#orgPaying" ).prop( "required", true );
		} else {
			$( "#orgPayingDiv" ).hide();
			$( "#orgPaying" ).prop( "required", false );
		}
	}
);

function toggleOtherInput(othersCheckbox){
	var x = document.getElementById( "otherDescription" );
	var xdiv = document.getElementById( "otherDescriptionDiv" );
	if (othersCheckbox.checked) {
		x.setAttribute( "required","" );
		xdiv.style.display = "block";
	} else {
		x.removeAttribute( "required" );
		xdiv.style.display = "none";
	}
}

$( "#event" ).change(
	function() {
		if ( this.value == "a0A2M00000VHQAMUA5" ) {
			  $( ".other-event-div" ).show();
			  $( ".other-event-input" ).prop( "required", true );
		} else {
			$( ".other-event-div" ).hide();
			$( ".other-event-input" ).prop( "required", false );
		}
	}
);

function fileSizeValidation() {
	var inputfiles = document.getElementsByClassName( 'fileInput' );
	// Check if any file is selected.
	if ( inputfiles.length > 0 ) {
		var numfiles = inputfiles.length;
		for ( var i = 0; i <= numfiles - 1; i++ ) {
			var fi = inputfiles[i];
			var filength = fi.files.length;
			if ( filength > 0 ) {
				for ( var j = 0; j <= filength - 1; j++ ) {
					var fsize = fi.files.item( j ).size;
					var file = Math.round( fsize / 1024 / 1024 );
					// The size of the file.
					if ( file >= 5 ) {
						alert( "Please select a file less than 5MB." );
						inputfiles[i].value = '';
						return false;
					}
				}
			}
		}
	}
}

function uploadFiles( lineItems, filesToUpload ) {
	var success = 0;
	var fail = 0;
	var totalFiles = lineItems.length;
	var toatlProcessed = 0

	// phpcs:disable
	// impossible to get this to pass the phpcs whitespace tests.
	filesToUpload.forEach(
		file => {
			var lineItemId = lineItems.find(
				lineItem => {
					return lineItem.name == file.id;
				}
			);
			let fd = new FormData();
			fd.append( "files", file.file );
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if ( this.readyState === 4 ) {
					let response = JSON.parse( this.responseText );
					if ( this.status === 200 && response.status === 1 ) {
						++success;
					}
					if ( this.status == 500 ) {
						fail = fail + 1;
					}
					toatlProcessed++;
					if ( totalFiles == toatlProcessed ) {
						updateMessage( success, fail );
					}
				}
			}
			xhttp.onerror = function() {
				fail += 1;
			}
			xhttp.open( 'POST', 'https://eol357sn43.execute-api.us-east-2.amazonaws.com/prod/api/v1/sf/travelfundlineitem/' + lineItemId.Id + '/upload', true );
			xhttp.send( fd );
		}
	);
	// phpcs:enable

}

function updateMessage(success, fail) {
	if (fail == 0) {
		$( "#message" ).html( "Thank you for your submission." ).removeClass( "warning" ).addClass( "success" );
		message.scrollIntoView( { behavior: "smooth", block: 'center' } );
	} else {
		alert( 'There were some errors while uploading file(s)' );
	}
}
