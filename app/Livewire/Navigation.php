<?php
namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Navigation extends Component {
  public function render() {
    $primaryListName = Auth::user()->primaryList->name ?? 'Inicio';
    return view('livewire.navigation', compact('primaryListName'));
  }

  #[On('list-changed')]
  public function refreshNavigation(): void {
    // Forzar re-render para mostrar el nuevo nombre de lista
  }
}
