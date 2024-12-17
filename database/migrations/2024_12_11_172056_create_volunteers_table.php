<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedTinyInteger('age');
            $table->enum('gender', ['Male', 'Female', 'Non-binary', 'Prefer not to say']);
            $table->string('address');
            $table->string('email')->unique();
            $table->string('city');
            $table->string('state');
            $table->string('how_did_you_hear');
            $table->boolean('aware_of_payment_policy')->default(false);
            $table->boolean('approved')->default(false);
            $table->string('instagram_handle')->nullable();
            $table->string('backup_phone_number');
            $table->boolean('terms_and_conditions')->default(false);
            $table->boolean('available_for_outreach')->default(false);
            $table->text('skills');
            $table->enum('availability', ['Weekdays', 'Weekends', 'Both']);
            $table->text('motivation');
            $table->text('hopes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
