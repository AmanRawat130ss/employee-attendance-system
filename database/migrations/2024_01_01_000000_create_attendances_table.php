<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->time('login_time')->nullable();
            $table->time('logout_time')->nullable();
            $table->decimal('total_hours', 5, 2)->default(0.00);
            $table->enum('status', ['Present', 'Absent', 'Half Day', 'Leave', 'Holiday'])->default('Present');
            $table->string('notes')->nullable();
            $table->timestamps();

            // Business Rule: An employee cannot have duplicate attendance for the same date
            $table->unique(['user_id', 'date'], 'unique_user_attendance_per_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
