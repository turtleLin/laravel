<?php

class CommentController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /comment
	 *
	 * @return Response
	 */
	public function postCreate()
	{
		$sender = Sentry::getUser()->username;
		$work_id = Input::get('work_id');
		$content = Input::get('content');
		$receiver = Input::get('receiver');

		$user = User::where('username',$receiver)->first();

		if(!isset($user))
		{
			return Response::json(array('errCode' => 1,'message' => '发送的用户不存在！'));
		}

		$receiver_id = $user->id;

		$comment = new Comment;
		$comment->content = $content;
		$comment->sender = $sender;
		$comment->receiver = $receiver;
		$comment->work_id = $work_id;
		$comment->receiver_id = $receiver_id;

		if($comment->save())
		{
			return Response::json(array('errCode' => 0,'message' => $comment->toJson()));
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '评论失败！'));
		}
	}

	public function postDelete()
	{
		$user = Sentry::getUser();
		$comment_id = Input::get('commentId');
		$comment = Comment::find($comment_id);

		if(!isset($comment))
		{
			return Response::json(array('errCode' => 1,'message' => '该评论不存在！'));
		}

		if($comment->sender != $user->username && $comment->receiver_id != $user->id)
		{
			return Response::json(array('errCode' => 1,'message' => '您没有权限操作！'));
		}

		if(comment->delete())
		{
			return Response::json(array('errCode' => 0,'message' => '删除成功！'));
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '删除失败！'));
		}
	}

	public function getRead() //读取评论列表
	{
		$work_id = Input::get('work_id');
		$work = Work::find($work_id);
		if(!isset($work))
		{
			return Response::json(array('errCode' => 1,'message' => '该作品不存在！'));
		}
		$comments = $work->comments()->paginate(15)->toJson();
		$comments = json_decode($comments);

		return Response::json(array('errCode' => 0,'comments' => $friends->data));
	}

	public function getHasRead() //读一条评论
	{
		$user_id = Sentry::getUser()->id;
		$id = Input::get('commentId');
		$comment = Comment::find($id);
		if(isset($comment))
		{
			if($message->receiver_id == $user_id)
			{
				$message->isread = true;
				$message->save();
			}
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '该评论不存在！'));
		}
	}
}