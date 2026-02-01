<?php

namespace Juzaweb\Modules\EmailMarketing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutomationRuleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:250'],
            'template_id' => ['required', 'exists:email_marketing_templates,id'],
            'trigger_type' => ['required', 'string', 'max:100'],
            'delay_hours' => ['nullable', 'integer', 'min:0'],
            'active' => ['boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
