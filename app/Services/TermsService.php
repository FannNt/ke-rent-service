<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Http\Repositories\Terms\TermsRepository;
use Exception;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class TermsService
{
    protected $termsRepository;

    public function __construct(TermsRepository $termsRepository)
    {
        $this->termsRepository = $termsRepository;
    }

    public function showTerms()
    {
        return $this->termsRepository->showTerms();
    }
    public function addTerms(array $data)
    {
        return $this->termsRepository->addTerms($data);
    }

    public function editTerms($id,array $data)
    {
        try {
            return $this->termsRepository->editTerns($id,$data);
        }catch (UniqueConstraintViolationException $e) {
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('name already use',400)
            );
        }

    }

    public function removeTerms($id)
    {
        return $this->termsRepository->removeTerms($id);
    }

}
