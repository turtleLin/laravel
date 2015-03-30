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
		$sender = Sentry::getUser();
		$work_id = Input::get('work_id');
		$content = Input::get('content');
		$receiver = Input::get('receiver');

		$user = User::where('username',$receiver)->first();
		$work = Work::find($work_id);

		if(!isset($user))
		{
			return Response::json(array('errCode' => 1,'message' => '发送的用户不存在！'));
		}

		if(!isset($work))
		{
			return Response::json(array('errCode' => 1,'message' => '该作品不存在！'));
		}

		$comment = new Comment;
		$comment->content = $content;
		$comment->sender_id = $sender->id;
		$comment->work_id = $work_id;

		if($comment->save())
		{
			$owner = User::find($work->user_id);
			if($work->user_id != $user->id)
			{
				$owner->comments()->save($comment);

				$news = new News;
				$news->work_id = $work_id;
				$news->sender_id = $sender->id;
				$news->user_id = $user->id;
				$news->code = 1;
				$news->content = $sender->username . '回复了您!';
				$news->save();
			}

			$user->comments()->save($comment);

			$owner->hot++;
			$owner->save();
			$work->hot++;
			$work->save();

			$news = new News;
			$news->work_id = $work_id;
			$news->sender_id = $sender->id;
			$news->user_id = $work->user_id;
			$news->code = 1;
			$news->content = $sender->username . '评论了您!';
			$news->save();

			return Response::json(array('errCode' => 0,'message' => '评论成功!'));
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

		$work = Work::find($comment->work_id);

		if($comment->sender_id != $user->id && $work->user_id != $user->id)
		{
			return Response::json(array('errCode' => 1,'message' => '您没有权限操作！'));
		}

		$user = User::find($work->user_id);

		if($comment->delete())
		{
			$user->hot = $user->hot - 1;
			$user->save();
			$work->hot = $work->hot - 1;
			$work->save();

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
		$comments = $work->comments()->with('user','users')->paginate(15)->toJson();
		$comments = json_decode($comments)->data;

		return Response::json(array('errCode' => 0,'comments' => $comments));
	}

	public function getHasRead() //读一条评论
	{
		$user_id = Sentry::getUser()->id;
		$comment_id = Input::get('commentId');
		$comment_user = Comment_user::where('comment_id',$comment_id)->where('user_id',$user_id)->first();
		if(isset($comment_user))
		{
			$comment_user->isread = 1;
			if($comment_user->save())
			{
				return Response::json(array('errCode' => 0,'message' => '查看成功！'));
			}else{
				return Response::json(array('errCode' => 1,'message' => '查看失败！'));
			}
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '非法操作！'));
		}
	}
}