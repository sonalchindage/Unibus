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

 
    function exportExcel(){
        const hideColumn = document.querySelector('.action-column');
        // console.log(hideColumn);
        hideColumn.style.display = 'none';
        var table2excel = new Table2Excel();
        table2excel.export(document.querySelectorAll("table.table"));
        hideColumn.style.display = 'table-cell'; 
    }

    $(function() { 
                  
        $(".exportBtnClass").click(function(e){ 
            var table = $(this).prev('.table2excel'); 
            if(table && table.length){ 
                var preserveColors =  
               (table.hasClass('colorClass') ? true : false); 

               // This class's content is excluded from getting exported 
                $(table).table2excel({ 

                    exclude: ".noExl",  
                    name: "Output excel file ", 
                    filename:  
                    "outputFile-" + new Date().toString().replace(/[\-\:\.]/g, "") + ".xls", 

                    fileext: ".xls", //File extension type 
                    exclude_img: true, 
                    exclude_links: true, 
                    exclude_inputs: true, 
                    preserveColors: preserveColors 
                }); 
            } 
        });         
    });

    document.querySelector('.limitedText').addEventListener('input', function () {
    const textArea = this;
    const errorDiv = textArea.closest('.modal-body').querySelector('.error');
    const charCountElement = textArea.closest('.modal-body').querySelector('.charCount');
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
    charCountElement.textContent = ` ${maxLength - currentLength} characters remaining`;

    errorDiv.textContent = ''; // Clear error message if everything is fine
});

function validateInput(event) {
    const textarea = event.target;
    const maxLength = textarea.getAttribute('maxlength');
    const currentLength = textarea.value.length;

    // Update the remaining characters count
    const charCountElement = textarea.closest('.modal-body').querySelector('.charCount');
    charCountElement.textContent = ` ${maxLength - currentLength} characters remaining`;

    // Check if the current length exceeds the max length
    const errorElement = textarea.closest('.modal-body').querySelector('.error');
    if (currentLength > maxLength) {
        errorElement.textContent = `You have exceeded the maximum length of ${maxLength} characters.`;
    } else {
        errorElement.textContent = '';
    }
}