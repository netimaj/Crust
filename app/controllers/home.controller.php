<?php
/*
  Home Controller
*/
class HomeController extends ApplicationController
{
	public function index()
	{
		$this->assign('title', 'Hello World!');
	}
}