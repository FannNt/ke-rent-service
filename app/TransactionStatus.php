<?php

namespace App;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    CASE COMPLETED = 'completed';
}
