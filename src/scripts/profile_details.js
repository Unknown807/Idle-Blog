
function submitForm() {
	$("#detailsForm").submit()
}

function chooseImage() {
	$("#fileUpload").trigger("click");
}

$("#fileUpload").on("change", function() {
	let fd = new FormData();
	let files = $("#fileUpload")[0].files;
	
	if (files.length > 0) {
		fd.append("userImg", files[0]);
		
		$.ajax({
			url: "upload_temp_pfp.php",
			type: "post",
			data: fd,
			contentType: false,
			processData: false,
			success: function(response) {
				if (response) {
					$("#userImg").attr("src", response);
					$("#userImg").show();
					$("#imgHelp").text("");
				} else {
					$("#imgHelp").text("Issue with file upload");
				}
			}
		});
		
	} else {
		$("#imgHelp").text("Issue with file upload");
	}
});