@foreach($users as $user)
	<p>{{$user->username}}</P>
	<p>{{$user->email}}</p>
	<p>{{$user->isadmin}}</p>
@endforeach
{{$users->links()}}