<?php

namespace Tests\Unit;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_has_sender_and_receiver()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => 'Hello test content',
        ]);

        $this->assertInstanceOf(User::class, $message->sender);
        $this->assertInstanceOf(User::class, $message->receiver);
        $this->assertEquals($sender->id, $message->sender->id);
        $this->assertEquals($receiver->id, $message->receiver->id);
    }
}
