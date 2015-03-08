<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
		$users = User::with('messages')->paginate(2)->toJson();
		$users = json_decode($users);
		//return View::make('index',compact('users'));
		return Response::json($users->data);
	}

	//注册
	public function postCreate()
	{
		try
			{
				$count1 = User::where('username','=',Input::get('username'))->count();
				$count2 = User::where('email','=',Input::get('email'))->count();
				if($count1)
				{
					return Response::json(array('errCode' => 1,'message' => '用户名已经存在！'));
				}
				if($count2)
				{
					return Response::json(array('errCode' => 1,'message' => '邮箱已经被注册！'));
				}
				$user = Sentry::createUser(array(
					'email' => Input::get('email'),
					'password' => Input::get('password'),
					'username' => Input::get('username'),
					'gender' => Input::get('gender'),
					'activated' => true
				));
				if($user)
				{
					$message = new Message;
					$message->title = '欢迎使用兔展!';
					$message->content = '欢迎使用兔展!';
					$message->sender = 'admin';
					$message->receiver = $user->username;
					$message->user_id = $user->id;
					$message->save();
					return Response::json(array('errCode' => 0,'message' => '注册成功！'));
				}
			}catch(\Exception $e)
			{
				return Response::json(array('errCode' => 2,'message' => $e->getMessage()));
			}
	}

	public function getCreate()
	{
		return View::make('register');
	}

	//登陆
	public function getLogin()
	{
		$cred = array(
				'username' => Input::get('username'),
				'password' => Input::get('password')
			);

		try
		{
			$user = Sentry::authenticate($cred,false);

			if($user)
			{
				return Resopnse::json(array('errCode' => 0,'message' => '登陆成功！'));
			}
		}catch(\Exception $e)
		{
			return Response::json(array('errCode' => 2,'message' => $e->getMessage()));
		}
	}

	public function getLogout()
	{
		Sentry::logout();
	}

	public function postUpdate()
	{
		try
		{
			$count = User::where('email','=',Input::get('email'))->count();
			if($count)
			{
				return Response::json(array('errCode' => 1,'message' => '邮箱已经被注册！'));
			}
			$user = Sentry::getUser();
			$user->email = Input::get('email');
			$user->gender = Input::get('gender');
			if($user->save())
			{
				return Response::json(array('errCode' => 0,'message' => '更新成功！'));
			}else
			{
				return Response::json(array('errCode' => 1,'message' => '更新失败！'));
			}
		}catch(\Exception $e)
		{
			return Response::json(array('errCode' => 2,'message' => $e->getMessage()));
		}
	}

	public function postChangePassword()
	{
		try
		{
			$user = Sentry::getUser();
			$oldPwd = Input::get('oldPwd');
			$newPwd = Input::get('newPwd');
			if($user->checkPassword($oldPwd))
			{
				$user->password = $newPwd;
				if($user->save())
				{
					return Response::json(array('errCode' => 0,'message' => '更新成功！'));
				}else
				{
					return Response::json(array('errCode' => 1,'message' => '更新失败！'));
				}
			}else
			{
				return Response::json(array('errCode' => 1,'message' => '旧密码输入错误！'));
			}
		}catch(\Exception $e)
		{
			return Response::json(array('errCode' => 2,'message' => $e->getMessage()));
		}
	}

	public function postDelete()
	{
		try
		{
			$user = Sentry::getUser();
			$username = Input::get('username');
			if($user->isadmin)
			{
				$deteleUser = Sentry::findUserByLogin($username);
				if($deteleUser->delete())
				{
					return Response::json(array('errCode' => 0,'message' => '删除成功！'));
				}
			}
		}catch(\Exception $e)
		{
			return Response::json(array('errCode' => 2,'message' => $e->getMessage()));
		}
	}

}
