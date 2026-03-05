<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\JobOffer;
use App\Models\Category;
use App\Models\Location;
use App\Notifications\JobOfferApprovedNotification;
use Illuminate\Support\Facades\Notification;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_notified_when_offer_is_approved()
    {
        Notification::fake();

        $admin = User::factory()->create(['account_type' => 'admin']);
        $employer = User::factory()->create(['account_type' => 'employer']);

        $category = Category::create(['name' => 'IT', 'slug' => 'it']);
        $location = Location::create(['country' => 'Poland', 'city' => 'Warsaw', 'address' => 'Test']);

        $offer = JobOffer::create([
            'user_id' => $employer->id,
            'title' => 'Test Offer',
            'company_name' => 'Test Co',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'description' => 'Test Desc',
            'employment_type' => 'B2B',
            'experience_level' => 'Junior',
            'is_approved' => false,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.approve-offer', $offer->id));

        Notification::assertSentTo($employer, JobOfferApprovedNotification::class);
    }

    public function test_can_fetch_notifications()
    {
        $user = User::factory()->create();
        $offer = (object) ['id' => 1, 'title' => 'Test Offer'];

        $user->notify(new JobOfferApprovedNotification($offer));

        $response = $this->actingAs($user)->getJson(route('notifications.index'));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'notifications')
            ->assertJsonPath('unreadCount', 1);
    }

    public function test_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $offer = (object) ['id' => 1, 'title' => 'Test Offer'];

        $user->notify(new JobOfferApprovedNotification($offer));
        $notification = $user->notifications->first();

        $this->actingAs($user)
            ->postJson(route('notifications.mark-as-read', $notification->id));

        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }
}
