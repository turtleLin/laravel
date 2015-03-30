<?php

class NewsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /news
	 *
	 * @return Response
	 */
	public function postRead()
	{
		$news_id = Input::get('news_id');
		$news = News::find($news_id);

		if(!isset($news))
		{
			return Response::json(array('errCode' => 1,'message' => '该消息不存在!'));
		}

		if($news->user_id != Sentry::getUser()->id)
		{
			return Response::json(array('errCode' => 1,'message' => '没有权限进行操作!'));
		}

		$news->isread = 1;
		$news->save();

		$code = Input::get('code');

		if($code == 0)
		{
			$user_id = Input::get('user_id');
			$user = User::find($user_id);

			if(!isset($user))
			{
				return Response::json(array('errCode' => 1,'message' => '该用户不存在!'));
			}

			$workNum = $user->works()->count();
			$friendNum = $user->friends()->count();
			$fanNum = $user->fans()->count();

			$user->workNum = $workNum;
			$user->friendNum = $friendNum;
			$user->fanNum = $fanNum;

			return Response::json(array('errCode' => 0,'user' => $user));
		}

		if($code == 1)
		{
			$work_id = Input::get('work_id');
			$work = Work::find($work_id);

			if(!isset($work))
			{
				return Response::json(array('errCode' => 1,'message' => '该作品不存在!'));
			}

			$aScore = $work->scores()->avg('score');
			$work->ascore = $aScore;
			$user_id = Sentry::getUser()->id;
			$work->isscore = Score::where('work_id',$work->id)->where('user_id',$user_id)->count();
			$work->commentnum = Comment::where('work_id',$work->id)->count();
			$work->islike = Like::where('work_id',$work->id)->where('user_id',$user_id)->count();

			return Response::json(array('errCode' => 0,'work' => $work));
		}

		return Response::json(array('errCode' => 1,'message' => '输入的code有误!')); 
	}

	public function getList()
	{
		$user_id = Sentry::getUser()->id;
		$user = User::find($user_id);

		$newss = $user->newss()->orderBy('isread')->orderBy('id','desc')->paginate(15)->toJson();

		$news = json_decode($newss)->data;

		foreach ($news as $n) {
			$user = User::find($n->sender_id);
			$n->head = $user->head;
		}

		return Response::json(array('errCode' => 0,'news' => $news));
	}

	public function getNoRead()
	{
		$user_id = Sentry::getUser()->id;
		$user = User::find($user_id);

		$count = $user->newss()->where('isread',0)->count();

		return Response::json(array('errCode' => 0,'count' => $count));
	}

}