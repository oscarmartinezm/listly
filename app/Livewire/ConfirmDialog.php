<?php
namespace App\Livewire;

use Livewire\Component;

class ConfirmDialog extends Component {
  public bool $show = false;
  public string $title = '';
  public string $message = '';
  public string $confirmText = 'Confirmar';
  public string $cancelText = 'Cancelar';
  public string $confirmEvent = '';
  public array $confirmParams = [];

  protected $listeners = ['showConfirmDialog'];

  public function showConfirmDialog(array $data): void {
    $this->title = $data['title'] ?? '¿Estás seguro?';
    $this->message = $data['message'] ?? '';
    $this->confirmText = $data['confirmText'] ?? 'Confirmar';
    $this->cancelText = $data['cancelText'] ?? 'Cancelar';
    $this->confirmEvent = $data['confirmEvent'] ?? '';
    $this->confirmParams = $data['confirmParams'] ?? [];
    $this->show = true;
  }

  public function confirm(): void {
    if ($this->confirmEvent) {
      $this->dispatch($this->confirmEvent, ...$this->confirmParams);
    }
    $this->cancel();
  }

  public function cancel(): void {
    $this->show = false;
    $this->reset(['title', 'message', 'confirmText', 'cancelText', 'confirmEvent', 'confirmParams']);
  }

  public function render() {
    return view('livewire.confirm-dialog');
  }
}
