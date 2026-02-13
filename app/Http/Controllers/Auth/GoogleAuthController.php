<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
  public function redirect()
  {
    return Socialite::driver('google')->redirect();
  }

  public function callback()
  {
    $googleUser = Socialite::driver('google')->user();

    $user = User::updateOrCreate(
      ['google_id' => $googleUser->getId()],
      [
        'name' => $googleUser->getName(),
        'email' => $googleUser->getEmail(),
        'avatar' => $googleUser->getAvatar(),
      ]
    );

    if ($user->wasRecentlyCreated) {
      $list = ShoppingList::create([
        'name' => 'Mi Lista',
        'user_id' => $user->id,
      ]);
      $user->update(['primary_list_id' => $list->id]);
    }

    Auth::login($user, remember: true);

    return redirect()->intended('/');
  }

  public function logout()
  {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
  }
}
