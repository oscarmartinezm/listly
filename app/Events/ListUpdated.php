<?php
namespace App\Events;

use App\Models\ShoppingList;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ListUpdated implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public function __construct(public ShoppingList $shoppingList) {
  }

  public function broadcastOn(): array {
    return [
      new Channel('shopping-list.' . $this->shoppingList->id),
    ];
  }
}
