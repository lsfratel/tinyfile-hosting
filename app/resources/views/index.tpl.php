{% extends: 'layout' %}

{% block:content %}
<div class="wrapper">
	<div class="header">
		<a href="/">
			<img src="/assets/img/logo.png" alt="TinyFile File hosting with no limits, totaly free">
		</a>
	</div>
	<div class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<div class="inputs" hidden>
						<div class="link text-left" style="margin-top: 3px;">
							<span class="text-success">Download Link:</span>
							<div class="input-group">
								<input type="text" class="form-control link-input">
								<span class="input-group-btn">
									<button class="btn btn-success btn-copy" title="Copy Download Link" data-clipboard-target=".link-input">
										<span class="glyphicon glyphicon-copy"></span>
									</button>
								</span>
							</div>
						</div>
						<div class="link text-left" style="margin-top: 3px;">
							<span class="text-danger">Delete Link:</span>
							<div class="input-group">
								<input type="text" class="form-control link-delete-input">
								<span class="input-group-btn">
									<button class="btn btn-danger btn-copy" title="Copy Delete Link" data-clipboard-target=".link-delete-input">
										<span class="glyphicon glyphicon-trash"></span>
									</button>
								</span>
							</div>
						</div>
					</div>
					<span class="btn btn-success fileinput-button" style="margin-top: 35px;">
						Choose File
						<input type="file" name="file" class="file-input">
					</span>
					<div class="progress progress-striped active" style="margin-top: 55px;">
						<div class="progress-bar progress-bar-success">
							<span class="progress-bitrate"></span>
						</div>
					</div>
					<div class="alert alert-dismissible alert-danger" style="margin-top: 25px;" hidden></div>
				</div>
				<div class="col-md-6 text-left">
					<h4>TinyFile rules:</h4>
					<ul>
						<li>no download, upload limits</li>
						<li>{{ $humanizer->fileSize($maxFileSize) }} per file</li>
						<li>downloaded files hosted for ever</li>
						<li>100% free</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<small class="text-center">
		<span class="margin-top:-3px;color:lightgray">file hosting, free file hosting, file hosting with no limits, file upload, tiny upload, tiny file</span> <br>
		2015-2016 All Rights Reserved - <a href="mailto:admin@mail.com">Abuse or Copyright Infringement</a>
	</small>
</div>
{% endblock %}

{% block:scripts %}
<script>
	var link 		= $('.inputs'),
		alert   	= $('.alert'),
		file 		  = $('.fileinput-button'),
		progress 	= $('.progress'),
		input 		= $('.link-input'),
		deleteInput = $('.link-delete-input'),
		clipboard 	= new Clipboard('.btn-copy');

	function errorHandler(error) {
		link.hide();
    file.hide();
    progress.hide();
    alert.empty();
		alert.append(error);
		alert.append("<a href='/' style='color:white;font-weight:bold;text-decoration:none;'> Try Again</a>.");
		alert.fadeIn('slow');
	}

	function speedHandler(spd) {
		$('.progress-bitrate').text(spd);
	}

	$('.file-input').fileupload({
    url: '/upload',
    dataType: 'json',
		add: function(e, data) {
      if (data.originalFiles[0]['size'] > {{$maxFileSize}}) {
        errorHandler('The file exceeds the limit.');
			} else {
				file.hide();
				progress.fadeIn();
				data.submit();
			}
		},
		error: function(data) {
			setTimeout(function() {
				errorHandler('Something went wrong, try again later.');
			}, 1000);
		},
        done: function (e, data) {
          setTimeout(function() {
          var result = data.result;
          if (result.success) {
            progress.hide();
            input.val(result.url);
            deleteInput.val(result.delete);
            link.fadeIn();
          } else {
            if (result.message.indexOf('upload_max_filesize') > -1)
              errorHandler('Something went wrong, try again later.');
            else
              errorHandler(result.message);
          }
        }, 1000);
      }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

	$('.file-input').bind('fileuploadprogress', function (e, data) {
		var progress = parseInt(data.loaded / data.total * 100, 10);
		var bits = data.bitrate;

		$('.progress .progress-bar').css('width', progress + '%');

		if (bits >= 1000000000) {
			return speedHandler((bits / 1000000000).toFixed(2) + ' Gbit/s');
		}

		if (bits >= 1000000) {
			return speedHandler((bits / 1000000).toFixed(2) + ' Mbit/s');
		}

		if (bits >= 1000) {
			return speedHandler((bits / 1000).toFixed(2) + ' kbit/s');
		}

		speedHandler(bits.toFixed(2) + ' bit/s');
	});
</script>
{% endblock %}
