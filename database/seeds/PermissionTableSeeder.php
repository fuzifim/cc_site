<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
		$permission = [
        	[
        		'name' => 'role-list',
        		'display_name' => 'Danh sách vai trò',
        		'description' => 'See only Listing Of Role'
        	],
        	[
        		'name' => 'role-create',
        		'display_name' => 'Tạo mới vai trò',
        		'description' => 'Create New Role'
        	],
        	[
        		'name' => 'role-edit',
        		'display_name' => 'Sửa vai trò',
        		'description' => 'Edit Role'
        	],
        	[
        		'name' => 'role-delete',
        		'display_name' => 'Xóa vai trò',
        		'description' => 'Delete Role'
        	],
        	[
        		'name' => 'item-list',
        		'display_name' => 'Display Item Listing',
        		'description' => 'See only Listing Of Item'
        	],
        	[
        		'name' => 'item-create',
        		'display_name' => 'Create Item',
        		'description' => 'Create New Item'
        	],
        	[
        		'name' => 'item-edit',
        		'display_name' => 'Edit Item',
        		'description' => 'Edit Item'
        	],
        	[
        		'name' => 'item-delete',
        		'display_name' => 'Delete Item',
        		'description' => 'Delete Item'
        	]
        ];

        foreach ($permission as $key => $value) { 
			$createPost = new Permission();
			$createPost->name         = $value['name'];
			$createPost->display_name = $value['display_name']; 
			$createPost->description  = $value['description'];
			$createPost->save();
        }
    }
}