{{Form::open(array('url'=>'user/login','method' => 'get'))}}
	<p>
		{{Form::label('username:')}}
		{{Form::text('username')}}
	</p>
	<p>
		{{Form::label('password:')}}
		{{Form::text('password')}}
	</p>
	<p>
		{{Form::submit('submit')}}
	</p>
{{Form::close()}}