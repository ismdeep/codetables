<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQCCodesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('q_c_codes', function (Blueprint $table) {
            $table->id();

            $table->integer('k')->nullable(false);
            $table->integer('p')->nullable(false);
            $table->integer('n')->nullable(false);
            $table->integer('d')->nullable(true);
            $table->text('code')->nullable(true);
            $table->text('generator_matrix')->nullable(true);
            $table->text('weight_enumerator')->nullable(true);

            $table->integer('dual_n')->nullable(true);
            $table->integer('dual_k')->nullable(true);
            $table->text('dual_generator_matrix')->nullable(true);
            $table->text('dual_weight_enumerator')->nullable(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('q_c_codes');
    }
}
