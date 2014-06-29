<?php

class usersController extends \BaseController {

<<<<<<< HEAD

public function __construct() {
    $this->beforeFilter('csrf', array('on'=>'post'));
    $this->beforeFilter('auth', array('only'=>array('getDashboard')));
}
  //  protected $layout = 'layouts.master';

=======
    //protected $layout = 'layouts.master';
  public function __construct()
  {
    $this->beforeFilter('csrf', array('on'=>'post'));
    $this->beforeFilter('auth', array('only'=>array('getDashboard')));
  }
>>>>>>> master
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::all();
		return print_r($users,true);
	}

  /**
	 *Log into Douglas
	 *
	 *@return Response
	 */
	public function getLogin()
	  {
		return View::make('users/login')->with('pageTitle', 'SugarCRM Douglas Login');
    }

  public function postLogin()
  {
    if (Auth::attempt(array('username'=>Input::get('username'), 'password'=>Input::get('password')))) {
<<<<<<< HEAD
    return Redirect::to('users/dashboard')->with('pageTitle', 'SugarCRM Douglas Create Users');
      } else {
    return Redirect::to('users/login')
        ->with('message', 'Your username/password combination was incorrect')
        ->with('pageTitle', 'SugarCRM Douglas Login')
        ->withInput();
      }
  }

  public function getLogout() {
    Auth::logout();
    return Redirect::to('users/login')->with('message', 'Your are now logged out!')
    ->with('pageTitle', 'SugarCRM Douglas Login');

    }
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		return View::make('users/create')->with('pageTitle', 'SugarCRM Douglas Create Users');
	}

  public function postCreate()
  {
       $validator = Validator::make(Input::all(), User::$rules);
           if ($validator->passes()) {
                 $user = new User;
                 $user->id = '1234';
                 $firstname = Input::get('firstname');
                 $lastname = Input::get('lastname');
                 $user->fullname = $firstname." ".$lastname;
                 $uname = substr($firstname,0,1).$lastname;
                 $user->username = $uname;
                 $user->email = Input::get('email');
                 $user->password = Hash::make(Input::get('password'));
                 $user->save();

    return Redirect::to('users/login')->with('pageTitle', 'SugarCRM Douglas Create Users');
} else {
    echo "Failure!";
    //return Redirect::to('users/login')->with('pageTitle', 'SugarCRM Douglas Create Users');
}
  }



public function getDashboard() {
    return View::make('users.dashboard')->with('pageTitle', 'SugarCRM Douglas Dashboard');
}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}
=======
    return Redirect::to('users/dashboard')->with('message', 'You are now logged in!')
    ->with('pageTitle', 'SugarCRM Douglas Login');
      } else {
    return Redirect::to('users/login')
        ->with('pageTitle', 'SugarCRM Douglas Login')
        ->with('message', 'Your username/password combination was incorrect')
        ->withInput();
      }
  }
  public function getCreate()
    {
    return View::make('users/createUser')->with('pageTitle', 'SugarCRM Douglas Create User');
    }

  public function postCreate() {
    $validator = Validator::make(Input::all(), User::$rules);

    if ($validator->passes()) {
      $user = new User;
      $user->fullname = Input::get('firstname').Input::get('lastname');
      //$user->firstname = Input::get('firstname');
      //$user->lastname = Input::get('lastname');
      $user->email = Input::get('email');
      $user->password = Hash::make(Input::get('password'));
      $user->save();
    } else {
            return Redirect::to('users/createUser')->with('pageTitle', 'SugarCRM Douglas Create User')
            ->with('message', 'The following errors occurred')
            ->withErrors($validator)
            ->withInput();
    }
  }
>>>>>>> master


  public function getDashboard()
  {
    return View::make('users/dashboard')->with('pageTitle', 'SugarCRM Douglas Dashboard');
  }

  public function getLogout()
  {
    Auth::logout();
    return Redirect::to('users/login')->with('pageTitle', 'SugarCRM Douglas Dashboard')
    ->with('message', 'Your are now logged out!');
  }




}
