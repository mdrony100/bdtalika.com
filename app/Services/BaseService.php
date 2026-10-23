<?php
namespace App\Services;

abstract class BaseService{

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
