<?php

class WorkController extends \BaseController {

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
			return Response::json(array('errCode' => 0,'message' => '创建成功！'));
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '创建成功！'));
		}
	}

	

}