<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use App\Lib\Common\CommonBase\FileUtil;
use Illuminate\Console\Command;

class DwMakeMigration extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:migration {table} {--type=create} {--fields=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成迁移文件';

    protected $stub_dir;
    protected $migrate_dir;
    protected $table;
    protected $type;
    protected $fields;
//    protected $type_arr = ["create","add","change"];

    protected $common_fields = [
        "email" => <<<DDD
\$table->string('@key',100)->unique()->comment('');

DDD
        ,
        "content|description" => <<<DDD
\$table->text('@key')->nullable()->comment('');

DDD
        ,
        "*_id" => <<<DDD
\$table->unsignedInteger('@key')->default(0)->comment('');

DDD
        ,
        "*_count|num|*_num|price|*_price" =><<<DDD
\$table->integer('@key')->default(0)->comment('');

DDD
        ,
        "is_*|status|rate|can_*|type|*_type|*_status|*_state|state" => <<<DDD
\$table->unsignedTinyInteger("@key")->default(0)->comment("");

DDD
        ,
        "*_at" => <<<DDD
\$table->dateTime("@key")->nullable()->comment("");

DDD

    ];

    protected $default_common_field = <<<DDD
\$table->string('@key',150)->nullable()->comment('');

DDD;

    protected $default_drop_field = <<<DDD
\$table->dropColumn('@key');

DDD;



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
     * @throws \Exception
     */
    public function handle()
    {
        $this->init_configs();//初始化配置项
        if ($this->has_created()) {
           // dump(1);
            dd($this->getClassName() . "已经创建过,请先执行migrate 命令生成数据库表");
//            die;
//            throw new \Exception($this->getClassName() . "已经创建过,请先执行migrate 命令生成数据库表");
        }
        $replace_data = [
            "//extra_fields_hook" => $this->getExtraFields(),
            "DummyClassName" => $this->getClassName(),
            "DummyTable" => $this->table
        ];

        if($this->type != "create"){
            $replace_data["//extra_drop_fields_hook"] =$this->getExtraDropFields();
        }
        $tasks = [
            [
                'stub_path' =>$this->stub_dir.$this->getMigrationStub(),
                'des_path' => $this->migrate_dir.$this->getFileName().".php"
            ]
        ];
        $this->quickTask($replace_data,$tasks);

    }






    protected function getExtraDropFields()
    {
        $arr = "";
        foreach ($this->getArrFields() as $field) {
            $arr .= str_replace("@key", $field, $this->default_drop_field);
        }
        return $arr;

    }


    protected function getMigrationStub()
    {
        switch ($this->type){
            case "create":
                return "create_migration_stub.php";
                break;
            case "add":
                return "add_column_migration_stub.php";
                break;
            case "drop":
                return "drop_column_migration_stub.php";
                break;

        }

    }


    protected function getExtraFields()
    {
        if ($this->fields){
            $field_arr = explode(",", $this->fields);

            $new_arr = '';
            foreach ($field_arr as $item) {
               $new_arr .= $this->getExtraField($item);
            }
            return $new_arr;
        }
        return "";
    }

    protected function getExtraField($item)
    {
        if ($key = $this->matchCommonField($item)) {
           return str_replace("@key", $item,$this->common_fields[$key]);
        }else{
            return str_replace("@key", $item,$this->default_common_field);
        }
    }

    protected function matchCommonField($item)
    {

        foreach ($this->common_fields as $key => $common_field) {

            if ( str_is($key,$item)) {
                return $key;
            }
        }
        return false;

    }


    private function init_configs()
    {

        $this->table =  $this->argument("table");

        $this->type = $this->option("type") ? : "create";

        $this->stub_dir = $this->getBaseStubDir().'/migrationStubs/';

        $this->migrate_dir = base_path("database/migrations/");

        $this->fields = $this->option("fields");
       $this->common_fields =  $this->initCommonFields($this->common_fields);
    }

    protected function getArrFields()
    {
        return explode(",", $this->fields) ? : [];

    }


    protected function getClassName()
    {
        $type = ucfirst($this->type);
        $table = ucfirst(camel_case($this->table));
        $fields = ucfirst(camel_case(implode("_",explode(",", $this->fields))));
        if ($this->type == "create") {
            return $type.$table."Table";
        }
       // dump($type);
        return $type.$fields."In".$table."Table";
    }

    private function getFileName($has_date=true)
    {
        if ($has_date) {
            return date("Y_m_d_His")."_".snake_case($this->getClassName());
        }
        return snake_case($this->getClassName());

    }

    protected function has_created()
    {
        $file_name = $this->getFileName(false);
        $arr = FileUtil::allFile($this->migrate_dir);
        foreach ($arr as $item) {
            if (str_contains($item,$file_name)) {
                return true;
            }
        }
        return false;
    }


}
