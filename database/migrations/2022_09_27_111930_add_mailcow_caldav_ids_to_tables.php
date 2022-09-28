<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMailcowCaldavIdsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->getTables() as $tableName) {
            if (!Schema::hasColumn($tableName, 'mailcow_caldav_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('mailcow_caldav_id')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->getTables() as $tableName) {
            if (Schema::hasColumn($tableName, 'mailcow_caldav_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('mailcow_caldav_id');
                });
            }
        }
    }

    /**
     * Get tables
     *
     * @return array
     */
    protected function getTables(): array
    {
        return ['reminders', 'reminder_outbox', 'special_dates', 'life_events', 'activities', 'tasks'];
    }
}