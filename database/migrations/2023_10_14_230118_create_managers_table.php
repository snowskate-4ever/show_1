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
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('resource_id');
            $table->unsignedBigInteger('resource_type')->nullable();
            $table->index  ('resource_type', 'manager_resource_type_idx');
            $table->foreign('resource_type', 'manager_resource_type_fk')->on('resource_types')->references('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->index  ('user_id', 'manager_user_idx');
            $table->foreign('user_id', 'manager_user_fk')->on('users')->references('id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managers');
    }
};
