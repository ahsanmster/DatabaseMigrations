<?php
echo '
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class Create' . $className . 'Table extends Migration
{
   	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create("'.$tableName.'"'.', function(Blueprint $table)
        {'?>

      @foreach($tableData as $column => $type)
          @php
               if ($column != "created_at"  &&  $column != "updated_at" )
                  echo '$table->'.$type.'("'.$column.'");'
           @endphp

      @endforeach
          @php echo '$table->timestamps();' @endphp
  <?php echo
      '});
    }

    	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
    public function down()
    {
        Schema::dropIfExists("'. $tableName .'");
    }
}'

?>
