<?php

declare(strict_types=1);

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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('type');
            $table->string('name');
            $table->date('signed_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->foreignIdFor(Organisation::class)->constrained()->cascadeOnDelete();
        });
    }
};
