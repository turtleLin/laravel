<?php
require_once("qiniu/rs.php");

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	protected $accessKey = 'MGM-1zjkmsBA3QJuok6jempH5UihkdqD9PJDdpao';
	protected $secretKey = '3jNvwcLeetOkK5KbeW6yeu35G5GLFVVGCrr36Mp1';

	public function index()
	{
		
		$work = Work::with('user')->find(50);

		return Response::json($work);
	}

	public function sendEmail()
	{
		$possible_charactors = "abcdefghijklmnopqrstuvwxyz0123456789"; //产生随机数的字符串
		$salt  =  ""; 
		while(strlen($salt) < 6) 
		{ 
		 	 $salt .= substr($possible_charactors,rand(0,strlen($possible_charactors)-1),1); 
		}

		Mail::send('token',array('token' => $salt),function($message)
				{
					$message->to('930030895@qq.com','')->subject('兔展移动端验证码!');
				});
	}

	//注册
	public function postCreate()
	{
		try{
				$reg = "/^[_.0-9a-z-a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$/";
				$email = Input::get('email');
				if(!preg_match($reg, $email))
					return Response::json(array('errCode' => 1,'message' => '邮箱格式不对!'));

				$count1 = User::where('username',Input::get('username'))->count();
				$count2 = User::where('email',Input::get('email'))->count();
				if($count1)
				{
					return Response::json(array('errCode' => 1,'message' => '用户名已经存在！'));
				}
				if($count2)
				{
					return Response::json(array('errCode' => 1,'message' => '邮箱已经被注册！'));
				}

				$domain = '7xi2us.com2.z0.glb.qiniucdn.com';
				Qiniu_SetKeys($this->accessKey, $this->secretKey);
				$baseUrl = Qiniu_RS_MakeBaseUrl($domain, Input::get('head'));
				$getPolicy = new Qiniu_RS_GetPolicy();
				$privateUrl = $getPolicy->MakeRequest($baseUrl, null);

				$password = Input::get('password');
				$username = Input::get('username');

				if(strlen($password) < 6)
				{
					return Response::json(array('errCode' => 1,'message' => '密码长度不能少于6位！'));
				}
				
				if(strlen($password) > 12)
				{
					return Response::json(array('errCode' => 1,'message' => '密码长度不能多于12位！'));
				}

				if(strlen($username) < 5)
				{
					return Response::json(array('errCode' => 1,'message' => '用户名长度不能少于6位！'));
				}

				if(strlen($username) > 16)
				{
					return Response::json(array('errCode' => 1,'message' => '用户名长度不能多于16位！'));
				}	

				$user = Sentry::createUser(array(
					'email' => Input::get('email'),
					'password' => $password,
					'username' => $username,
					'gender' => Input::get('gender'),
					'head' => $privateUrl,
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
					return Response::json(array('errCode' => 0,'user' => User::find($user->id)));
				}
			}catch(\Exception $e)
			{
				return Response::json(array('errCode' => 1,'message' => '用户名或密码错误!'));
			}
	}

	public function getEmail()
	{
		$email = Input::get('email');
		$reg = "/^[_.0-9a-z-a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$/";
		if(!preg_match($reg, $email))
			return Response::json(array('errCode' => 1,'message' => '邮箱格式不对!'));
		$count = User::where('email',$email)->count();
		if($count)
		{
			return Response::json(array('errCode' => 1,'message' => '邮箱已经被注册！'));
		}

		return Response::json(array('errCode' => 0,'message' => '邮箱可用！'));
	}

	public function getCreate()
	{
		return View::make('register');
	}

	public function getChange()
	{
		return View::make('change');
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
			$user = Sentry::authenticate($cred,true);

			if($user)
			{
				return Response::json(array('errCode' => 0,'user' => User::find($user->id)));
			}else
			{
				return Response::json(array('errCode' => 1,'message' => '用户名或密码错误!'));
			}
		}catch(\Exception $e)
		{
			return Response::json(array('errCode' => 1,'message' => '用户名或密码错误!'));
		}
	}

	public function getLogout()
	{
		Sentry::logout();
		return Response::json(array('errCode' => 0,'message' => '退出成功!'));
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
			return Response::json(array('errCode' => 1,'message' => $e->getMessage()));
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
				$resetCode = $user->getResetPasswordCode();
				if($user->attemptResetPassword($resetCode, $newPwd))
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
			return Response::json(array('errCode' => 1,'message' => $e->getMessage()));
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
			return Response::json(array('errCode' => 1,'message' => $e->getMessage()));
		}
	}

	public function getMaster()
	{
		$masters = Master::all();
		$index = 0;
		foreach ($masters as $master) {
			$users = User::orderBy('hot','desc')
				->skip($index * 8)
				->take(8)
				->get();
			$index++;

			$master->users = $users;
		}

		return  Response::json(array('errCode' => 0,'master' => $masters));
	}

	public function getUser()
	{
		$user_id = Input::get('id');
		$user = User::with('works','friends','fans')->find($user_id);

		if(!isset($user))
		{
			return Response::json(array('errCode' => 1,'message' => '该用户不存在!'));
		}

		$workNum = count($user->works);
		$friendNum = count($user->friends);
		$fanNum = count($user->fans);
		$isattention = Friend::where('user_id',Sentry::getUser()->id)->where('friend_id',$user_id)->count();

		$user->workNum = $workNum;
		$user->friendNum = $friendNum;
		$user->fanNum = $fanNum;
		$user->isattention = $isattention;

		return Response::json(array('errCode' => 0,'user' => $user));
	}

	public function getForgetPassword()
	{
		$username = Input::get('username');
		$email = Input::get('email');

		$user = User::where('username',$username)->first();

		if(!isset($user))
		{
			return Response::json(array('errCode' => 1,'message' => '该用户不存在!'));
		}

		if($user->email != $email)
		{
			return Response::json(array('errCode' => 1,'message' => '输入的邮箱不正确!'));
		}

		$possible_charactors = "abcdefghijklmnopqrstuvwxyz0123456789"; //产生随机数的字符串
		$salt  =  ""; 
		while(strlen($salt) < 6) 
		{ 
		 	 $salt  .=  substr($possible_charactors,rand(0,strlen($possible_charactors)-1),1); 
		}

		$token = new Token;
		$token->user_id = $user->id;
		$token->tokens = $salt;
		if($token->save())
		{
			Mail::send('token',array('token' => $salt),function($message) use ($email)
				{
					$message->to($email,'')->subject('兔展移动端验证码!');
				});
			return Response::json(array('errCode' => 0,'message' => '验证码发到您的邮箱了!!'));
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '创建验证码失败!'));
		}
	}

	public function getCheckToken()
	{
		$username = Input::get('username');
		$token = Input::get('token');
		$password = Input::get('password');

		$user = User::where('username',$username)->first();

		if(!isset($user))
		{
			return Response::json(array('errCode' => 1,'message' => '该用户不存在!'));
		}

		$to = $user->tokens()->orderBy('id','desc')->first();

		if($to->tokens == $token)
		{
			$user = Sentry::findUserById($user->id);
			$resetCode = $user->getResetPasswordCode();
			if($user->attemptResetPassword($resetCode, $password))
			{
				Token::where('user_id',$user->id)->delete();
				return Response::json(array('errCode' => 0,'message' => '修改成功!'));
			}
			return Response::json(array('errCode' => 1,'message' => '修改失败!'));
		}else{
			return Response::json(array('errCode' => 1,'message' => '错误!'));
		}
	}

}
