<div class="form-group" style="margin-bottom: 0">
    @if($label)
        <label>{{ $label }} Discount</label>
    @endif
    <div class="input-group">
        <div class="input-group-addon">
            <i class="fa fa-tags"></i>
        </div>
        <input type="number" class="form-control" name="{{ $name }}" min="0" id="{{ $name }}" value="0" onchange="{{ $function }}" @cannot('createDiscount', App\Invoice::class) {{ 'readonly' }} @endcannot>
        <select id="select_{{ $name }}" class="form-control" onchange="{{ $function }}" @cannot('createDiscount', App\Invoice::class) {{ 'readonly' }} @endcannot>
            @if($availableDiscounts)
                <option value="" data-percentage="0">0</option>
                @foreach($availableDiscounts as $discount)
                <option value="" data-percentage="{{ $discount }}">{{ $discount }}%</option>
                @endforeach
            @else
                {{-- User 14404 as OP Jogja --}}
                @if(isset($user) && $user->id == 14404)
                    <option value="" data-percentage="0">0</option>
                    <option value="" data-percentage="10">10%</option>
                    <option value="" data-percentage="15">15%</option>
                    <option value="" data-percentage="20">20%</option>
                    <option value="" data-percentage="25">25%</option>
                    <option value="" data-percentage="30">30%</option>
                    {{-- User 13805 as Sonny Jogja --}}
                @elseif(isset($user) && $user->id == 14060)
                    <option value="" data-percentage="0">0</option>
                    <option value="" data-percentage="50">50%</option>
                    <option value="" data-percentage="100">100%</option>
                    {{-- Default --}}
                @elseif(isset($user) && $user->id == 4)
                    <option value="" data-percentage="0">0</option>
                    <option value="" data-percentage="50">80%</option>
                    <option value="" data-percentage="100">100%</option>
                @elseif(isset($user) && $user->id == 15762)
                    <option value="" data-percentage="0">0</option>
                    <option value="" data-percentage="10">10%</option>
                    <option value="" data-percentage="20">20%</option>
                    <option value="" data-percentage="50">50%</option>
                    <option value="" data-percentage="80">80%</option>
                @else
                    <option value="" data-percentage="0">0</option>
                    <option value="" data-percentage="10">10%</option>
                    <option value="" data-percentage="20">20%</option>
                    {{-- <option value="" data-percentage="80">80%</option> --}}
                @endif
            @endif
        </select>
    </div>
    <small id="error_{{ $name }}" class="text-danger" hidden>Error</small> <br>
</div>