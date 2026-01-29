<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\EmailMarketing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SegmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:190'],
            'description' => ['nullable', 'max:250'],
        ];
    }
}
