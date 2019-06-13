<?php

namespace Huojunhao\DwGenerator\DwPlugin;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use App\Lib\Common\CommonBase\FileUtil;
use Illuminate\Console\Command;

class DwPluginTenancy extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dp:tenancy  {--remove} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成';

    protected $stub_dir;

    protected $dir_map ;

    protected $app_config_path;

    protected $provider_hook = <<<DDD
App\Providers\OwnServiceProvider::class
DDD;

    protected $provider_config = <<<DDD
App\Providers\TenancyServiceProvider::class,
DDD;

    protected $facade_hook = <<<DDD
\App\Admin\Extensions\CusAdmin::class
DDD;
    protected $facade_alias = <<<DDD
        'Tenancy' => \App\Tenancy\Facades\Tenancy::class,

DDD;



    protected $database_env = <<<DDD
    
TenancyUser=username,password,name,avatar,remember_token
TenancyRole=name,slug
TenancyPermission=name,slug,http_method,http_path
TenancyRoleTenancyUser=tenancy_role_id,tenancy_user_id
TenancyRoleTenancyPermission=tenancy_role_id,tenancy_permission_id
TenancyUserTenancyPermission=tenancy_user_id,tenancy_permission_id

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
     */
    public function handle()
    {
        $this->init_configs();//初始化配置项
//        dump($this->stub_dir);

//        $this->makeCommand();
        if (!$this->option("remove")) {
            foreach ($this->dir_map as $key => $item) {
                FileUtil::publish($key, $item);
            }


            $this->updateProviderConfig();
            //最后要执行下composer dump-autoload
            $this->info(shell_exec("composer dump-autoload"));

        }else{
            foreach ($this->dir_map as $key => $item) {
                FileUtil::unlinkFileOrDir($item);
            }
            //删除
            $this->updateProviderConfig(true);
        }

    }


    protected function updateProviderConfig($remove=false)
    {
        $app_config_content = file_get_contents($this->app_config_path);

        if ($remove) {
            $app_config_content = str_replace($this->provider_config, "", $app_config_content);
            $app_config_content = str_replace($this->facade_alias, "", $app_config_content);

        }else{


            //如果配置文件中不包含现在的provider配置，则添加
            if (!str_contains($app_config_content,$this->provider_config )) {
                //如果hook有逗号，先逗号去除
                if (str_contains($app_config_content, $this->provider_hook . ",")) {
                   $app_config_content = str_replace($this->provider_hook . ',', $this->provider_hook,$app_config_content);
                }
                $app_config_content = str_replace($this->provider_hook, $this->provider_hook.",".PHP_EOL.$this->provider_config, $app_config_content);
            }
            //添加alias
            if (!str_contains($app_config_content,$this->facade_alias )) {
                //如果hook有逗号，先逗号去除
                if (str_contains($app_config_content, $this->facade_hook . ",")) {
                    $app_config_content = str_replace($this->facade_hook . ',', $this->facade_hook,$app_config_content);
                }
                $app_config_content = str_replace($this->facade_hook, $this->facade_hook.",".PHP_EOL.$this->facade_alias, $app_config_content);
            }


        }

        file_put_contents($this->app_config_path, $app_config_content);



    }


    private function init_configs()
    {

//        $this->stub_dir = $this->getBaseStubDir().'/tenancyStubs/';
        $this->stub_dir = storage_path(). '/plugins/tenancyStubs/';
        $this->app_config_path = config_path("app.php");

        $this->genDirMap();

//        if (!$this->command_name) {
//            throw new \Exception("command_name必填");
//        }

    }


    protected function genDirMap()
    {
        $this->dir_map = [
            $this->stub_dir . "Tenancy" => app_path("Tenancy"),
            $this->stub_dir . "tenancy.php" => config_path("tenancy.php"),
            $this->stub_dir."TenancyServiceProvider.php" => app_path("Providers/TenancyServiceProvider.php"),
            $this->stub_dir . "tenancyview" => resource_path("views/tenancy"),
            $this->stub_dir."create_tenancy_tables.php" => database_path("migrations")."/2016_01_05_115422_create_tenancy_tables.php",
            $this->stub_dir."InitTenancySeeder.php" => database_path("seeds")."/InitTenancySeeder.php",
            $this->stub_dir."TenancyNotification" => app_path('Notifications/Tenancy')

        ];


        $models = FileUtil::allFile($this->stub_dir . "model/");
//        dump($models);
        foreach ($models as $model) {
            $this->dir_map[$this->stub_dir . "model/". $model] = app_path("model/") . $model;
        }
//        dump($this->dir_map);



    }





}
