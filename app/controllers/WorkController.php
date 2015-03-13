<?php

require_once("qiniu/rs.php");

class WorkController extends \BaseController {

	protected $accessKey = 'qyUmwicFpG9W3TmrbpU_1RbOfLBSD7RwwYkqNIf-';
	protected $secretKey = '_YFeoIgzO8reVZQHH9Uf6HDOLigVZoEm1Vr7Siln';
	
	/**
	 * Display a listing of the resource.
	 * GET /work
	 *
	 * @return Response
	 */
	public function postCreate()
	{
		$user_id = Sentry::getUser()->id;
		$name = Input::get('name');
		$work = new Work;
		$work->name = $name;
		$work->user_id = $user_id;
		if($work->save())
		{
			return Response::json(array('errCode' => 0,'work' => $work->toJson()));
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '创建成功！'));
		}
	}

	public function getToken()
	{
		$bucket = 'rabbitpre';
		Qiniu_SetKeys($this->accessKey, $this->secretKey);
		$putPolicy = new Qiniu_RS_PutPolicy($bucket);
		$upToken = $putPolicy->Token(null);

		return Response::json(array('errCode' => 0,'token' => $upToken));
	}

	public function postPublish()
	{
		$work_id = Input::get('work_id');
		$resources = Input::get('resources');
		$user = Sentry::getUser();
		$work = Work::find($work_id);

		if(!isset($work))
		{
			return Response::json(array('errCode' => 1,'message' => '该作品不存在！'));
		}

		if($work->user_id != $user->id)
		{
			return Response::json(array('errCode' => 1,'message' => '您没有权限！'));
		}

		foreach ($resources as $res) {
			$resource = new Resource;
			$resource->work_id = $work_id;
			$resource->page = $res->page;
			$resource->key = $res->key;
			$resource->downurl = $res->url;

			if(!$resource->save())
			{
				return Response::json(array('errCode' => 2,'message' => '保存失败！'));
			}
		}

		return Response::json(array('errCode' => 0,'message' => '保存成功！'));

	}

	public function getList()
	{
		$user = Sentry::getUser();
		$works = Work::where('user_id',$user->id)
			->with(array('resources' => function($query)
			{
				$query->orderBy('page');
			}))
			->paginate(15)
			->get()
			->toJson();

		$works = json_decode($works);

		return Response::json(array('errCode' => 0,'rabbitpres' => $works->data)));
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

		if($work->delete())
		{
			return Response::json(array('errCode' => 0,'message' => '删除成功！'));
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '删除失败！'));
		}
	}

	public function getAlbum()
	{
		$albums = Album::all();
		foreach($albums as $album)
		{
			$works = $album->works()->with('resources')->get();
			$album->works = $works;
		}

		return Response::json(array('errCode' => 0,'albums' => $albums));
	}

	public function postUpdate()
	{
		return Response::json(array('errCode' => 1,'message' => '修改失败！'));
	}
}