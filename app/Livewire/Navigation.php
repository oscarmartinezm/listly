<?php
namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Navigation extends Component {
  public $primaryListName;
  public $primaryListId;
  public $ownedLists;
  public $sharedLists;

  public function mount(): void {
    $this->updateListData();
  }

  public function updateListData(): void {
    $user = Auth::user()->fresh();
    $this->primaryListName = $user->primaryList->name ?? 'Inicio';
    $this->primaryListId = $user->primary_list_id;
    $this->ownedLists = $user->ownedLists;
    $this->sharedLists = $user->sharedLists;
  }

  public function setActiveList(int $listId): void {
    Auth::user()->update(['primary_list_id' => $listId]);
    $this->redirect(route('home'), navigate: true);
  }

  public function render() {
    return view('livewire.navigation');
  }

  #[On('list-changed')]
  public function refreshNavigation(): void {
    $this->updateListData();
  }
}
