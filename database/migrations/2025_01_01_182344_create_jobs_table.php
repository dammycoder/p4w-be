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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('about_company');                 
            $table->string('job_title');                     
            $table->string('job_location');                 
            $table->string('work_type');                   
            $table->string('job_category');                
            $table->string('title');                    
            $table->json('primary_objectives');             
            $table->text('job_description');                
            $table->json('job_responsibilities');          
            $table->string('reports_to');                   
            $table->json('minimum_qualification');           
            $table->string('job_code')->unique();          
            $table->timestamps();                          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
