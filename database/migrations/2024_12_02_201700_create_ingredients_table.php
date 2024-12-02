<?php

use App\Constants\WeightUnit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('total_weight');
            $table->decimal('remaining_weight');
            $table->tinyInteger('weight_unit')->default(WeightUnit::KILOGRAM);
            $table->boolean('email_notification_sent')->default(false)->comment('email notification sent if stock level reaches 50%');
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
        Schema::dropIfExists('ingredients');
    }
}
