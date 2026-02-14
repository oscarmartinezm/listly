<?php
namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Support\Facades\Auth;

class ListShareController extends Controller {
  public function accept(string $token) {
    $list = ShoppingList::where('share_token', $token)->firstOrFail();
    $user = Auth::user();

    if ($list->user_id === $user->id) {
      return redirect()->route('home')->with('message', 'Ya eres dueño de esta lista');
    }

    if (! $list->sharedUsers()->where('user_id', $user->id)->exists()) {
      $list->sharedUsers()->attach($user->id);
    }

    if (! $user->primary_list_id) {
      $user->update(['primary_list_id' => $list->id]);
    }

    return redirect()->route('home')->with('message', "Te uniste a la lista \"{$list->name}\"");
  }
}
