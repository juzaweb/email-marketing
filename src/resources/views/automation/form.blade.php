@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group float-right">
                <a href="{{ $backUrl }}" class="btn btn-warning">
                    <i class="fa fa-arrow-left"></i> {{ __('core::translation.back') }}
                </a>
                <button type="submit" form="form-save" class="btn btn-success">
                    <i class="fa fa-save"></i> {{ __('core::translation.save') }}
                </button>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-8">
            <form action="{{ $action }}" method="post" id="form-save" class="form-ajax">
                @if($model->id)
                    @method('PUT')
                @endif

                <x-card>
                    {{ Field::text($model, 'name', [
                        'required' => true,
                        'label' => __('core::translation.name')
                    ]) }}

                    {{ Field::textarea($model, 'description', [
                        'label' => __('core::translation.description')
                    ]) }}

                    {{ Field::select($model, 'template_id', [
                        'required' => true,
                        'options' => $templates,
                        'label' => __('email-marketing::translation.email_templates')
                    ]) }}

                    <div class="row">
                        <div class="col-md-6">
                            {{ Field::select($model, 'trigger_type', [
                                'required' => true,
                                'options' => array_combine(array_keys($triggers), array_column($triggers, 'label')),
                                'label' => __('email-marketing::translation.automation.trigger.label')
                            ]) }}
                        </div>
                        <div class="col-md-6">
                            {{ Field::text($model, 'delay_hours', [
                                'type' => 'number',
                                'default' => 0,
                                'label' => __('email-marketing::translation.automation.delay_hours')
                            ]) }}
                        </div>
                    </div>
                </x-card>
            </form>
        </div>

        <div class="col-md-4">
             <x-card>
                {{ Field::checkbox($model, 'active', [
                    'label' => __('core::translation.active'),
                    'checked' => $model->active
                ]) }}
            </x-card>
        </div>
    </div>
@endsection
