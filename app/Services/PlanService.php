<?php

namespace App\Services;

use App\Models\Plan;

class PlanService{

    public function store(array $data)
    {
        $plan = new Plan();

        $plan->title = $data['title'];
        $plan->subtitle = $data['subtitle'];
        $plan->price = adminCurrencyAmount($data['price']);
        $plan->prev_price = adminCurrencyAmount($data['prev_price']);
        $plan->price_color = $data['price_color'];
        $plan->status = $data['status'];
        $plan->post_limit = $data['post_limit'];
        $plan->post_duration = $data['post_duration'];

        if($data['attribute']){
            $plan->attribute = json_encode($data['attribute'],true);
        }

        $plan->save();
    }

    public function update(array $data, $id)
    {
        $plan = Plan::findOrFail($id);

        $plan->title = $data['title'];
        $plan->subtitle = $data['subtitle'];
        $plan->price = adminCurrencyAmount($data['price']);
        $plan->prev_price = adminCurrencyAmount($data['prev_price']);
        $plan->price_color = $data['price_color'];
        $plan->status = $data['status'];
        $plan->post_limit = $data['post_limit'];
        $plan->post_duration = $data['post_duration'];

        if($data['attribute']){
            $plan->attribute = json_encode($data['attribute'],true);
        }

        $plan->update();
    }

    public function status($id1,$id2)
    {
        $data = Plan::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    public function bulkdelete($ids)
    {
        $bulk_ids = explode(",",$ids);
        foreach($bulk_ids as $key=>$id){
            if($id){
                try {
                    $this->delete($id);
                } catch (\Exception $e) {
                    $msg = $e->getMessage();
                }
            }
        }
    }

    public function destroy($id)
    {
        try {
            $this->delete($id);
            $msg = 'Data Deleted Successfully.';
        } catch (\Throwable $th) {
            $msg = 'Something went wrong.';
        }
        return response()->json($msg);

        $msg = 'Plan Deleted Successfully.';
        return response()->json($msg);
    }

    public function delete($id){
        $data = Plan::findOrFail($id);

        $data->delete();

        return true;
    }
}
