<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE cabinet_user MODIFY COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE cabinet_user MODIFY COLUMN role ENUM('admin', 'manager') NOT NULL DEFAULT 'manager'");
    }
};
