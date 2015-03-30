<?php

class MessageController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /message
	 *
	 * @return Response
	 */
	public function postSend()
	{
		if(!Input::has('title'))
		{
			return Response::json(array('errCode' => 1,'message' => '标题不能为空！'));
		}
		if(!Input::has('content'))
		{
			return Response::json(array('errCode' => 1,'message' => '内容不能为空！'));
		}
		$title = Input::get('title');
		$content = Input::get('content');
		$receiver = Input::get('receiver');
		$user = User::where('username',$receiver)->first();
		if(!isset($user))
		{
			return Response::json(array('errCode' => 1,'message' => '发送的用户不存在！'));
		}
		$sender = Sentry::getUser()->username;
		$user_id = $user->user_id;

		$message = new Message;
		$message->title = $title;
		$message->content = $content;
		$message->sender = $sender;
		$message->receiver = $receiver;
		$message->user_id = $user_id;
		if($message->save())
		{
			return Response::json(array('errCode' => 0,'message' => '发送成功！'));
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '发送失败！'));
		}
	}

	public function getRead() //读取消息列表
	{
		$user_id = Sentry::getUser()->id;
		$messages = Message::where('user_id',$user_id)->orderBy('isread')->orderBy('id','desc')->paginate(15)->toJson();
		$message = json_decode($messages);

		return Response::json(array('errCode' => 0,'msgList' => $message->data));
	}

	public function getHasRead() //读一条消息
	{
		$user_id = Sentry::getUser()->id;
		$id = Input::get('id');
		$message = Message::find($id);
		if(isset($message))
		{
			if($message->user_id == $user_id)
			{
				$message->isread = true;
				$message->save();
			}
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '该消息不存在！'));
		}
	}
}