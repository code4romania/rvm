<?php

declare(strict_types=1);

use App\Models\County;
use App\Models\Organisation;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('role')->nullable();
            $table->foreignIdFor(County::class)->nullable()->constrained();
            $table->foreignIdFor(Organisation::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamp('password_set_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
};
