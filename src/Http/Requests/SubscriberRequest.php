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

class SubscriberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:dns', 'max::190'],
            'name' => ['nullable', 'string', 'max::190'],
            'status' => ['required']
        ];
    }
}
