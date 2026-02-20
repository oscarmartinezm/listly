<?php
namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navigation extends Component {
  public $primaryListName;
  public $primaryListId;
  public $ownedLists;
  public $sharedLists;

  public function mount(): void {
    $user = Auth::user();
    $this->primaryListName = $user->primaryList->name ?? 'Inicio';
    $this->primaryListId = $user->primary_list_id;
    $this->ownedLists = $user->ownedLists;
    $this->sharedLists = $user->sharedLists;
  }

  public function render() {
    return view('livewire.navigation');
  }
}
