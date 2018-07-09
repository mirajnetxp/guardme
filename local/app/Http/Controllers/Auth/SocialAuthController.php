<?php

namespace Responsive\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Responsive\Http\Controllers\Controller;
use Responsive\Http\Repositories\SocialAuthRepository;
use Responsive\Http\Repositories\UsersRepository;
use Responsive\User;


class SocialAuthController extends Controller {
	use AuthenticatesUsers;

	/**
	 * @var SocialAuthRepository
	 */
	private $socialAuthRepository;
	private $socialite;
	/**
	 * @var UsersRepository
	 */
	private $usersRepository;

	/**
	 * SocialAuthController constructor.
	 *
	 * @param SocialAuthRepository $socialAuthRepository
	 * @param UsersRepository $usersRepository
	 */
	public function __construct( SocialAuthRepository $socialAuthRepository, UsersRepository $usersRepository ) {
		$this->socialAuthRepository = $socialAuthRepository;
		$this->usersRepository      = $usersRepository;
	}


	/**
	 * @param $social
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function socialLogin( $social ) {
		return Socialite::driver( $social )->redirect();
	}

	/**
	 * Obtain the user information from Socialite returned information.
	 *
	 * @param $provider
	 *
	 * @return Response
	 */
	public function handleProviderCallback( $provider ) {
		$userSocial = Socialite::driver( $provider )->user();
		$user       = $this->usersRepository->getUserByEmail( $userSocial->getEmail() );


		if ( $user ) {
			// log user in
			return $this->logAndRedirectUser( $user );
		} else {
			// todo: create a new user
			// first determine whether user should be a freelancer or an employer
			$new_user = $this->initUserFromSocialAuthProvider( $provider, $userSocial );

			return view( 'auth.complete-social-auth', compact( 'new_user' ) );
		}

	}

	public function completeSocialAuth( $provider ) {
		$data = \request()->all();

		$user = $this->usersRepository->getUserById( $data['uid'] );

		$user->admin = $data['usertype'];
		if (isset($data['gender']) ) {
			$user->gender = $data['gender'];
		}
		$user->save();

		return $this->logAndRedirectUser( $user );
	}

	private function logAndRedirectUser( $user ) {
		auth()->guard( 'web' )->login( $user );

		if ( auth()->check() && auth()->user()->id == 1 ) {

			return redirect( '/admin' );
		} else {
			return redirect( '/account' );
		}
	}

	private function initUserFromSocialAuthProvider( $provider, $social_user ) {
		if ( ! $social_user ) {
			return;
		}

		$user = new User();
		switch ( $provider ) {
			case 'facebook':
				$user->name   = $social_user->getName();
				$user->email  = $social_user->getEmail();
				$user->photo  = $social_user->getAvatar();
				break;
			case 'google':
				$user->name   = $social_user->getName();
				$user->email  = $social_user->getEmail();
				$user->gender = $social_user->user['gender'];
				$user->photo  = $social_user->getAvatar();
				break;
		}

		$user->save();

		return $user;
	}
}
