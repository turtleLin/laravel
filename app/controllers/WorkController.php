<?php

require_once("qiniu/rs.php");

class WorkController extends \BaseController {

	protected $accessKey = 'MGM-1zjkmsBA3QJuok6jempH5UihkdqD9PJDdpao';
	protected $secretKey = '3jNvwcLeetOkK5KbeW6yeu35G5GLFVVGCrr36Mp1';
	
	/**
	 * Display a listing of the resource.
	 * GET /work
	 *
	 * @return Response
	 */
	public function postCreate()
	{
		$user_id = Sentry::getUser()->id;
		$title = Input::get('title');
		$description = Input::get('description');
		$work = new Work;
		$work->title = $title;
		$work->description = $description;
		$work->user_id = $user_id;

		$bucket = 'rabbitpremobile';
		Qiniu_SetKeys($this->accessKey, $this->secretKey);
		$putPolicy = new Qiniu_RS_PutPolicy($bucket);
		$upToken = $putPolicy->Token(null);

		if($work->save())
		{
			return Response::json(array('errCode' => 0,'work' => $work->toJson(),'token' => $upToken));
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '创建失败！'));
		}
	}

	public function getToken()
	{
		$bucket = 'rabbitpremobile';
		Qiniu_SetKeys($this->accessKey, $this->secretKey);
		$putPolicy = new Qiniu_RS_PutPolicy($bucket);
		$upToken = $putPolicy->Token(null);

		return Response::json(array('errCode' => 0,'token' => $upToken));
	}

	public function postPublish()
	{
		try
		{
			$user = Sentry::getUser();
			$user_id = $user->id;
			$title = Input::get('title');
			$description = Input::get('description');

			$work = new Work;
			$work->title = $title;
			$work->description = $description;
			$work->user_id = $user_id;
			$work->save();


			$resources = Input::get('pictures');

			if(!isset($work))
			{
				return Response::json(array('errCode' => 1,'message' => '该作品不存在！'));
			}

			if($work->user_id != $user->id)
			{
				return Response::json(array('errCode' => 1,'message' => '您没有权限！'));
			}

			$work_id = $work->id;

			$domain = '7xi2us.com2.z0.glb.qiniucdn.com';
			Qiniu_SetKeys($this->accessKey, $this->secretKey);

			foreach ($resources as $resource) {
				$picture = new Picture;
				$picture->work_id = $work_id;
				$picture->page = $resource['page'];
				$picture->key = $resource['key'];

				$baseUrl = Qiniu_RS_MakeBaseUrl($domain, $resource['key']);
				$getPolicy = new Qiniu_RS_GetPolicy();
				$privateUrl = $getPolicy->MakeRequest($baseUrl, null);

				$picture->url = $privateUrl;
				$picture->save();

				if(array_key_exists('sound', $resource))
				{
					$sound = new Sound;
					$sound->picture_id = $picture->id;
					$sound->key = $resource['sound'];

					$baseUrl = Qiniu_RS_MakeBaseUrl($domain, $resource['sound']);
					$getPolicy = new Qiniu_RS_GetPolicy();
					$privateUrl = $getPolicy->MakeRequest($baseUrl, null);
					$sound->url = $privateUrl;

					$sound->save();
				}

				
				if(array_key_exists('text', $resource))
				{
					$texts = $resource['text'];
					foreach ($texts as $t) {
						$text = new Text;
						$text->text = $t['text'];
						$text->x = $t['x'];
						$text->y = $t['y'];
						$text->w = $t['w'];
						$text->h = $t['h'];
						$text->color = $t['color'];
						$text->font = $t['font'];
						$text->picture_id = $picture->id;
						$text->save();
					}
				}
				
				if(array_key_exists('watermark', $resource))
				{
					$watermark = $resource['watermark'];
					$wm = new Watermark;
					$wm->text = $watermark['text'];
					$wm->x = $watermark['x'];
					$wm->y = $watermark['y'];
					$wm->w = $watermark['w'];
					$wm->h = $watermark['h'];
					$wm->color = $watermark['color'];
					$wm->picture_id = $picture->id;
					$wm->save();
				}

				if(array_key_exists('bubble', $resource))
				{
					$bubbles = $resource['bubble'];

					foreach ($bubbles as $b) {
						$bubble = new Bubble;
						$bubble->type = $b['type'];
						$bubble->x = $b['x'];
						$bubble->y = $b['y'];
						$bubble->w = $b['w'];
						$bubble->h = $b['h'];
						$bubble->color = $b['color'];
						$bubble->alpha = $b['alpha'];
						$bubble->picture_id = $picture->id;
						$bubble->save();
					}
				}
			}

			$user->hot += 10;
			$user->save();
		}catch(Exception $e)
		{
			return Response::json(array('errCode' => 1,'message' => $e->getMessage()));
		}
		return Response::json(array('errCode' => 0,'message' => '发布成功!'));
	}

	public function getList()
	{
		$user = Sentry::getUser();
		$user_id = Input::get('user_id');
		$works = Work::where('user_id',$user_id)->orderBy('hot','desc')
			->with('pictures','comments')
			->paginate(15)
			->toJson();

		 $works = json_decode($works)->data;

		foreach ($works as $work) {
				$work->picturesnum = count($work->pictures);
				$pictures = array_chunk($work->pictures, 1);
				$work->pictures = $pictures[0];
				$work->commentnum = count($work->comments);
				unset($work->comments);
			}

		return Response::json(array('errCode' => 0,'rabbitpres' => $works));
	}
	
	public function postDelete()
	{
		$work_id = Input::get('work_id');
		$work = Work::find($work_id);
		$user = Sentry::getUser();

		if(!isset($work))
		{
			return Response::json(array('errCode' => 1,'message' => '该作品不存在！'));
		}

		if($work->user_id != $user->id)
		{
			return Response::json(array('errCode' => 1,'message' => '您没有权限！'));
		}

		$likes = Like::where('work_id',$work_id)->count();
		$comments = Comment::where('work_id',$work_id)->count();
		$sumScore = Score::where('work_id',$work_id)->sum('score');

		if($work->delete())
		{
			$user->hot = $user->hot - 10 - $likes - $comments - $sumScore;
			$user->save();
			return Response::json(array('errCode' => 0,'message' => '删除成功！'));
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '删除失败！'));
		}
	}

	public function getAlbums()
	{
		$user_id = Sentry::getUser()->id;
		$albums = Album::all();
		$num = count($albums);
		$works = Work::orderBy('hot','desc')
				->with(array('pictures' => function($query){
					$query->where('page',1);
				}))
				->with('comments')
				->take(6 * $num)
				->get();

		foreach ($works as $work) {
			$work->commentnum = count($work->comments);
			unset($work->comments);
		}
		$works = $works->toArray();
		$works = array_chunk($works, 6);
		$index = 0;		
		foreach ($albums as $album) {
				$album->works = $works[$index++];
			}	

		return Response::json(array('errCode' => 0,'albums' => $albums));
	}

	public function getScore()
	{
		try
		{
			$user_id = Sentry::getUser()->id;
			$user = User::find($user_id);
			$work_id = Input::get('work_id');
			$score = Input::get('score');
			$score = floatval($score);

			if($score < 0 || $score > 10)
			{
				return Response::json(array('errCode' => 1,'message' => 'score输入有误!'));
			}

			$work = Work::find($work_id);
			if(!isset($work))
			{
				return Response::json(array('errCode' => 1,'message' => '没有该作品！'));
			}

			$s = new Score;
			$s->work_id = $work_id;
			$s->user_id = $user_id;
			$s->score = $score;

			$work->hot += $score;
			$user->hot += $score;

			if($s->save() && $work->save() && $user->save())
			{
				$aScore = Score::where('work_id',$work_id)->avg('score');
				if(!$aScore)
					$aScore=0;

				return Response::json(array('errCode' => 0,'aScore' => $aScore));
			}
		}catch(Exception $e)
		{
			return Response::json(array('errCode' => 1,'message' => $e->getMessage()));
		}

		return Response::json(array('errCode' => 1,'message' => '评分失败!'));
	}

	public function postUpdate()
	{
		return Response::json(array('errCode' => 1,'message' => '修改失败！'));
	}

	public function getWork()
	{
		$user_id = Sentry::getUser()->id;
		$work_id = Input::get('work_id');
		$work = Work::with(array('pictures' => function($query)
			{
				$query->with('sound','texts','watermark','bubbles')->orderBy('page');
			}))
			->with('user')->find($work_id);
		
		if(!isset($work))
		{
			return Response::json(array('errCode' => 1,'message' => '没有该作品！')); 
		}

		$aScore = $work->scores()->avg('score');
		if(!$aScore)
			$aScore=0;
		$work->ascore = $aScore;
		$work->isscore = Score::where('work_id',$work->id)->where('user_id',$user_id)->count();
		$work->commentnum = Comment::where('work_id',$work->id)->count();
		$work->islike = Like::where('work_id',$work->id)->where('user_id',$user_id)->count();

		$work->isattention = Friend::where('friend_id',$work->user_id)->where('user_id',$user_id)->count();

		return Response::json(array('errCode' => 0,'work' => $work));
	}
}