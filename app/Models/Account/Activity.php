<?php

namespace App\Models\Account;

use App\Traits\HasUuid;
use App\Helpers\DateHelper;
use App\Traits\Journalable;
use App\Models\Contact\Contact;
use App\Models\Journal\JournalEntry;
use App\Traits\SupportsMailcowCaldav;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\MailcowCaldavSupport;
use App\Models\Instance\Emotion\Emotion;
use App\Interfaces\IsJournalableInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Http\Resources\Contact\ContactShort as ContactShortResource;

/**
 * @property int|null $activity_type_id
 */
class Activity extends Model implements IsJournalableInterface, MailcowCaldavSupport
{
    use Journalable, HasUuid, SupportsMailcowCaldav;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activities';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['happened_at'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'account',
        'type',
        'contacts',
    ];

    /**
     * Get the account record associated with the activity.
     *
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the contact record associated with the activity.
     *
     * @return BelongsToMany
     */
    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }

    /**
     * Get the activity type record associated with the activity.
     *
     * @return BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    /**
     * Get all of the activities journal entries.
     */
    public function journalEntries()
    {
        return $this->morphMany(JournalEntry::class, 'journalable');
    }

    /**
     * Get the emotion records associated with the activity.
     *
     * @return BelongsToMany
     */
    public function emotions()
    {
        return $this->belongsToMany(Emotion::class, 'emotion_activity', 'activity_id', 'emotion_id')
            ->withPivot('account_id')
            ->withTimestamps();
    }

    /**
     * Get the summary for this activity.
     *
     * @return string or null
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Get the key of the title of the activity.
     *
     * @return string or null
     */
    public function getTitle()
    {
        return $this->type ? $this->type->translation_key : null;
    }

    /**
     * Get all the contacts this activity is associated with.
     */
    public function getContactsForAPI()
    {
        $attendees = $this->contacts->filter(function ($contact) {
            // This should not be possible!
            return $contact->account_id === $this->account_id;
        });

        return ContactShortResource::collection($attendees);
    }

    /**
     * Gets the information about the activity for the journal.
     *
     * @return array
     */
    public function getInfoForJournalEntry()
    {
        return [
            'type' => 'activity',
            'id' => $this->id,
            'activity_type' => (!is_null($this->type) ? $this->type->name : null),
            'summary' => $this->summary,
            'description' => $this->description,
            'day' => $this->happened_at->day,
            'day_name' => mb_convert_case(DateHelper::getShortDay($this->happened_at), MB_CASE_TITLE, 'UTF-8'),
            'month' => $this->happened_at->month,
            'month_name' => mb_convert_case(DateHelper::getShortMonth($this->happened_at), MB_CASE_UPPER, 'UTF-8'),
            'year' => $this->happened_at->year,
            'attendees' => $this->getContactsForAPI(),
        ];
    }

    /**
     * Get the mailcow cal dav id attribute
     *
     * @return string
     */
    public function getMailcowCaldavDateAttributeAttribute()
    {
        return 'happened_at';
    }
}