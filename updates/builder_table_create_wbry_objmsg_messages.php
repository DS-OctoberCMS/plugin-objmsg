<?php namespace Wbry\ObjMsg\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateWbryObjmsgMessages extends Migration
{
    public function up()
    {
        Schema::create('wbry_objmsg_messages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->text('message')->nullable();
            $table->integer('post_id')->nullable()->unsigned();
            $table->boolean('is_admin')->nullable()->default(0);
            $table->boolean('is_view')->nullable()->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('wbry_objmsg_messages');
    }
}