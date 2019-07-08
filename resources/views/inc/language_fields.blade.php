@if ($fields)
    @foreach($fields as $field)
        <?php $key = $field['key'];?>
        @if ($field['type'] == 'text')
            <div class="form-group">
                <label class="col-sm-3 control-label">{{ $field['name'] }} {{ $field['required'] ? '*' : '' }}</label>
                <div class="col-sm-6">
                    {!! Form::text($key, $object->$key ? $object->$key : old($key), array('placeholder' => $field['placehoder'], 'class' => 'form-control')) !!}
                </div>
            </div>
        @elseif ($field['type'] == 'number')
            <div class="form-group">
                <label class="col-sm-3 control-label">{{ $field['name'] }} {{ $field['required'] ? '*' : '' }}</label>
                <div class="col-sm-6">
                    {!! Form::number($key, $object->$key ? $object->$key : old($key), array('placeholder' => $field['placehoder'], 'class' => 'form-control')) !!}
                </div>
            </div>
        @elseif ($field['type'] == 'textarea')
            <div class="form-group">
                <label class="col-sm-3 control-label">{{ $field['name'] }} {{ $field['required'] ? '*' : '' }}</label>
                <div class="col-sm-9">
                    <textarea name="{{ $key }}" class="rich_text">{{ old($key) ? old($key) : $object->$key }}</textarea>
                </div>
            </div>
        @endif
    @endforeach
@endif