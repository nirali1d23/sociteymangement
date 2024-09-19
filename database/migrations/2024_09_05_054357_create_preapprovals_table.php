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
        Schema::create('preapprovals', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_name');
            $table->date('date');
            $table->string('flat_no');
            $table->string('contact_number')->nullble();
            $table->string('vehicle_number')->nullble();
            $table->string('purpose')->nullble();
            $table->boolean('status')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preapprovals');
    }
};
