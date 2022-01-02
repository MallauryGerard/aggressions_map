<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAggressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aggressions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('datetime');
            $table->enum('type', [
                'Vol avec effraction',
                'Vol simple ou aggravé',
                'Agression (physique ou verbale)',
                'Exhibition',
                'Dégradation'
            ])->nullable();
            $table->text('description');
            $table->text('contact')->nullable();
            $table->string('coordinates');
            $table->string('ip');
            $table->boolean('is_visible')->default(0);
            $table->boolean('is_moderate')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aggressions');
    }
}
