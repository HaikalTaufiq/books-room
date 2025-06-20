<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $applicant_name
 * @property string $room_name
 * @property string $activity_type
 * @property int $capacity
 * @property string $booking_date
 * @property string $start_time
 * @property string $end_time
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Room|null $room
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereActivityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereApplicantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereBookingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereRoomName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBooking whereUserId($value)
 * @mixin \Eloquent
 */
class RoomBooking extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'user_id',
        'applicant_name',
        'room_name',
        'activity_type',
        'capacity',
        'booking_date',
        'start_time',
        'end_time',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
