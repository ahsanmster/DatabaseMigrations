<?php

namespace digitalWaves\createMigrations;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateMigrationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:migration-files ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command read database and make migrations files if migrations files doesnt exist ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Throwable
     */
    public function handle()
    {
//if user want to change db_host
        $this->change_host();
//if user want to change db_name
        $this->change_db_name();
//if user want to change db_username
        $this->change_db_username();
//if user want to change db_password
        $this->change_db_password();
//delete all previous migrations files from database migration
        if ($this->confirm('Do you want to delete all previous  migrations files ?')) {
            $databasePath = database_path('/migrations');
            $this->remove_files_from_migrations($databasePath);
            $this->info('migrations deleted successfully');
        }
// check db name from .env file and return  array of table names of database
        $schema = DB::getDoctrineSchemamanager();
        $tables = $schema->listTables();
// check each table name of db and its columns name and data-type of each columns
        $getArray =  $this->table_column_type($tables);
//make migrations files according to given array collection of table name and its column names and data types of columns.
        $this->migration_files($getArray,$tables);
    }
    public function remove_files_from_migrations($databasePath){
        $dir_handle = opendir($databasePath);
        while($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                unlink($databasePath."/".$file);
            }
        }
        closedir($dir_handle);
        return;
    }
    public function change_host(){
        if ($this->confirm('Do you want to change DB_HOST ?')) {
            $host = $this->ask('What is your DB_HOST');
            Config::set('database.connections.mysql.host',$host );
        }
    }
    public function change_db_name(){
        if ($this->confirm('Do you want to change DB_DATABASE ?')) {
            $db_name = $this->ask('What is your DB_DATABASE');
            Config::set('database.connections.mysql.database',$db_name );
        }
    }
    public function change_db_username(){
        if ($this->confirm('Do you want to change DB_USERNAME ?')) {
            $db_userName = $this->ask('What is your DB_USERNAME');
            Config::set('database.connections.mysql.username',$db_userName );
        }
    }
    public function change_db_password(){
        if ($this->confirm('Do you want to change DB_PASSWORD ?')) {
            $db_password = $this->ask('What is your DB_PASSWORD');
            Config::set('database.connections.mysql.password',$db_password );
        }
    }
    public function getColumnType($columns){
        $getColumnName = [];
        $getColumnType=[];
        foreach ($columns as $key => $value) {
            $getColumnType[] = $value->getType()->getName() ;
            $getColumnName[]= $key;
        }
        // make custom array of db table column name  and its data-types
        $checkArray = [];
        for($i=0; $i < sizeof($getColumnName); $i++) {
            $checkArray[$getColumnName[$i]] = $getColumnType[$i];
        }
        return  $checkArray ;
    }
    public function migration_class_name($key){
        return str_replace(' ', '', ucwords(str_replace('_',' ',($key))));
    }
    public function file_class_name($date,$timestamp,$key){
        return $date.'_'.$timestamp.'_create_'.$key.'_table.php';
    }
    public function migration_file_data($replace,$key,$data){
        return view('createMigrations::temp', [
            'className' => $replace,
            'tableName' => $key,
            'tableData' => $data
        ])->render();

    }
    public function formatted_date(){
        return Carbon::now()->format('Y_m_d');
    }
    public function table_column_type($tables){
        $finalArray=collect();
        $bar = $this->output->createProgressBar(count($tables));
        foreach($tables as $table)
        {
            $columns = $table->getColumns();
            $checkArray =  $this->getColumnType($columns);
            $finalArray->put($table->getName(), $checkArray);
            $bar->advance();
        }
        $bar->finish();
        return $finalArray;

    }
    public function migration_files($getArray,$tables)
    {
        $bar = $this->output->createProgressBar(count($tables));
        foreach($getArray as $key => $data )
        {
            $replace = $this->migration_class_name($key);
            $path = database_path('migrations');
            $date = $this->formatted_date();
            $timestamp = Carbon::now()->timestamp;
            $fileName  =  $this->file_class_name($date,$timestamp,$key);
            $html = $this->migration_file_data($replace,$key,$data);
            file_put_contents($path.'/'.$fileName,$html);
            $bar->advance();
        }
        $bar->finish();
        $this->info('migrations added successfully');
    }

}
