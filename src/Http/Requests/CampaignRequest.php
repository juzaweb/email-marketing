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
use Juzaweb\Modules\Admin\Rules\AllExist;

class CampaignRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'template_id' => ['nullable'],
			'name' => ['required'],
			'subject' => ['required'],
			'content' => ['required'],
			'status' => ['required'],
			'segment_ids' => ['nullable', 'array', new AllExist('email_segments', 'id')],
			'segment_ids.*' => ['uuid'],
		];
	}
}
