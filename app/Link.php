<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Link extends Model
{
    /**
     * Database: The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'checked_url', 'presence_url', 'checked_url_status_code', 'presence_url_status_code',
        'presence_url_added', 'presence_url_removed', 'last_check', 'email', 'details', 'active', 'user_id'
    ];

    /**
     * Database: The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:F d, Y h:i A',
        'updated_at' => 'datetime:F d, Y h:i A',

        'presence_url_added' => 'datetime:F d, Y h:i A',
        'presence_url_removed' => 'datetime:F d, Y h:i A',
        'last_check' => 'datetime:F d, Y h:i A',
    ];

    /**
     * Get the user-owner of the link
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the active entries
     *
     * @return mixed
     */
    public static function getActive()
    {
        return self::whereActive(1);
    }

    /**
     * Get entries belongs to the current user
     *
     * @return mixed
     */
    public static function getForCurrentUser()
    {
        return self::whereUserId(auth()->user()->id);
    }

    /**
     * Update the link info after check
     *
     * @param array $array
     * @return mixed
     */
    public function updateAfterCheck(array $array)
    {
        $removed = ($array['p_code'] == 200 ? null : ($this->presence_url_removed ?? Carbon::now()));
        return $this->update([
            'checked_url_status_code' => $array['c_code'],
            'presence_url_status_code' => $array['p_code'],
            'presence_url_removed' => $removed,
            'last_check' => Carbon::now()
        ]);
    }

    /**
     * Save the Link with presence_url_added date.
     *
     * @param array $options
     * @return boolean
     */
    public function save(array $options = [])
    {
        $model = DB::table('links')->where('id', $this->id)->first();
        $changes = $this->getDirty();
        if(!$model && !key_exists('presence_url_added', $changes)) {
            $changes['presence_url_added'] = Carbon::now();
            $id = DB::table('links')->insertGetId($changes);
            $this->id = $id;
            return true;
        } else return parent::save($options);
    }
}
