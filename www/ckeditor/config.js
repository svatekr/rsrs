/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.toolbar = [
		{ name: 'source', items: ['Source', 'ShowBlocks', 'Maximize'] },
		{ name: 'clipboard', items: ['Undo', 'Redo', '-', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'] },
		{ name: 'editing', items: ['Find', 'Replace'] },
		{ name: 'p2', items: ['Blockquote', 'Outdent', 'Indent', '-', 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
		{ name: 'basicstyles', items: ['Bold', 'Italic', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
		'/',
		{ name: 'syntax', items: ['Syntaxhighlight'] },
		{ name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
		{ name: 'colors', items: ['BGColor', 'TextColor'] },
		{ name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
		{ name: 'paragraph', items: [] },
		{ name: 'insert', items: ['CreatePlaceholder', 'CreateDiv', 'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'Flash', 'Glyphicons', 'WidgetTemplateMenu', 'BootstrapTabs'] },
		{ name: 'insert2', items: ['btbutton', 'btgrid', 'lightbox'] }
	];

	config.extraPlugins = 'widgetbootstrap,widgettemplatemenu,floatpanel,panel,lineutils,glyphicons';
	config.removeButtons = 'Print,Preview,NewPage,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,BidiLtr,BidiRtl,Language,Iframe,PageBreak';
	config.contentsCss = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css';

	config.extraAllowedContent = '(*){*}[*]';
	config.allowedContent = true;
	config.filebrowserBrowseUrl = '../../kcfinder/browse.php?opener=ckeditor&type=files';
	config.filebrowserImageBrowseUrl = '../../kcfinder/browse.php?opener=ckeditor&type=images';
	config.filebrowserFlashBrowseUrl = '../../kcfinder/browse.php?opener=ckeditor&type=flash';
	config.filebrowserUploadUrl = '../../kcfinder/upload.php?opener=ckeditor&type=files';
	config.filebrowserImageUploadUrl = '../../kcfinder/upload.php?opener=ckeditor&type=images';
	config.filebrowserFlashUploadUrl = '../../kcfinder/upload.php?opener=ckeditor&type=flash';
	config.allowedContent = true;
	config.autoParagraph = false;
};

CKEDITOR.dtd.$removeEmpty['span'] = false;
CKEDITOR.dtd.$removeEmpty['i'] = false;
