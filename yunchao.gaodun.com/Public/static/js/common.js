/*头像照片上传*/

jQuery(document).ready(function($)
						{
							var i = 1,
								$example_dropzone_filetable = $("#example-dropzone-filetable"),
								example_dropzone = $("#advancedDropzone").dropzone({
								url: 'data/upload-file.php',
								
							
								success: function(file)
								{
									file.fileEntryTd.find('td:last').html('<span class="text-success">Uploaded</span>');
									file.progressBar.removeClass('progress-bar-warning').addClass('progress-bar-success');
								},
								
								error: function(file)
								{
									file.fileEntryTd.find('td:last').html('<span class="text-danger">Failed</span>');
									file.progressBar.removeClass('progress-bar-warning').addClass('progress-bar-red');
								}
							});
							
							$("#advancedDropzone").css({
								minHeight: 200
							});
			
						});