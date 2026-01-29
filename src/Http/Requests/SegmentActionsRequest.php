<?php

namespace Juzaweb\Modules\EmailMarketing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Juzaweb\Modules\Core\Rules\AllExist;

class SegmentActionsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'action' => ['required'],
            'ids' => ['required', 'array', 'min:1', new AllExist('email_segments', 'id')],
        ];
    }
}
