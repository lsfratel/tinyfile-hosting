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
				<div class="col-md-6 text-left">
					<h4>TinyFile rules:</h4>
					<ul>
						<li>no download, upload limits</li>
						<li>{{ $humanizer->fileSize($maxFileSize) }} per file</li>
						<li>downloaded files hosted for ever</li>
						<li>100% free</li>
					</ul>
				</div>
				<div class="col-md-6">
				{% if($file) %}
					<span class="text-left">
						<h4 class="text-danger">{{$file->name_original}}</h4>
						<p><strong>Uploaded:</strong> {{$file->uploaded_at}}</p>
						<p><strong>Downloads:</strong> {{$file->downloads}}</p>
						<p><strong>Size:</strong> {{$file->size}}</p> <br>
					</span>
					<form action="/delete/{{$file->delete_token}}" method="POST">
						<button type="submit" class="btn btn-block btn-danger">** DELETE THIS FILE **</button>
					</form>
				{% endif %}
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
