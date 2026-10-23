<?php

namespace App\Services;

use App\Models\AccountProcess;
use Purifier;

class AccountProcessService{

    public function store(array $data)
    {
        $process = new AccountProcess();
        $input = $data;
        $input['details'] = Purifier::clean($data['details']);
        $process->fill($input)->save();
    }

    public function update(array $data,$id)
    {
        $process = AccountProcess::findOrFail($id);
        $input = $data;
        $input['details'] = Purifier::clean($data['details']);
        $process->update($input);
    }

    public function destroy($id)
    {
        $data = AccountProcess::findOrFail($id);
        $data->delete();
    }
}
