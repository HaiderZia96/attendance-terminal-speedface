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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');

            $table->string('student_reg_no')->nullable();

            $table->string('name');
            $table->string('image');
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->string('status')->default(1)->comment("0: inactive, 1:active");
            $table->string('campus')->nullable();

            $table->string('role_status')->nullable();



            $table->string('st_in_day1')->nullable();
            $table->string('st_in_day2')->nullable();
            $table->string('st_in_day3')->nullable();
            $table->string('st_in_day4')->nullable();
            $table->string('st_in_day5')->nullable();
            $table->string('st_in_day6')->nullable();
            $table->string('st_in_day7')->nullable();

            $table->string('st_out_day1')->nullable();
            $table->string('st_out_day2')->nullable();
            $table->string('st_out_day3')->nullable();
            $table->string('st_out_day4')->nullable();
            $table->string('st_out_day5')->nullable();
            $table->string('st_out_day6')->nullable();
            $table->string('st_out_day7')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
