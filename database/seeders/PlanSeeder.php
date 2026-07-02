<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;
class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $plans = array(
          array('id' => '1','title' => 'Starter','labelcolor' => 'price-color-1','iconname' => 'star','price' => '50000','is_featured' => '1','is_recommended' => '0','is_trial' => '0','status' => '1','days' => '30','trial_days' => '0','data' => '{"messages_limit":"1000","contact_limit":"100","device_limit":"3","template_limit":"20","apps_limit":"10","chatbot":"true","bulk_message":"false","schedule_message":"false","access_chat_list":"false","access_group_list":"false"}','created_at' => '2023-03-05 18:29:25','updated_at' => '2023-03-05 18:31:25'),
          
          array('id' => '2','title' => 'Enterprise','labelcolor' => 'price-color-2','iconname' => 'check_circle','price' => '250000','is_featured' => '1','is_recommended' => '1','is_trial' => '1','status' => '1','days' => '30','trial_days' => '10','data' => '{"messages_limit":"-1","contact_limit":"-1","device_limit":"-1","template_limit":"-1","apps_limit":"-1","chatbot":"true","bulk_message":"true","schedule_message":"true","access_chat_list":"true","access_group_list":"true"}','created_at' => '2023-03-05 18:30:39','updated_at' => '2023-03-05 18:33:59'),
          
          array('id' => '3','title' => 'Basic','labelcolor' => 'price-color-3','iconname' => 'bolt','price' => '100000','is_featured' => '1','is_recommended' => '0','is_trial' => '0','status' => '1','days' => '30','trial_days' => '0','data' => '{"messages_limit":"-1","contact_limit":"100","device_limit":"100","template_limit":"100","apps_limit":"10","chatbot":"false","bulk_message":"false","schedule_message":"true","access_chat_list":"true","access_group_list":"false"}','created_at' => '2023-03-05 18:32:49','updated_at' => '2023-03-05 18:32:49')
      );

      foreach ($plans as $plan) {
          Plan::updateOrCreate(
              ['id' => $plan['id']],
              [
                  'title' => $plan['title'],
                  'labelcolor' => $plan['labelcolor'],
                  'iconname' => $plan['iconname'],
                  'price' => $plan['price'],
                  'is_featured' => $plan['is_featured'],
                  'is_recommended' => $plan['is_recommended'],
                  'is_trial' => $plan['is_trial'],
                  'status' => $plan['status'],
                  'days' => $plan['days'],
                  'trial_days' => $plan['trial_days'],
                  'data' => json_decode($plan['data'], true)
              ]
          );
      }
  }
}
