<?php

namespace app\cmwn\Users;
use app\Repositories\SideBarItems;
use app\User;
use Illuminate\Support\Facades\Auth;

class UserSpecificRepository
{

    public $tag;

	public function __construct(SideBarItems $tag){
		$this->tag = $tag;
	}

	public function compose($view){

		$acceptedfriends = array();
		$pendingfriends = array();
		$friendrequests = array();
		if (Auth::check()) {
			$acceptedfriends = User::find(Auth::user()->id)->acceptedfriends;
			$pendingfriends = User::find(Auth::user()->id)->pendingfriends;
			$friendrequests = User::find(Auth::user()->id)->friendrequests;
		}



		$view->with('tags', $this->tag->getAll())->with('acceptedfriends', $acceptedfriends)->with('pendingfriends', $pendingfriends)->with('friendrequests', $friendrequests);
	}

}
