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
        Schema::create('supervisor_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('placement_id')
                ->constrained('internship_placements')
                ->cascadeOnDelete();

            $table->foreignId('supervisor_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('assignment_type', 30);
            $table->timestamp('assigned_at')->useCurrent();

            $table->timestamps();

            $table->unique(
                ['placement_id', 'supervisor_id', 'assignment_type'],
                'unique_supervisor_assignment'
            );

            $table->index(['supervisor_id', 'assignment_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisor_assignments');
    }
};
