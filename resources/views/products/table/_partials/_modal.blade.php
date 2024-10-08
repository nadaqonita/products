@isset($modal)
    @foreach ($modal as $item)
        <x-modal id="{{ $item['id'] }}" title="{{ $item['title'] }}" dialogClass="{{ $item['dialogClass'] }}">
            <form method="POST" id="{{ $item['formId'] }}" action="{{ $item['action'] }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        @foreach ($item['content'] as $v)
                            @switch($v['tag'])
                                @case('textarea')
                                    <div class="col-{{ $v['column'] }}">
                                        <div class="form-floating mb-2">
                                            <textarea class="{{ $v['class'] }}" id="{{ $v['id'] }}" name="{{ $v['name'] }}"
                                                placeholder="{{ $v['placeholder'] }}" style="height:220px"
                                                @if (isset($v['disabled']) && $v['disabled'] != '') {{ 'disabled' }} @else {{ '' }} @endif></textarea>
                                            <label for="{{ $v['id'] }}">{{ $v['label'] }} <span
                                                    class="{{ $v['spanClass'] }}">{{ $v['span'] }}</span></label>
                                        </div>
                                    </div>
                                @break

                                @case('select')
                                    <div class="col-{{ $v['column'] }}">
                                        <div class="form-floating mb-2">
                                            <select class="{{ $v['class'] }}" id="{{ $v['id'] }}"
                                                name="{{ $v['name'] }}" placeholder="{{ $v['placeholder'] }}"
                                                @if (isset($v['disabled']) && $v['disabled'] != '') {{ 'disabled' }} @else {{ '' }} @endif>
                                                <option selected disabled>Pilih salah satu</option>
                                                @if (isset($v['option']))
                                                    @if (is_object($v['option']))
                                                        @foreach ($v['option'] as $i)
                                                            <option value="{{ $i->value }}">{{ $i->text }}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach ($v['option'] as $i)
                                                            <option value="{{ $i['value'] }}">{{ $i['text'] }}</option>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </select>
                                            <label for="{{ $v['id'] }}">{{ $v['label'] }} <span
                                                    class="{{ $v['spanClass'] }}">{{ $v['span'] }}</span></label>
                                        </div>
                                    </div>
                                @break

                                @default
                                    <div class="col-{{ $v['column'] }}">
                                        <div class="form-floating mb-2">
                                            <x-input type="{{ $v['type'] }}" class="{{ $v['class'] }}"
                                                id="{{ $v['id'] }}" name="{{ $v['name'] }}"
                                                placeholder="{{ $v['placeholder'] }}" />
                                            <label for="{{ $v['id'] }}">{{ $v['label'] }}
                                                {{ $v['required'] == true ? '<span class="text-danger">*</span>' : '' }} <span
                                                    class="{{ $v['spanClass'] }}">{{ $v['span'] }}</span></label>
                                        </div>
                                    </div>
                            @endswitch
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    @foreach ($item['button'] as $key => $btn)
                        <x-button type="{{ $btn['type'] }}" class="{{ $btn['attr']['class'] ?? '' }}"
                            data-bs-dismiss="{{ $btn['attr']['data-bs-dismiss'] ?? '' }}">{{ $btn['text'] }}</x-button>
                    @endforeach
                </div>
            </form>
        </x-modal>
    @endforeach

@endisset
