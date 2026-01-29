@extends('admin::layouts.admin')

@section('content')
    <form action="{{ $action }}" class="form-ajax" method="post">
        @if ($model->exists)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-12">
                <a href="{{ $backUrl }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Save') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-9">
                <x-card title="{{ __('Information') }}">
                    {{ Field::text(__('Template Id'), 'template_id', ['value' => $model->template_id]) }}

                    {{ Field::text(__('Name'), 'name', ['value' => $model->name]) }}

                    {{ Field::text(__('Subject'), 'subject', ['value' => $model->subject]) }}

                    {{ Field::text(__('Content'), 'content', ['value' => $model->content]) }}

                    {{ Field::select(__('Send Type'), 'send_type', [
                        'value' => $model->send_type?->value ?? 'manual',
                        'options' => [
                            'manual' => __('email-marketing::translation.campaign.send_type.manual'),
                            'auto' => __('email-marketing::translation.campaign.send_type.auto'),
                        ],
                        'id' => 'send-type-select',
                    ]) }}

                    {{-- Manual Campaign Fields --}}
                    <div id="manual-fields" style="display: none;">
                        {{ Field::select(__('Segments'), 'segment_ids[]', [
                            'value' => $model->segments->pluck('id')->toArray(),
                            'options' => $segments,
                            'multiple' => true,
                            'class' => 'select2-ajax',
                        ]) }}
                    </div>

                    {{-- Auto Campaign Fields --}}
                    <div id="auto-fields" style="display: none;">
                        {{ Field::select(__('Automation Trigger'), 'automation_trigger_type', [
                            'value' => $model->automation_trigger_type,
                            'options' => app('Juzaweb\Modules\EmailMarketing\Contracts\AutomationTriggerRegistryInterface')->labels(),
                            'placeholder' => __('-- Select Trigger --'),
                        ]) }}

                        {{ Field::number(__('Delay (hours)'), 'automation_delay_hours', [
                            'value' => $model->automation_delay_hours ?? 0,
                            'min' => 0,
                            'help' => __('Delay time before sending email (in hours)'),
                        ]) }}
                    </div>

                    {{ Field::select(__('Status'), 'status')->dropDownList([]) }}
                </x-card>
            </div>

            <div class="col-md-3">

            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        $(function() {
            const $sendTypeSelect = $('#send-type-select');
            const $manualFields = $('#manual-fields');
            const $autoFields = $('#auto-fields');

            function toggleFields() {
                const sendType = $sendTypeSelect.val();

                if (sendType === 'auto') {
                    $manualFields.hide();
                    $autoFields.show();
                } else {
                    $manualFields.show();
                    $autoFields.hide();
                }
            }

            // Initial state
            toggleFields();

            // On change
            $sendTypeSelect.on('change', toggleFields);
        });
    </script>
@endsection
