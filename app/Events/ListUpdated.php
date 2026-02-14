<?php
namespace App\Events;

use App\Models\ItemsList;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ListUpdated implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public function __construct(public ItemsList $itemsList) {
  }

  public function broadcastOn(): array {
    return [
      new Channel('listly.' . $this->itemsList->id),
    ];
  }
}
