<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\Terms\AddTermsRequest;
use App\Http\Requests\Terms\EditTermsRequest;
use App\Services\TermsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    protected $termsService;
    public function __construct(TermsService $termsService)
    {
        $this->termsService = $termsService;
    }

    public function addTerms(AddTermsRequest $request)
    {
        $data = $this->termsService->addTerms($request->validated());

        Log::channel('terms')->info("ADD TERMS",[
            'added by' => auth()->user()->email,
            'name' => $data->name,
            'message' => $data->message,
            'id' => $data->id,
            ]);
        return ApiResponse::sendResponse($data,'');
    }

    public function editTerms($id,EditTermsRequest $request)
    {
        $data = $this->termsService->editTerms($id,$request->validated());
        Log::channel('terms')->info("EDIT TERMS",[
            'edited by' => auth()->user()->email,
            'request' => $request->validated()
        ]);
        return ApiResponse::sendResponse($data,'');
    }

    public function removeTerms($id)
    {
        $this->termsService->removeTerms($id);
        Log::channel('terms')->info("REMOVE TERMS",[
            'removed by' => auth()->user()->email,
        ]);
        return ApiResponse::sendResponse('Success',"success remove terms $id");
    }

}
