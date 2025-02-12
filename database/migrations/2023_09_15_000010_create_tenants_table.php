<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->string('identification', 100)->unique();
            $table->string('name', 100)->unique();
            $table->string('phone', 100);
            $table->string('email', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->jsonb('data')->nullable();

            $table->timestamps();

            $table->unsignedBigInteger('country_id')
                ->nullable()
                ->constrained('countries');

            $table->unsignedBigInteger('state_id')
                ->nullable()
                ->constrained('states');

            $table->unsignedBigInteger('city_id')
                ->nullable()
                ->constrained('cities');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
