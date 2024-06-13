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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->string('machine_ip');
            $table->string('machine_location');

            $table->string('employee_code');

            $table->string('punch_time');
            $table->string('upload_time');
            $table->string('punch_state')->nullable();
            $table->string('terminal_sn')->nullable();
            $table->string('terminal_alias')->nullable();
            $table->string('area_alias')->nullable();
            $table->string('is_mask')->nullable();
            $table->string('employee_id')->nullable(); // This is BioTime id. This does not belong to employees table.

            // set attendance sync flags
            $table->boolean('sync')->nullable()->default(0)->comment('0: UnSync , 1:Sync');
            $table->dateTime('mark_time')->nullable();


            $table->string('in_out')->nullable();

            //Sync Iteration columns
            $table->unsignedBigInteger('sync_iteration')->default(0)->nullable(); // based on key sync_iteration key in config



            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
//            $table->foreign('employee_code')->references('employee_code')->on('employees')->onDelete('cascade');
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
        Schema::dropIfExists('attendances');
    }
};
