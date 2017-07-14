tinymce.init({
	selector: '.wysiwyg'
});

$('#tabs a').click(function(e) {
	e.preventDefault()
	$(this).tab('show')
});

// "myAwesomeDropzone" is the camelized version of the HTML element's ID
Dropzone.options.myDropzone = {
	paramName: "file", // The name that will be used to transfer the file
	maxFilesize: 2, // MB
	parallelUploads: 1,
	dictDefaultMessage: 'Arrastra imágenes aquí',
	dictFallbackMessage: 'Tu browser no soporta Drag and Drop',
	dictFileTooBig: 'Imagen muy grande ({{filesize}}MiB). Tamaño máximo: {{maxFilesize}}MiB.',
	init: function() {
		this.on("complete", function(file) {
			var self = this;
			console.log(file);
			// setTimeout(function(){
			// 	self.removeFile(file);
			// },3000);
		});
	}
};

/* menu open */
$(document).on('click', '.menu-link', function() {
	let menu = $('.menu');
	let options = {
		left: 0
	}

	if (menu.position().left > -1) {
		options.left = -1000;
	}
	TweenLite.to(menu, 0.5, options);
});