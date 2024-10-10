 $(document).ready(function () {
	 
 	$(".ts-sidebar-menu li a").each(function () {
 		if ($(this).next().length > 0) {
 			$(this).addClass("parent");
 		};
 	})
 	var menux = $('.ts-sidebar-menu li a.parent');
 	$('<div class="more"><i class="fa fa-angle-down"></i></div>').insertBefore(menux);
 	$('.more').click(function () {
 		$(this).parent('li').toggleClass('open');
 	});
	$('.parent').click(function (e) {
		e.preventDefault();
 		$(this).parent('li').toggleClass('open');
 	});
 	$('.menu-btn').click(function () {
 		$('nav.ts-sidebar').toggleClass('menu-open');
 	});
	 
	 
	 $('#zctb').DataTable();
	 
	 
	 $("#input-43").fileinput({
		showPreview: false,
		allowedFileExtensions: ["zip", "rar", "gz", "tgz"],
		elErrorContainer: "#errorBlock43"
			// you can configure `msgErrorClass` and `msgInvalidFileExtension` as well
	});

 });

//textarea validate
 document.querySelector('.limitedText').addEventListener('input', function () {
	const textArea = this;
	const errorDiv = textArea.closest('.form-group').querySelector('.error');
	const charCountElement = textArea.closest('.form-group').querySelector('.charCount');
	const regex = /^[A-Za-z.,\s]*$/; // Only letters, periods, and spaces
	const words = textArea.value.trim().split(/\s+/);
	const sub = document.querySelector('.sub');
	sub.disabled = false;

	// Check for invalid characters
	if (!regex.test(textArea.value)) {
		errorDiv.textContent = 'Only letters, periods, and spaces are allowed.';
		sub.disabled = true;
		return;
	}

	// Check for word limit
	if (words.length > 50) {
		errorDiv.textContent = 'You can only enter up to 50 words.';
		sub.disabled = true;
		return;
	}

	// Check character count and update remaining characters
	const maxLength = textArea.getAttribute('maxlength');
	const currentLength = textArea.value.length;
	charCountElement.textContent =` ${maxLength - currentLength} characters remaining`;

	errorDiv.textContent = ''; // Clear error message if everything is fine
	});

// password visible
function togglePasswordVisibility(inputId, button) {
	const inputField = document.getElementById(inputId);
	const icon = button.querySelector('i');
	if (inputField.type === 'password') {
		inputField.type = 'text';
		icon.classList.remove('fa-eye');
		icon.classList.add('fa-eye-slash');
	} else {
		inputField.type = 'password';
		icon.classList.remove('fa-eye-slash');
		icon.classList.add('fa-eye');
	}
}

//gate date validate
function validateDates() {
	const outDate = document.getElementById('out_date').value;
	const outTime = document.getElementById('out_time').value;
	const inDate = document.getElementById('in_date').value;
	const inTime = document.getElementById('in_time').value;

	if (outDate && inDate) {
		const outDateTime = new Date(`${outDate}T${outTime}`);
		const inDateTime = new Date(`${inDate}T${inTime}`);

		if (inDateTime < outDateTime) {
			alert("In Date and Time cannot be earlier than Out Date and Time.");
			document.getElementById('in_date').value = '';
			document.getElementById('in_time').value = '';
			document.getElementById('number_of_days').value = '';
			return;
		}
	}

	calculateDays();
}

function validatevisitDates() {
    const visitDate = document.getElementById('visit_date').value;
    const visitTime = document.getElementById('visit_time').value;
    const leaveDate = document.getElementById('leave_date').value;
    const leaveTime = document.getElementById('leave_time').value;

    if (visitDate && leaveDate) {
        if (visitDate !== leaveDate) {
            alert("Guest cannot stay more than one day.");
            document.getElementById('leave_date').value = '';
            document.getElementById('leave_time').value = '';
            return;
        }

        const visitDateTime = new Date(`${visitDate}T${visitTime}`);
        const leaveDateTime = new Date(`${leaveDate}T${leaveTime}`);

        if (leaveDateTime < visitDateTime) {
            alert("Leave Date and Time cannot be earlier than Visit Date and Time.");
            document.getElementById('leave_date').value = '';
            document.getElementById('leave_time').value = '';
            return;
		}
	}
}

function calculateDays() {
	const outDate = document.getElementById('out_date').value;
	const outTime = document.getElementById('out_time').value;
	const inDate = document.getElementById('in_date').value;
	const inTime = document.getElementById('in_time').value;

	if (outDate && outTime && inDate && inTime) {
		const outDateTime = new Date(`${outDate}T${outTime}`);
		const inDateTime = new Date(`${inDate}T${inTime}`);

		const differenceInTime = inDateTime - outDateTime;
		const differenceInDays = differenceInTime / (1000 * 3600 * 24);

		document.getElementById('number_of_days').value = differenceInDays.toFixed(2);
	}
}

//textarea validate
function validateInput(event) {
	const textarea = event.target;
	const maxLength = textarea.getAttribute('maxlength');
	const currentLength = textarea.value.length;

	// Update the remaining characters count
	const charCountElement = document.getElementById('charCount');
	charCountElement.textContent =` ${maxLength - currentLength} characters remaining`;

	// Check if the current length exceeds the max length
	const errorElement = document.getElementById('error');
	if (currentLength > maxLength) {
		errorElement.textContent = `You have exceeded the maximum length of ${maxLength} characters.`;
	} else {
		errorElement.textContent = '';
	}
}
