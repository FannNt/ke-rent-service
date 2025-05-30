<?php

namespace App\Http\Repositories\Terms;

use App\Models\Terms;

class TermsRepository {

    public function showTerms()
    {
        return Terms::all();
    }

    public function addTerms(array $data)
    {
        return Terms::create($data);
    }

    public function editTerns($id,array $data)
    {
        $terms = Terms::findOrFail($id);
        $terms->update($data);
        return $terms;
    }

    public function removeTerms($id)
    {
        return Terms::where('id',$id)->delete();
    }
}
