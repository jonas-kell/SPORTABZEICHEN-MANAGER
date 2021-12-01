<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastTimePropertiesToAthletesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->integer("proofOfSwimming")->nullable();
            $table->integer("lastBadgeYear")->nullable();
            $table->integer("numberOfBadgesSoFar")->default(0);
            $table->string("identNo")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn("proofOfSwimming");
            $table->dropColumn("lastBadgeYear");
            $table->dropColumn("numberOfBadgesSoFar");
            $table->dropColumn("identNo");
        });
    }
}
