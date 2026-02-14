<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemDeleted implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public function __construct(
    public int $itemId,
    public int $itemsListId,
  ) {
  }

  public function broadcastOn(): array {
    return [
      new Channel('listly.' . $this->itemsListId),
    ];
  }
}
