<?php

use Illuminate\Database\Seeder;

class InitTenancySeeder extends Seeder
{

    protected $roles_data = [
        [
            'name' => '超级管理员',
            'slug' => 'administrator'
        ],
        [
            'name' => "干事",
            'slug' => "worker"
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {

        DB::transaction(function () {

            if ( !DB::table('tenancy_users')->where("id", 1)->first()) {

                $this->addFirstAdmin();
            }
    });
    }


    protected function addFirstAdmin()
    {

        dump("插入tenancy_user测试数据");

        $admin = new \App\Model\TenancyUser([
            'id' => 1,
            'account' => 'tenancy',
            'name' => '商户管理员',
            "email" => "123@qq.com",

//                    'parent_id' => 0,
//                    'node_id' => 0,
            'password' => bcrypt('123456ab')
        ]);
        $admin->save();
    }


}
