<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStatusInTableQCCodes extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('q_c_codes', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->after('dual_weight_enumerator')
                ->nullable(false)
                ->default(0)
                ->comment('0未处理；1正在处理；2已处理');
        });
        DB::connection()->getPdo()->exec('update q_c_codes set status=2 where dual_d is not NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('q_c_codes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
