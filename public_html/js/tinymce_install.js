tinymce.init({
	selector: "textarea#content",
	height: 500,
	theme: "modern",
	relative_urls:false,
	remove_script_host:false,
	convert_urls:true,
	plugins: [
		"advlist autolink lists link image charmap print preview hr anchor pagebreak",
		"searchreplace wordcount visualblocks visualchars code fullscreen",
		"insertdatetime media nonbreaking save table contextmenu directionality",
		"emoticons template paste textcolor colorpicker textpattern responsivefilemanager"
	],
	toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
	toolbar2: "| responsivefilemanager print preview media | forecolor backcolor emoticons",
	image_advtab: true,
	external_filemanager_path: "../filemanager/",
	filemanager_title: "Instafxng Filemanager",
//        external_plugins: { "filemanager" : "../filemanager/plugin.min.js"},
//        templates: [
//            {title: 'Test template 1', content: 'Test 1'},
//            {title: 'Test template 2', content: 'Test 2'}
//        ]

});